<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Common_Tools
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$wpct_remove_on_uninstall = get_option('wpct_remove_on_uninstall');
if(!empty($wpct_remove_on_uninstall) && $wpct_remove_on_uninstall == 'yes')
{
    # delete options on uninstall
    delete_option('wpct-loader-enable');
    delete_option('wpct-loader-image');
    delete_option('wpct-progress-bar-enable');
    delete_option('wpct-scroll-progress-bar');
    delete_option('wpct-scroll-progress-color');
    delete_option('wpct-back-to-top-enable');
    delete_option('wpct-backtotop-color');
    delete_option('wpct-adminbar-disable');
    delete_option('wpct-login-image');
    delete_option('wpct-mime-types-enable');
    delete_option('wpct-uninstall-enable');
}
