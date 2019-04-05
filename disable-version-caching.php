<?php
/**
 * Plugin Name: Disable Version Caching
 * Description: Updates the assets version of all CSS and JS files. Shows the latest changes on the site without asking the client to clear browser cache.
 * Version: 1.0.0
 * Author: Aman Khanakia
 * Author URI: https://wordpress.org/support/users/mrkhanakia/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: disable-version-caching
 * Domain Path: /lang/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'disable_version_caching_plugin_actions' ) ) {
    /**
     * Add settings to plugin links.
     * @param $actions
     * @return mixed
     */
    function disable_version_caching_plugin_actions( $actions )
    {
        array_unshift( $actions, "<a href=\"" . menu_page_url( 'disable-version-caching', false ) . "\">" . esc_html__( "Settings" ) . "</a>" );
        return $actions;
    }
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'disable_version_caching_plugin_actions', 10, 1 );
}

if ( ! function_exists( 'disable_version_caching_load_textdomain' ) ) {
    /**
     * Set languages directory.
     */
    function disable_version_caching_load_textdomain()
    {
        load_plugin_textdomain( 'disable-version-caching', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    }
    add_action( 'plugins_loaded', 'disable_version_caching_load_textdomain' );
}

if ( ! function_exists( 'disable_version_caching' ) ) {
    /**
     * Changes the version of CSS and JS files.
     * Disables loading Disable_Version_Caching class if this function is used before setup theme.
     *
     * @param array $args
     */
    function disable_version_caching( $args = array() ) {
        if ( ! class_exists( 'Disable_Version_Caching_Function' ) ) {
            include_once 'includes/class-disable-version-caching-function.php';
        }

        Disable_Version_Caching_Function::instance( $args );
    }
}

if ( ! function_exists( 'maybe_load_class_disable_version_caching' ) ) {
    /**
     * Load Disable_Version_Caching class if the function disable_version_caching is not used before setup theme.
     */
    function maybe_load_class_disable_version_caching()
    {
        if ( ! class_exists( 'Disable_Version_Caching' ) && ! class_exists( 'Disable_Version_Caching_Function' ) ) {
            include_once 'includes/class-disable-version-caching.php';
        }
    }

    add_action( 'after_setup_theme', 'maybe_load_class_disable_version_caching' );
}

if ( is_admin() ) {
    include_once 'includes/admin/class-disable-version-caching-admin-settings.php';
}