<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Common_Tools
 * @subpackage Wp_Common_Tools/includes
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Common_Tools_Activator {

	public static function activate() {
        update_option('wpct-loader-enable', 'no');
        update_option('wpct-loader-image', '');
        update_option('wpct-progress-bar-enable','no');
        update_option('wpct-scroll-progress-bar', 'top');
        update_option('wpct-scroll-progress-color','#ff0000');
        update_option('wpct-back-to-top-enable','no');
        update_option('wpct-backtotop-color','#ff0000');
        update_option('wpct-adminbar-disable','no');
        update_option('wpct-login-image', '');
        update_option('wpct-mime-types-enable', '');
        update_option('wpct-uninstall-enable', 'no');
	}
}
