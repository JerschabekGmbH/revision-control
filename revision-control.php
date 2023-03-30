<?php
/*
  Plugin Name: Revsion Control
  Plugin URI: https://www.jerschabek-gmbh.de
  Version: 0.1.0
  Author: Torsten Jerschabek
  Author URI: https://www.jerschabek-gmbh.de
  Description: Allows to control, how many revisions are saved
  Text Domain: revision-control
  Domain Path: /languages
  License: GPL
 */


//Setup Plugin

if ( ! class_exists( 'revision_control' ) ) :
  class revision_control {
    /**
     * Setup Class
     * 
     * @since 0.1.0
     * 
     * 
     */

    public function __construct() {
      add_action( 'plugins_loaded', array( $this, 'revision_control' ) ); 
    }

    public function revision_control() {
      if ( !defined( 'WP_POST_REVISIONS' ) ) :
        define( 'WP_POST_REVISIONS', 0 );
      endif;
    }

  }
endif;

// Executes the class
return new revision_control();
?>