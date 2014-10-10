<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Routing;
 
$routes = new Routing\RouteCollection();

$params = array(
    'id'          => false
  , 'slug'        => false
  , 'page'        => 1
  , 'category'    => false
  , 'subcategory' => false
);

$routesMap = array(
    '/'                                             => 'home'
  , '/catalog/{page?}/{category?}/{subcategory?}'   => 'catalog'
  , '/products/{id}/{slug}'                         => 'product'
  // , '/products/{page}/{category}'                   => 'catalog'
  // , '/products'                                     => 'catalog'
  , '/about-marmouze'                               => 'marmouze222'
);

/**
 * Get the optional parameters for the route.
 *
 * @return array
 */
// $extractOptionalParameters = function( $uri ) use( $params )
// {
//   preg_match_all('/\{(\w+?)\?\}/', $uri, $matches);

//   if( isset( $matches[1] ) )
//   {
//     // print_r( $matches );
//     $ret = [];

//     foreach( $matches[1] as $key  )
//     {
//       if( isset( $params[ $key ] ) )
//         $ret[ $key ] = $params[ $key ];
//     }

//     return $ret;
//   }

//   return [];
// };

echo '<pre>';

foreach( $routesMap as $uri => $page )
{
  preg_match_all('/\{(.*?)\}/', $uri, $matches);

  $optionals = array_map(function($m) { return trim($m, '?'); }, $matches[1]);
  // $optionals = $extractOptionalParameters( $uri );

  $uri = preg_replace('/\{(\w+?)\?\}/', '{$1}', $uri);
  // echo '<br>';

  // preg_match_all('/\{(\w+?)\?\}/', $uri, $matches);

  $routes->add( $page , new Routing\Route( $uri, $optionals ) );

  var_dump( $optionals );
  // // print_r( $matches );
  // echo '<br><hr><br>';
}


// var_dump( $routes );
// exit;

return $routes;
