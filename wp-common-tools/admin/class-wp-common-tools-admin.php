<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/admin
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Common_Tools_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Common_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Common_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-common-tools-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Common_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Common_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-common-tools-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function wpct_admin_menu_callback() {
			# add menu page option to admin
			add_menu_page('Common Tools','Common Tools','manage_options','wp_common_tools_settings_page',array($this,'wp_common_tools_settings_page_callback'),'dashicons-plus-alt');
	}

	/**
	 * admin notice removable callback function
	 *
	 * @since    1.0.0
	 */
	public function wpct_add_removable_arg_callback($args)
	{
		array_push($args,'wpct-msg');
    	return $args;
	}

	/**
	 * admin notice callback function
	 *
	 * @since    1.0.0
	 */
	public function wpct_admin_notice_callback() {

		# admin notice for form submit
		if (!empty($_REQUEST['wpct-msg'])) 
		{
			if($_REQUEST['wpct-msg'] == 'success')
			{
				$message = 'Settings Saved.';
				$notice_class = 'updated notice-success';
			}
			else if($_REQUEST['wpct-msg'] == 'error')
			{
				$message = 'Sorry, your nonce did not verify';
				$notice_class = 'notice-error';
			}
			else if($_REQUEST['wpct-msg'] == 'error-media')
			{
				$message = 'Sorry, error in uploading media.';
				$notice_class = 'notice-error';
			}
			else
			{
				$message = 'Something went wrong!';
				$notice_class = 'notice-error';
			}
			# print admin notice
			printf('<div id="message" class="notice '.$notice_class.' is-dismissible"><p>' . __('%s.', 'wp-bulk-post-status-update') . '</p></div>', $message);
		}

	}


	/**
	 * callback menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function wp_common_tools_settings_page_save_callback() {
		# declare variables
		$status = "";
		$save_selected_mime = array();
		# check current user have manage options permission
		if ( current_user_can('manage_options') ) 
		{
			# check form submission
	      	if (isset($_POST['wp-common-tools-form-settings'])) 
	     	{
	        	# current page url
		        $pluginurl = admin_url('admin.php?page=wp_common_tools_settings_page');
	        	# check nonce
	        	if ( ! isset( $_POST['wpct_nonce'] ) || ! wp_verify_nonce( $_POST['wpct_nonce'], 'wpct_action_nonce' ) ) 
	        	{
	        		$redirect_url = add_query_arg('wpct-msg', 'error',$pluginurl);
		            wp_safe_redirect( $redirect_url);
		            exit();
				} 
				else 
				{	
					$status = 'success';
					# loader
					$enable_loader = isset($_POST['wpct-loader-enable']) ? sanitize_text_field($_POST['wpct-loader-enable']) : 'no';
	        		update_option('wpct-loader-enable', $enable_loader);
	        		if($enable_loader == 'yes')
	        		{
			        	if (isset($_FILES['wpct-loader-image'])) 
			        	{	
			        		if(!empty($_FILES['wpct-loader-image']))
			        		{
			        			if(empty($_FILES['wpct-loader-image']['error']))
			        			{
					        		require_once( ABSPATH . 'wp-admin/includes/image.php' );
									require_once( ABSPATH . 'wp-admin/includes/file.php' );
									require_once( ABSPATH . 'wp-admin/includes/media.php' );
					              	$attachment_id = media_handle_upload('wpct-loader-image', 0);
					              		
								    if (is_wp_error($attachment_id)) 
								    {
								        $status = 'media-error';
								    }
								    else 
								    {
								        update_option('wpct-loader-image', $attachment_id);
								    }
								}
								else
								{
									update_option('wpct-loader-image', '');
								}
							}
							else
							{
								update_option('wpct-loader-image', '');
							}
		            	}
		            	if(isset($_POST['wpct-loader-img-id']) && !empty($_POST['wpct-loader-img-id']))
		            	{
		            		update_option('wpct-loader-image', sanitize_text_field($_POST['wpct-loader-img-id']));
		            	}
	            	}

	            	# progress bar
	            	$enable_pb = isset($_POST['wpct-progress-bar-enable']) ? sanitize_text_field($_POST['wpct-progress-bar-enable']) : 'no';
	            	update_option('wpct-progress-bar-enable', sanitize_text_field($enable_pb));
	            	if(isset($_POST['wpct-scroll-progress-bar']))
	            	{
	            		update_option('wpct-scroll-progress-bar', sanitize_text_field($_POST['wpct-scroll-progress-bar']));
	            		$pb_color = isset($_POST['wpct-scroll-progress-color']) ? sanitize_text_field($_POST['wpct-scroll-progress-color']) : '';
	            		update_option('wpct-scroll-progress-color',$pb_color);

	            	}

	            	# back to top
	            	$enable_btt = isset($_POST['wpct-back-to-top-enable']) ? sanitize_text_field($_POST['wpct-back-to-top-enable']) : 'no';
	            	update_option('wpct-back-to-top-enable', sanitize_text_field($enable_btt));

	            	$btt_color = isset($_POST['wpct-backtotop-color']) ? sanitize_text_field($_POST['wpct-backtotop-color']) : '';
	            	update_option('wpct-backtotop-color',$btt_color);

	            	# Admin bar
	            	$adminbar_disable = isset($_POST['wpct-adminbar-disable']) ? sanitize_text_field($_POST['wpct-adminbar-disable']) : 'no';
	            	update_option('wpct-adminbar-disable',$adminbar_disable);

	            	# wp login page logo image
	            	if (isset($_FILES['wpct-login-image']) && !empty($_FILES['wpct-login-image'])) 
		        	{	
	        			if(empty($_FILES['wpct-login-image']['error']))
	        			{
			        		require_once( ABSPATH . 'wp-admin/includes/image.php' );
							require_once( ABSPATH . 'wp-admin/includes/file.php' );
							require_once( ABSPATH . 'wp-admin/includes/media.php' );
			              	$logn_attachment_id = media_handle_upload('wpct-login-image', 0);
			              		
						    if (is_wp_error($logn_attachment_id)) 
						    {
						        $status = 'media-error';
						    }
						    else 
						    {
						        update_option('wpct-login-image', $logn_attachment_id);
						    }
						}
						else
						{
							update_option('wpct-login-image', '');
						}
	            	}
	            	else
					{
						update_option('wpct-login-image', '');
					}
	            	if(isset($_POST['wpct-login-img-id']) && !empty($_POST['wpct-login-img-id']))
	            	{
	            		update_option('wpct-login-image', sanitize_text_field($_POST['wpct-login-img-id']));
	            	}

	            	# mime types
	            	if(isset($_POST['wpct-mime-types-enable']) && !empty($_POST['wpct-mime-types-enable']))
	            	{
	            		$selected_mime_value = array_map( 'sanitize_text_field', $_POST['wpct-mime-types-enable'] );
	                	$save_selected_mime = !empty($selected_mime_value) ? $selected_mime_value : array();
	            		update_option('wpct-mime-types-enable', $save_selected_mime);
	            	}
	            	else
	            	{
	            		update_option('wpct-mime-types-enable', '');
	            	}

	            	# uninstall
	            	$uninstall = isset($_POST['wpct-uninstall-enable']) ? sanitize_text_field($_POST['wpct-uninstall-enable']) : 'no';
	            	update_option('wpct-uninstall-enable', sanitize_text_field($uninstall));
		            	
	            	$redirect_url = add_query_arg('wpct-msg',$status,$pluginurl);
	                wp_safe_redirect( $redirect_url);
					exit();
			    }
			}
		}
	}

	/**
	 * callback menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function wp_common_tools_settings_page_callback() {
		# declare variables
		$loader_enabled = $loader_image_wrap = $loader_img_src = $loader_select_image_wrap = $progress_bar_enable = $backtop_enable = $backtotop_option_wrap = $adminbar_disable = $login_image_wrap = $login_img_src = $login_select_image_wrap = $uninstall_enable = "";

		# get saved data

		# loader
	    $wpct_loader_enable = get_option('wpct-loader-enable');
	    $loader_enable = !empty($wpct_loader_enable) ? $wpct_loader_enable :'';
	    $wpct_loader_image_id = get_option('wpct-loader-image');
	    $loader_image_id = !empty($wpct_loader_image_id) ? $wpct_loader_image_id :'';
	    $wpct_progress_bar_enable = get_option('wpct-progress-bar-enable');
	    $wpct_progress_bar_enable = !empty($wpct_progress_bar_enable) ? $wpct_progress_bar_enable : ''; 


	    # progress bar
	    $wpct_scroll_progress_bar = get_option('wpct-scroll-progress-bar');
	    $wpct_scroll_progress_bar = !empty($wpct_scroll_progress_bar) ? $wpct_scroll_progress_bar : 'top';
	    $wpct_scroll_progress_color = get_option('wpct-scroll-progress-color');
	    $wpct_scroll_progress_color = !empty($wpct_scroll_progress_color) ? $wpct_scroll_progress_color : '#ff0000';

	    # back to top
	    $wpct_backtop_enable = get_option('wpct-back-to-top-enable');
	    $wpct_backtop_enable = !empty($wpct_backtop_enable) ? $wpct_backtop_enable : '';
	    $wpct_btt_color = get_option('wpct-backtotop-color');
	    $wpct_btt_color = !empty($wpct_btt_color) ? $wpct_btt_color : '#ff0000';

	    # admin bar
	    $wpct_adminbar_disable = get_option('wpct-adminbar-disable');
	    $wpct_adminbar_disable = !empty($wpct_adminbar_disable) ? $wpct_adminbar_disable : '';
	    
	    # logn image
	    $wpct_login_image_id = get_option('wpct-login-image');
	    $login_image_id = !empty($wpct_login_image_id) ? $wpct_login_image_id :'';
	    
	    # mime
	    $wpct_mime_types = get_option('wpct-mime-types-enable');
	    $wpct_mime_types = !empty($wpct_mime_types) ? $wpct_mime_types : array();

	    # uninstall
	    $wpct_uninstall_enable = get_option('wpct-uninstall-enable');
	    $wpct_uninstall_enable = !empty($wpct_uninstall_enable) ? $wpct_uninstall_enable : '';
		?>
		<div class="wrap">
			<div id="wpct-setting-container">
				<div id="wpct-body">
					<div id="wpct-body-content">
						<div class="">
							<form method="post" enctype="multipart/form-data">
								<!-- https://codepen.io/themeswild/pen/qaMBGm -->
               					<div class="wpct-tab-container">
               						<div class="wpct-tab-container-heading">
               							<h2><?php esc_html_e('Common Tools','wp-bulk-post-status-update'); ?></h2>
               							<hr/>
               						</div>
									<div class="wpct-tab-menu">
								      <ul>
								         <li><a href="javascript:void(0);" id="tab1" class="wpct-tab-a wpct-active-a" data-id="tab1"><?php _e('Scroll Progress','wp-common-tools'); ?></a></li>
								         <li><a href="javascript:void(0);" id="tab2" class="wpct-tab-a" data-id="tab2"><?php _e('Back to Top','wp-common-tools'); ?></a></li>
								         <li><a href="javascript:void(0);" id="tab3" class="wpct-tab-a" data-id="tab3"><?php _e('Admin Options','wp-common-tools'); ?></a></li>
								         <li><a href="javascript:void(0);" id="tab4" class="wpct-tab-a" data-id="tab4"><?php _e('Mime Types','wp-common-tools'); ?></a></li>
								         <li><a href="javascript:void(0);" id="tab5" class="wpct-tab-a" data-id="tab5"><?php _e('Page Loader','wp-common-tools'); ?></a></li>
								         <li><a href="javascript:void(0);" id="tab6" class="wpct-tab-a" data-id="tab6"><?php _e('Uninstall','wp-common-tools'); ?></a></li>
								      </ul>
								   </div><!--end of tab-menu-->

								   <div  class="wpct-tab wpct-tab-active" data-id="tab1">
								        <h2><?php _e('Scroll Progress','wp-common-tools'); ?></h2>
								        <div class="wpct-scroll-progress-bar-wrap">
								          	<?php 
								          	if($wpct_progress_bar_enable == 'yes')
								         	{
								         		$progress_bar_enable = 'checked';
								         	}
								         	else
								         	{
								         		$progress_option_wrap = 'display:none;';
								         	}  ?>
								          	<div class="wpct-scroll-progress-bar-enable">
								          		<input type="checkbox" name="wpct-progress-bar-enable" id="wpct-progress-bar-enable" value="yes" <?php echo $progress_bar_enable; ?>>
								          		<label for="wpct-progress-bar-enable"><?php _e('Enable Scroll Progress Bar','wp-common-tools'); ?></label>
								          	</div>
								          	<div class="wpct-scroll-progress-bar-option-wrap" style="<?php echo $progress_option_wrap; ?>">
								          		<div class="wpct-scroll-progress-bar-options">
									          		<?php 
									          		$top_checked =  ($wpct_scroll_progress_bar == 'top') ? 'checked' : '';
									          		$circle_checked =  ($wpct_scroll_progress_bar == 'circle') ? 'checked' : '';
									          		 ?>
									          		 <br/>
									          		<input type="radio" name="wpct-scroll-progress-bar" value="top" id="spb-top" <?php echo $top_checked; ?>>
									          		<label for="spb-top"><?php _e('Top','wp-common-tools'); ?></label><br/>
									          		<input type="radio" name="wpct-scroll-progress-bar" value="circle" id="spb-circle" <?php echo $circle_checked; ?>>
									          		<label for="spb-circle"><?php _e('Circle','wp-common-tools'); ?></label>
								          		</div>
								          		<div class="wpct-scroll-progress-bar-color-wrap">
								          			<br/>
								          			<input type="color" name="wpct-scroll-progress-color" id="spb-color" value="<?php echo esc_attr($wpct_scroll_progress_color); ?>">
								          			<label for="spb-color"><?php _e('Select Color for progress','wp-common-tools'); ?></label>
								          		</div>
								          	</div>
								        </div>
								        <p><?php _e('The scroll progress of the page is displayed when the user scrolls down.','wp-common-tools'); ?></p>
								    </div><!--end of tab one--> 

								    <div class="wpct-tab " data-id="tab2">
								      	<h2><?php _e('Back to Top','wp-common-tools'); ?></h2>
								         <div class="wpct-back-to-top-wrap">
								          	<?php 
								          	if($wpct_backtop_enable == 'yes')
								         	{
								         		$backtop_enable = 'checked';
								         	}
								         	else
								         	{
								         		$backtotop_option_wrap = 'display:none;';
								         	}  ?>
								          	<div class="wpct-back-to-top-enable">
								          		<input type="checkbox" name="wpct-back-to-top-enable" id="wpct-back-to-top-enable" value="yes" <?php echo $backtop_enable; ?>><label for="wpct-back-to-top-enable"><?php _e('Enable Back to Top','wp-common-tools'); ?></label>
								          	</div>
							          		<div class="wpct-back-to-top-option-wrap" style="<?php echo $backtotop_option_wrap; ?>">
							          			<br/>
							          			<input type="color" name="wpct-backtotop-color" id="btt-color" value="<?php echo esc_attr($wpct_btt_color); ?>">
							          			<label for="btt-color"><?php _e('Select Color for button','wp-common-tools'); ?></label>
							          		</div>
							      		</div>
								        <p><?php _e('The Back to top button will be added to the footer.','wp-common-tools'); ?></p>     
								   </div><!--end of tab two--> 

								   <div class="wpct-tab " data-id="tab3">
								         <div class="wpct-admin-options-wrap">
								         	<h2><?php _e('Admin Bar Option','wp-common-tools'); ?></h2>
								          	<?php 
								          	if($wpct_adminbar_disable == 'yes')
								         	{
								         		$adminbar_disable = 'checked';
								         	}
								         	?>
								          	<div class="wpct-adminbar-enable">
								          		<input type="checkbox" name="wpct-adminbar-disable" id="wpct-adminbar-disable" value="yes" <?php echo $adminbar_disable; ?>><label for="wpct-adminbar-disable"><?php _e('Hide Admin Bar','wp-common-tools'); ?></label>
								          	</div>
								          	<p><?php _e('Hide admin bar from Frontend when user logged in.','wp-common-tools'); ?></p>

								          	<h2><?php _e('Change WP-Admin Login page logo','wp-common-tools'); ?></h2>
								          	<?php 
								          	if(!empty($login_image_id))
									        { 	
									         	$login_img = wp_get_attachment_image_src($login_image_id);
									         	if(!empty($login_img[0]))
									         	{
									         		$login_img_src = $login_img[0];
									         		$login_select_image_wrap = 'display:none;';
									         	}
									        }  
										    ?>
								          	<div class="wpct-login-image-wrap">
									         	<div class="wpct-login-img-select-wrap" style="<?php echo $login_select_image_wrap; ?>">
									         		<input type="file" name="wpct-login-image" class="wpct-login-image" accept="image/png, image/gif, image/jpeg">
									         	</div>
								         		<?php
								         		
								         		if(!empty($login_img_src))
								         		{	?>
								         			<br/>
								         			<div class="wpct-login-img-preview-wrap">
								         				<img src="<?php echo $login_img_src; ?>" class="wpct-login-img-preview" width="100" height="100">
								         				<input type="hidden" name="wpct-login-img-id" value="<?php echo $login_image_id; ?>">
								         				<br/>
								         				<a href="javascript:void(0)" class="wpct-remove-btn remove-login-img"><?php _e('Remove','wp-common-tools'); ?></a>
								         			</div>
								         			<?php
								         		}
								         		?>
								         	</div>
								         	<p><?php _e('Admin logo will be changed if the logo is selected.','wp-common-tools'); ?></p>
							      		</div>
								   </div><!--end of tab three--> 

								   <div class="wpct-tab " data-id="tab4">
								      	<h2><?php _e('Mime Types','wp-common-tools'); ?></h2>
								         <div class="wpct-mime-types-wrap">
								          	<?php 
								          	$total_mimes = get_allowed_mime_types();
								          	# add svg to existing 
								          	$wpct_mimes = array();	
								          	$wpct_mimes['svg'] = 'image/svg';
								         	?>
								          	<div class="wpct-mime-types-enables">
								          		<?php
								          		$c = 1; 
								          		foreach ($wpct_mimes as $mime_name => $mime_data) 
								          		{
								          			$mime_checked = (in_array($mime_name , $wpct_mime_types) || in_array($mime_name , $total_mimes)) ? 'checked' : '';
								          			?>
								          			<input type="checkbox" name="wpct-mime-types-enable[]" id="wpct-mime-types-enable-<?php echo $c; ?>" value="<?php echo esc_attr($mime_name); ?>" <?php echo $mime_checked; ?>><label for="wpct-mime-types-enable-<?php echo $c; ?>"><?php echo esc_attr($mime_name); ?></label><br/>
								          			<?php
								          			$c++; 
								          		} ?>
								          	</div>
							          		<div class="wpct-back-to-top-option-wrap" >
							          			<br/>
							          			
							          		</div>
							      		</div>
								        <p><?php _e('Allow SVG image upload to media.','wp-common-tools'); ?></p>     
								   </div><!--end of tab four--> 

								   <div class="wpct-tab" data-id="tab5">
								        <h2><?php _e('Page Loader','wp-common-tools'); ?></h2>
								        <div class="wpct-page-loader-wrap">
									        <?php 
									        if(!empty($loader_image_id))
									        { 	
									         	$loader_img = wp_get_attachment_image_src($loader_image_id);
									         	if(!empty($loader_img[0]))
									         	{
									         		$loader_img_src = $loader_img[0];
									         		$loader_select_image_wrap = 'display:none;';
									         	}
									        } 
									        ?>
							         		<div class="wpct-loader-option">
									         	<?php
									         	if($loader_enable == 'yes')
									         	{
									         		$loader_enabled = 'checked';
									         	}
									         	else
									         	{
									         		$loader_image_wrap = 'display:none;';
									         	} 
									         	?>
							         			<input type="checkbox" name="wpct-loader-enable" id="wpct-loader-enable" value="yes" <?php echo $loader_enabled; ?>>
							         			<label for="wpct-loader-enable"><?php _e('Enable Loader','wp-common-tools'); ?></label>
							         		</div>
								         	<div class="wpct-loader-image-wrap" style="<?php echo $loader_image_wrap; ?>">
									         	<div class="wpct-loader-img-select-wrap" style="<?php echo $loader_select_image_wrap; ?>">
									         		<input type="file" name="wpct-loader-image" class="wpct-loader-image" accept="image/png, image/gif, image/jpeg">
									         	</div>
								         		<?php 
								         		if(!empty($loader_img_src))
								         		{	?>
								         			<br/>
								         			<div class="wpct-loader-img-preview-wrap">
								         				<img src="<?php echo $loader_img_src; ?>" class="wpct-loader-img-preview" width="100" height="100">
								         				<input type="hidden" name="wpct-loader-img-id" value="<?php echo $loader_image_id; ?>">
								         				<br/>
								         				<a href="javascript:void(0)" class="wpct-remove-btn remove-loader-img"><?php _e('Remove','wp-common-tools'); ?></a>
								         			</div>
								         			<?php
								         		}
								         		?>
								         	</div>
								        </div>
								        <p><?php _e('Display a loader until the page is fully loaded.','wp-common-tools'); ?></p>
								   </div><!--end of tab five--> 

								   <div class="wpct-tab " data-id="tab6">
								      	<h2><?php _e('Remove Plugin data on Uninstall','wp-common-tools'); ?></h2>
								         <div class="wpct-uninstall-wrap">
								          	<?php 
								          	if($wpct_uninstall_enable == 'yes')
								         	{
								         		$uninstall_enable = 'checked';
								         	}
								         	?>
								          	<div class="wpct-uninstall-enable">
								          		<input type="checkbox" name="wpct-uninstall-enable" id="wpct-uninstall-enable" value="yes" <?php echo $uninstall_enable; ?>><label for="wpct-uninstall-enable"><?php _e('Remove Data?','wp-common-tools'); ?></label>
								          	</div>
							      		</div>
								        <p><?php _e('Plugin data will be removed on Uninstall','wp-common-tools'); ?></p>     
								   </div><!--end of tab six--> 

								   	<div class="wpct-save-button-wrap">
									    <?php wp_nonce_field( 'wpct_action_nonce', 'wpct_nonce' ); ?>
					                    <?php  submit_button( 'Save Settings', 'primary', 'wp-common-tools-form-settings'  ); ?>
					                </div>
								</div><!--end of container-->
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}
}
