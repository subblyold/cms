<?php

namespace Subbly\PostControllers;

use App;
use Config;
use Input;
use Redirect;
use Request;
use Validator;
use View;

class Action 
  extends \Controller
{
  // define return type
  private $isAjax = false;

  // data to display on the page
  public $viewData = array(
      'view' => null
  );

  public function __construct()
  {
    $this->isAjax = Request::ajax();

    $this->afterFilter('@filterTeardown');
  }

  /**
   * Generic form validation
   */
  public function formValidator( $rules )
  {
    $validator = Validator::make( Input::all(), $rules );

    if( $validator->fails() ) 
    {
      return Redirect::back()
              ->withErrors( $validator )
              ->withInput( Input::except('password') );
    }
  }

   /**
    * Format an error Response
    *
    * @param string $errorMessage  The error message to send
    * @param int    $statusCode    The HTTP status code
    * @param array  $httpHeaders   Http headers to insert into the Response
    *
    * @return Response
    */
  protected function errorResponse($errorMessage, $statusCode = 400, array $httpHeaders = array())
  {
    $statusTexts = \Symfony\Component\HttpFoundation\Response::$statusTexts;

    if( $this->isAjax )
    {
      $response = Response::make(json_encode(array(
          'error' => (string) $errorMessage,
      )), $statusCode);

      return $response->header('Content-Type', 'application/json');
    }
    else
    {
      return Redirect::back()
              ->withErrors( $errorMessage )
              ->withInput( Input::except('password') );
    }
  }


  /**
   * Filter the incoming requests.
   */
  public function filterTeardown( $route, $request, $response )
  {
  //   // cast the views object to a string
  //   if( $this->isAjax && !is_null( $this->viewData['view'] ) )
  //     $this->viewData['view'] = $this->viewData['view']->render();

  //   // if not ajax, we build html
  //   if( !$this->isAjax )
  //     return $response->setContent( View::make( $this->template, $this->viewData ) );

  //   // else, we return a JSON of the data

  //   $response->header('Content-Type', 'json');

  //   return $response->setContent( $this->viewData );

    return $response;

  }
}
