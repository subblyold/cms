<?php

namespace Subbly\CMS\Controllers\Api;

use Sentry, Controller;

use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Subbly\Subbly;
use Subbly\Model\Collection;

class BaseController extends Controller
{
    const LIMIT_DEFAULT = 50;
    const LIMIT_MIN     = 1;
    const LIMIT_MAX     = 100;

    /**
     * The constructor.
     */
    public function __construct()
    {
        Carbon::setToStringFormat(\DateTime::ISO8601);
    }

    /**
     * Get the offset and limit to apply
     *
     * @param array  $options The parameters (optional)
     *
     * @return array Return offset and limit
     */
    protected function apiOffsetLimit(array $options = null)
    {
        if ($options === null) {
            $options = Input::all();
        }

        $offset = null;

        if (isset($options['offset']))
        {
            $offset = (int) $options['offset'];
            $offset = $offset < 0 ? 0 : $offset;
        }

        $limit = isset($options['limit'])
            ? (int) $options['limit']
            : 0
        ;
        if ($limit < self::LIMIT_MIN) {
            $limit = self::LIMIT_DEFAULT;
        }
        else if ($limit > self::LIMIT_MAX) {
            $limit = self::LIMIT_MAX;
        }

        if ($offset === null && isset($options['page']))
        {
            $offset = ((int) $options['page'] - 1) * $limit;
            $offset = $offset < 0 ? 0 : $offset;
        }

        $offset ?: 0;

        return array($offset, $limit);
    }

    /**
     * Get the includes asked in the request
     *
     * @return array|null
     */
    protected function includes()
    {
        if (Input::has('includes')) {
            return (array) Input::get('includes');
        }

        return null;
    }

    protected function formatOptions(array $options = array())
    {
        $keysToTrim = array('includes');

        foreach ($options as $k=>$v)
        {
            if (in_array($k, $keysToTrim) && empty($v)) {
                unset($options[$k]);
            }
        }

        return $options;
    }

    /**
     * Controller filter to process to the authentication.
     *
     * @param mixed    $route
     * @param Request  $request
     *
     * @return void|Response
     */
    public function processAuthentication($route, $request)
    {
        $user = null;

        try {
            $credentials = array(
                'login'    => Request::server('PHP_AUTH_USER'),
                'password' => Request::server('PHP_AUTH_PW'),
            );

            $user = Subbly::api('subbly.user')->authenticate($credentials, false);
        }
        catch (\Exception $e)
        {
            if (
                $e instanceof \Cartalyst\Sentry\Users\UserNotActivatedException
                || $e instanceof \Cartalyst\Sentry\Users\UserSuspendedException
                || $e instanceof \Cartalyst\Sentry\Users\UserBannedException
            ) {
                return $this->jsonErrorResponse($e->getMessage());
            }

            return $this->jsonErrorResponse('Auth required! Something is wrong with your credentials.', 401, array());
            //     'WWW-Authenticate' => 'Basic realm="Subbly authentication"',
            // ));
        }

        // TODO Check if is admin
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $response = $parentResponse = null;

        try {
            $response = $parentResponse = parent::callAction($method, $parameters);
        }
        catch (\Exception $e) {
            $allowExceptions = array(
                'Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException',
                'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
            );

            if (in_array(get_class($e), $allowExceptions)) {
                return $this->jsonNotFoundResponse($e->getMessage());
            }
            if ($e instanceof \Subbly\Model\Exception\UnvalidModelException) {
                return $this->jsonErrorResponse($e->firstErrorMessage());
            }

            $response = $this->jsonErrorResponse('Fatal error!');
        }

        if (App::environment('local')) {
            return $parentResponse ?: parent::callAction($method, $parameters);;
        }

        return $response;
    }

    /**
     * Format a json Response
     *
     * @param mixed  $data        The data to format into JSON
     * @param array  $headers     Headers to set into the JSON output
     * @param array  $httpHeaders Http headers to insert into the Response
     *
     * @return Response
     */
    protected function jsonResponse($data = null, array $headers = array(), array $httpHeaders = array())
    {
        $headers = array_replace_recursive(array(
            'status' => array(
                'code'    => 200,
                'message' => 'OK',
            ),
        ), $headers);

        $data = array(
            'headers'  => $headers,
            'response' => $data,
        );

        // Debug data
        if (Config::get('app.debug', false) && Input::get('debug', false) == true) {
            $data['debug'] = $this->debugDatas();
        }

        $response = Response::make(json_encode($data), $headers['status']['code']);

        // HTTP headers
        foreach ($httpHeaders as $k=>$v) {
            $response->header($k, $v);
        }
        $response
            ->header('Content-Type', 'application/json')
            ->setEtag(md5(json_encode($data)))
        ;

        return $response;
    }

    /**
     * Format a json collection Response
     *
     * @param string                    $key        JSON key for the collection
     * @param \Subbly\Model\Collection  $collection The entries collection
     * @param array                     $extras     Extras values
     *
     * @return Response
     */
    public function jsonCollectionResponse($key, Collection $collection, array $extras = array())
    {
        $key = is_string($key)
            ? $key
            : 'entries'
        ;

        return $this->jsonResponse(array_replace($extras, array(
            $key     => $collection,
            'offset' => $collection->offset(),
            'limit'  => $collection->limit(),
            'total'  => $collection->total(),
        )));
    }

     /**
      * Format a json error Response
      *
      * @param string $errorMessage  The error message to send
      * @param int    $statusCode    The HTTP status code
      * @param array  $httpHeaders   Http headers to insert into the Response
      *
      * @return Response
      */
    protected function jsonErrorResponse($errorMessage, $statusCode = 400, array $httpHeaders = array())
    {
        $statusTexts = \Symfony\Component\HttpFoundation\Response::$statusTexts;

        return $this->jsonResponse(array(
            'error' => (string) $errorMessage,
        ), array(
            'status' => array(
                'code'    => (int) $statusCode,
                'message' => $statusTexts[$statusCode],
            ),
        ), $httpHeaders);
    }

     /**
      * Format a json Not Found Response
      *
      * @param string $message  The error message to send
      *
      * @return Response
      */
    protected function jsonNotFoundResponse($message = 'Not Found')
    {
        return $this->jsonResponse(array(
            'error' => $message,
        ), array(
            'status' => array(
                'code'    => 404,
                'message' => 'Not Found',
            ),
        ));
    }

    /**
     * Get the debug datas
     *
     * @return array
     */
    private function debugDatas()
    {
        return array(
            'db_queries' => \DB::getQueryLog(),
            'request' => array(
                'path'    => \Request::path(),
                'inputs'  => \Input::all(),
                'headers' => \Request::header(),
            )
        );
    }
}
