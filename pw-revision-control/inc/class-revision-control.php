<?php 
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

            $options = get_option( 'revision_control', array('number_of_revisions' => '') );

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