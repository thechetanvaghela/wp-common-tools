<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/public
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Common_Tools_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-common-tools-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-common-tools-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Hide admin bar callback.
	 *
	 * @since    1.0.0
	 */
	public function wpct_remove_admin_bar_callback() {
		show_admin_bar(false);
	}

	/**
	 * upload mimes callback.
	 *
	 * @since    1.0.0
	 */
	public function wpct_upload_mimes_callback($mimes) {
		$wpct_mime_types = get_option('wpct-mime-types-enable');
	    if(!empty($wpct_mime_types))
	    {
		 	$mimes['svg'] = 'image/svg+xml';
		 	//$mimes['svg'] = 'image/svg';
		}
		else
		{
			unset($mimes['svg']);
		}
		 return $mimes;
	}

	/**
	 * upload mimes callback.
	 *
	 * @since    1.0.0
	 */
	public function wpct_admin_logo_callback()
	{ 
		# get saved data
	    $login_image_id = get_option('wpct-login-image');
     	$img_width = 250;
     	$img_height = 100;
	    if(!empty($login_image_id))
	    {
     		$img_data = wp_get_attachment_image_src($login_image_id);
     		if(isset($img_data[0]) && !empty($img_data[0]))
     		{
     			$login_img = $img_data[0];
     			$img_width = $img_data[1];
     			$img_height = $img_data[2];
			  	?> 
			    <style type="text/css"> 
			        body.login div#login h1 a {
			            background-image: url(<?php echo esc_url($login_img); ?>);  
			            width: <?php echo esc_attr($img_width); ?>px !important;
			            height: <?php echo esc_attr($img_height); ?>px !important;
			            background-size:100% !important;
			        } 
			    </style>
			    <?php
			}
     	}
	}

	/**
	 * login url callback.
	 *
	 * @since    1.0.0
	 */
	function wpct_login_headerurl_callback() {
	    return home_url();
	}

	/**
	 * added HTML to Footer 
	 *
	 * @since    1.0.0
	 */
	public function wpct_footer_callback() {
		
		# back to top
		$wpct_backtotop_enable = get_option('wpct-back-to-top-enable');
		if($wpct_backtotop_enable == 'yes')
		{
		    $wpct_btt_color = get_option('wpct-backtotop-color');
	    	$wpct_btt_color = !empty($wpct_btt_color) ? $wpct_btt_color : '#ff0000';
	    	$btt_style_bg = 'background-color:'.$wpct_btt_color.';'; ?>
			<!-- Back to TOP -->
			<a id="wpct-backtotop" style="<?php echo esc_attr($btt_style_bg); ?>"></a>
			<?php 
		}

		# Page Loader
	    $wpct_loader_enable = get_option('wpct-loader-enable');
	    $loader_enable = !empty($wpct_loader_enable) ? $wpct_loader_enable :'no';
	    if($loader_enable == 'yes')
	    {
		    $loader_image_id = get_option('wpct-loader-image');
	     	$loader_img = plugin_dir_url( __FILE__ ) . 'images/default-loading-icon.gif';
		    if(!empty($loader_image_id))
		    {
	     		$img_data = wp_get_attachment_image_src($loader_image_id);
	     		if(!empty($img_data[0]))
	     		{
	     			$loader_img = $img_data[0];
	     		}
	     	} ?>
			<!-- Loader -->
			<div id="wpct-preload">
				<img src="<?php echo esc_url($loader_img); ?>">
			</div>
			<?php 
		} 

		# progress bar
		$wpct_scroll_progress_bar_enable = get_option('wpct-progress-bar-enable');
		if($wpct_scroll_progress_bar_enable == 'yes')
		{
			# get position
			$wpct_scroll_progress_bar = get_option('wpct-scroll-progress-bar');
		    $wpct_scroll_progress_bar = !empty($wpct_scroll_progress_bar) ? $wpct_scroll_progress_bar : 'top';
		    # get color
		    $wpct_scroll_progress_color = get_option('wpct-scroll-progress-color');
	    	$wpct_scroll_progress_color = !empty($wpct_scroll_progress_color) ? $wpct_scroll_progress_color : '#ff0000';
	    	$top_style_bg = 'background-color:'.$wpct_scroll_progress_color.';';
	    	$circle_style = 'color:'.$wpct_scroll_progress_color.';box-shadow:1px 1px 8px 2px '.$wpct_scroll_progress_color.', -1px -1px 8px 2px '.$wpct_scroll_progress_color.'';

		    if($wpct_scroll_progress_bar == 'circle')
		    {	?>
				<!-- page scroll progress in circle with percentage -->
				<div id="wpct-scroll-percent" data-scrollPercentage>
					<div class="wpct-percentage">&nbsp;</div>
				</div>
		 		<div id="wpct-percentage-value" style="<?php echo esc_attr($circle_style); ?>"></div>
		 		<?php 
		 	}
		 	else if($wpct_scroll_progress_bar == 'top')
		 	{	?>
		 		<!-- page scroll progress in top -->
		 		<div class="wpct-progress-container wpct-fixed-top">
				  <span class="wpct-progress-bar" style="<?php echo esc_attr($top_style_bg); ?>"></span>
				</div>
				<?php
			}
		}
	}
}
