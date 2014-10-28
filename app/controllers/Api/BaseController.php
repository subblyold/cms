<?php

namespace Api;

use App, Controller, Input, Request, Response, Sentry;

use Subbly\Subbly;
use Subbly\Model\Collection;

class BaseController extends Controller
{
    /**
     * The constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get the offset asked in the request
     *
     * @return integer
     */
    protected function offset()
    {
        return (int) Input::get('offset');
    }

    /**
     * Get the limit asked in the request
     *
     * @return integer
     */
    protected function limit()
    {
        return (int) Input::get('limit');
    }

    /**
     * Setup the layout used by the controller.
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
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

            return $this->jsonErrorResponse('Auth required! Something is wrong with your credentials.', 401, array(
                'WWW-Authenticate' => 'Basic realm="Subbly authentication"',
            ));
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
        try {
            $response = parent::callAction($method, $parameters);
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
            return parent::callAction($method, $parameters);
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
        $response = Response::make(json_encode($data), $headers['status']['code']);

        foreach ($httpHeaders as $k=>$v) {
            $response->header($k, $v);
        }
        $response->header('Content-Type', 'application/json');

        return $response;
    }

    /**
     *
     */
    public function jsonCollectionResponse($key, Collection $collection)
    {
        $key = is_string($key)
            ? $key
            : 'entries'
        ;

        return $this->jsonResponse(array(
            $key     => $collection,
            'offset' => $collection->offset(),
            'limit'  => $collection->limit(),
            'total'  => $collection->total(),
        ));
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
}
