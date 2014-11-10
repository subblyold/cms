<?php

class TplController extends Controller 
{
  protected function setup()
  {
    $basePath = 'views/backend/';
    $path = app_path( $basePath );
    
    $files = File::allFiles( $path );
    $arr = array();

    foreach( $files as $file )
    {
      $filename    = str_replace( $path, '',  str_replace('.tpl', '', (string) $file ) );
      $tmp         = array_filter( explode( '/', $filename ) );
      $content     = File::get( (string) $file, '' );
      $html        = trim( preg_replace('/\s+/', ' ',preg_replace('/<!-- (.*)-->/Uis', '', $content) ) );
      $dotpath     = implode( '.', $tmp );

      array_set( $arr, $dotpath, $html );
    }

    $contents = 'var TPL = ' . json_encode( $arr );

    $response = Response::make($contents, 200 );
    $response->header('Content-Type', 'application/javascript');
    return $response;
  }

}
