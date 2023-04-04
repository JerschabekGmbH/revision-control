<?php
/*
  Plugin Name: PW Revsion Control
  Plugin URI: https://www.jerschabek-gmbh.de
  Version: 0.1.0
  Author: Torsten Jerschabek
  Author URI: https://www.passau-webdesign.com
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
      add_action( 'admin_menu', array( $this, 'pw_add_admin_menu' ) );
      add_action( 'admin_init', array( $this, 'pw_settings_init' ) );
      add_action( 'plugins_loaded', array( $this, 'pw_settings_execute' ) );
    }


    public function pw_add_admin_menu() {
      add_options_page( 
        'Revision Control', 
        'Revision Control', 
        'manage_options', 
        'pw_revision_control', 
        'pw_options_page' 
      );
    }

    public function pw_settings_init() {
      register_setting( 'pluginPage', 'revision_control' );

      add_settings_section(
        'pw_pluginPage_section', 
        __( 'Limits the number of saved post revisions', 'pw' ), 
        'pw_settings_section_callback', 
        'pluginPage'
      );
    
      add_settings_field( 
        'number_of_revisions', 
        __( 'Number of Revisions', 'pw' ), 
        'number_of_revisions_render', 
        'pluginPage', 
        'pw_pluginPage_section' 
      ); 


      function number_of_revisions_render() { 

        $options = get_option( 'revision_control' );
        ?>
        <input type='text' name='revision_control[number_of_revisions]' value='<?php echo $options['number_of_revisions']; ?>'>
        <?php
      }
      
      
      function pw_settings_section_callback() { 
        
      }
      
      
      function pw_options_page() { 
      
          ?>
          <form action='options.php' method='post'>
            <h2>Revision Control</h2>
            <?php
            settings_fields( 'pluginPage' );
            do_settings_sections( 'pluginPage' );
            submit_button();
            ?>
          </form>
          <?php
      
      }


    } 

    public function pw_settings_execute() {
      $options = get_option( 'revision_control' );
      
      if (isset($options['number_of_revisions'])) :
        $number_of_revisions = $options['number_of_revisions'];

        if ( !defined( 'WP_POST_REVISIONS' ) ) :
          define( 'WP_POST_REVISIONS', $number_of_revisions );
        endif;
      endif;
    }

  }
endif;

// Executes the class
return new revision_control();


// Check Update
if( ! class_exists( 'PWUpdateChecker' ) ) {

	class PWUpdateChecker{

		public $plugin_slug;
		public $version;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {

			$this->plugin_slug = plugin_basename( __DIR__ );
			$this->version = '1.0';
			$this->cache_key = 'pw_custom_upd';
			$this->cache_allowed = false;

			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
			add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );

		}

		public function request(){

			$remote = get_transient( $this->cache_key );

			if( false === $remote || ! $this->cache_allowed ) {

				$remote = wp_remote_get(
					'https://rudrastyh.com/wp-content/uploads/updater/info.json',
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );

			return $remote;

		}


		function info( $res, $action, $args ) {

			// print_r( $action );
			// print_r( $args );

			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return $res;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return $res;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return $res;
			}

			$res = new stdClass();

			$res->name = $remote->name;
			$res->slug = $remote->slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = $remote->requires_php;
			$res->last_updated = $remote->last_updated;

			$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog
			);

			if( ! empty( $remote->banners ) ) {
				$res->banners = array(
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			}

			return $res;

		}

		public function update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();

			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$res = new stdClass();
				$res->slug = $this->plugin_slug;
				$res->plugin = plugin_basename( __FILE__ ); // misha-update-plugin/misha-update-plugin.php
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;

				$transient->response[ $res->plugin ] = $res;

	    }

			return $transient;

		}

		public function purge( $upgrader, $options ){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
				delete_transient( $this->cache_key );
			}

		}


	}

	new PWUpdateChecker();

}



?>