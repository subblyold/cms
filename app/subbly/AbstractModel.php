<?php

namespace Subbly;

abstract class AbstractModel 
{
  // Model original value
  private $row;

  /**
   * Magic get method to allow getting class properties but still having them protected
   * to disallow writing.
   *
   * @return  mixed
   */
  public function __get( $name )
  {
    return isset( $this->row[ $name ] ) ? $this->row[ $name ] : null;
  }

  /**
   * Magic set method to allow getting class properties but still having them protected
   * to disallow writing.
   *
   * @return  mixed
   */
  public function __set( $name , $value )
  {
    if( isset( $this->row[ $name ] ) )
    {
      $this->row[ $name ] = $value;
    }
    else
    {
      throw new Exception( 'attribute "' . $name . '" not found in ' . __CLASS__ );
    }
  }


  public abstract function find( $attributes );

  public abstract function findAll( $attributes = null );

  public abstract function save();

  public abstract function load( $row );

}
