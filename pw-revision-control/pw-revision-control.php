<?php
/*
  Plugin Name: PW Revsion Control
  Plugin URI: https://www.jerschabek-gmbh.de
  Version: 0.2.0
  Author: Torsten Jerschabek
  Author URI: https://www.passau-webdesign.com
  Description: Allows to control, how many revisions are saved
  Text Domain: revision-control
  Domain Path: /languages
  License: GPL
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Constants */
define('PLUGIN_DIR', plugin_basename( __DIR__ ) );
define('PLUGIN_FILE', plugin_basename( __FILE__ ) );


include( plugin_dir_path( __FILE__ ) . 'inc/class-update-checker.php');
include( plugin_dir_path( __FILE__ ) . 'inc/class-revision-control.php');