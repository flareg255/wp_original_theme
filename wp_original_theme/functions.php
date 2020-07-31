<?php

require_once( dirname(__FILE__) . '/classes/initial.php' );
require_once( dirname(__FILE__) . '/classes/theme_func.php' );

if ( ! defined( 'ABSPATH' ) ) exit;

$inital = new Initial();
$inital->init_func();

global $theme_func;
$theme_func = new Theme_func();

?>