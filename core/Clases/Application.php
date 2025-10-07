<?php

namespace Clases;
date_default_timezone_set('America/Lima');
class Application
{
  public $empresa;
  public $ht;
  public $dt;
  public $us;
  public $pw;
  public $ct;
  protected static $instance;
  

  protected function __construct()
  {
    $this->empresa = "";
  }
  public static function getInstance(): Application
  {
    if (is_null(self::$instance)) {
      self::$instance = new Application();
    }
    return self::$instance;
  }


  /**
   * Get the value of ht
   */
  public function getHt()
  {
    return $this->ht;
  }

  /**
   * Set the value of ht
   *
   * @return  self
   */
  public function setHt($ht)
  {
    $this->ht = $ht;

    return $this;
  }

  /**
   * Get the value of dt
   */
  public function getDt()
  {
    return $this->dt;
  }

  /**
   * Set the value of dt
   *
   * @return  self
   */
  public function setDt($dt)
  {
    $this->dt = $dt;

    return $this;
  }

  /**
   * Get the value of us
   */
  public function getUs()
  {
    return $this->us;
  }

  /**
   * Set the value of us
   *
   * @return  self
   */
  public function setUs($us)
  {
    $this->us = $us;

    return $this;
  }

  /**
   * Get the value of pw
   */
  public function getPw()
  {
    return $this->pw;
  }

  /**
   * Set the value of pw
   *
   * @return  self
   */
  public function setPw($pw)
  {
    $this->pw = $pw;

    return $this;
  }
}
