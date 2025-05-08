<?php

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}


class Badanie {

  private static $_instance;

  public static function instance() {
    if ( self::$_instance === null ) {
      self::$_instance = new self();

      self::$_instance->includes();

      self::$_instance->dashboard = new Badania_Dashboard();
      self::$_instance->front     = new Badania_Front();
    }

    return self::$_instance;
  }


  public function __construct() {
  }


  private function includes() {
    include_once( plugin_dir_path( __FILE__ ) . 'includes/dashboard.php' );
    include_once( plugin_dir_path( __FILE__ ) . 'includes/front.php' );
  }

  // public function value_clear($value) {
  // 	$unwanted_array = array('ą'=>'a', 'ę'=>'e', 'ć'=>'c', 'ś'=>'s', 'ź'=>'z', 'ż'=>'z', 'ł' => 'l', 'ó' => 'o');

  // 	$value_slug = str_replace(' ', '-', strtolower($value));
  // 	$value_slug = strtr( $value_slug, $unwanted_array );

  // 	return $value_slug;
  // }

}


function Badanie() {
  static $instance;

  if ( $instance === null || !( $instance instanceof Badanie ) ) {
    $instance = Badanie::instance();
  }

  return $instance;
}

$badanie = Badanie();

