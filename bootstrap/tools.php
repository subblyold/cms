<?php

if ( ! function_exists('app_upload'))
{
  /**
   * Get the path to the upload folder.
   *
   * @param  string  $path
   * @return string
   */
  function app_upload($path = '')
  {
    return app()->make('path.upload').($path ? '/'.$path : $path);
  }
}

if ( ! function_exists('public_upload'))
{
  /**
   * Get the path to the upload folder.
   *
   * @param  string  $path
   * @return string
   */
  function public_upload($path = '')
  {
    return URL::to( 'upload' . $path );
    // return app()->make('path.upload').($path ? '/'.$path : $path);
  }
}

if ( ! function_exists('convertBytes'))
{
  // http://stackoverflow.com/a/13223499
  function convertBytes( $value ) 
  {
    if ( is_numeric( $value ) ) 
    {
      return $value;
    }
    else
    {
      $value_length = strlen($value);
      $qty          = substr( $value, 0, $value_length - 1 );
      $unit         = strtolower( substr( $value, $value_length - 1 ) );

      switch( $unit ) 
      {
        case 'k':
          $qty *= 1024;
          break;
        case 'm':
          $qty *= 1048576;
          break;
        case 'g':
          $qty *= 1073741824;
          break;
      }

      return $qty;
    }
  }
}
