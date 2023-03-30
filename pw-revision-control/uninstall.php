<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ):
    exit();
endif;
  
// Remove DB information
delete_option( 'revision_control' );
