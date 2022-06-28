<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/includes
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Common_Tools {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Common_Tools_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_COMMON_TOOLS_VERSION' ) ) {
			$this->version = WP_COMMON_TOOLS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-common-tools';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Common_Tools_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Common_Tools_i18n. Defines internationalization functionality.
	 * - Wp_Common_Tools_Admin. Defines all hooks for the admin area.
	 * - Wp_Common_Tools_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-common-tools-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-common-tools-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-common-tools-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-common-tools-public.php';

		$this->loader = new Wp_Common_Tools_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Common_Tools_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Common_Tools_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Common_Tools_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		# init action
		$this->loader->add_action( 'init', $plugin_admin, 'wp_common_tools_settings_page_save_callback' );
		
		# admin menu register
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wpct_admin_menu_callback' );

		# admin notice
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpct_admin_notice_callback' );
		# add removable query arg
		$this->loader->add_filter( 'removable_query_args', $plugin_admin, 'wpct_add_removable_arg_callback', 10, 3  );

		$this->loader->add_action( 'wp_ajax_wpct_get_our_plugins', $plugin_admin, 'wp_ajax_wpct_get_our_plugins_callback' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Common_Tools_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		# Footer
		$this->loader->add_action( 'wp_footer', $plugin_public, 'wpct_footer_callback' );

		# admin bar
	    $wpct_adminbar_disable = get_option('wpct-adminbar-disable');
	    $wpct_adminbar_disable = !empty($wpct_adminbar_disable) ? $wpct_adminbar_disable : '';
	    if( $wpct_adminbar_disable == 'yes')
	    {
			$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpct_remove_admin_bar_callback' );
	    }

		#support memes
	    $wpct_mime_types = get_option('wpct-mime-types-enable');
	    if(!empty($wpct_mime_types))
	    {
			$this->loader->add_action( 'upload_mimes', $plugin_public, 'wpct_upload_mimes_callback' );
		}

		# wp admin page 
		$login_image_id = get_option('wpct-login-image');
	    if(!empty($login_image_id))
	    {
     		$img_data = wp_get_attachment_image_src($login_image_id);
     		if(isset($img_data[0]) && !empty($img_data[0]))
     		{
				# admin logo
				$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'wpct_admin_logo_callback' );
				# login url
				$this->loader->add_action( 'login_headerurl', $plugin_public, 'wpct_login_headerurl_callback' );

			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Common_Tools_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
