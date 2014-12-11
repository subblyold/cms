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
