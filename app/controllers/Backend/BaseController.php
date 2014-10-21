<?php

namespace Backend;

use Controller, Request, Response, Sentry;

use Subbly\Subbly;

class BaseController extends Controller
{
    /**
     * The constructor.
     */
    public function __construct()
    {
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     *
     */
    public function processAuthentication($route, $request, $authRequired = true)
    {
        $user = null;

        try {
            $credentials = array(
                'email'    => Request::server('PHP_AUTH_USER'),
                'password' => Request::server('PHP_AUTH_PW'),
            );

            $user = Subbly::api('subbly.user')->authenticate($credentials, false);
        }
        catch (\Exception $e) {
        }

        // TODO Check if is admin

        if ($authRequired === true && !$user) {
            // return $this->jsonErrorResponse('Auth required! Something is wrong with your credentials.', 401, array(
            //     'WWW-Authenticate' => 'Basic realm="Subbly authentication"',
            // ));
        }
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array   $parameters
     * @return mixed
     */
    public function missingMethod($parameters = array())
    {
        return $this->jsonNotFoundResponse();
    }

    /**
     *
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
    protected function jsonErrorResponse($errorMessage, $statusCode = 400, array $httpHeaders = array())
    {
        return $this->jsonResponse(null, array(
            'status' => array(
                'code'    => $statusCode,
                'message' => $errorMessage,
            ),
        ), $httpHeaders);
    }

    /**
     *
     */
    protected function jsonNotFoundResponse($message='Not Found')
    {
        return $this->jsonResponse(null, array(
            'status' => array(
                'code'    => 404,
                'message' => $message,
            ),
        ));
    }
}
