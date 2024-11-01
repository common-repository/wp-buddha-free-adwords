<?php
/**
 * Plugin Name: WP Buddha Free Adwords Plugin
 * Version: 1.0.0
 * Description: Write your adwords ads directly from your wordpress site or blog & Build UTM for your campaigns directly from your wordpress site or blog.
 * Author: WP Buddha
 * Author URI: https://wp-buddha.com
 */
 // Create a helper function for easy SDK access.

if ( ! function_exists( 'fre_fs' ) ) {
    // Create a helper function for easy SDK access.
    function fre_fs() {
        global $fre_fs;

        if ( ! isset( $fre_fs ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_3646_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3646_MULTISITE', true );
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $fre_fs = fs_dynamic_init( array(
                'id'                  => '3646',
                'slug'                => 'freeadwords',
                'type'                => 'plugin',
                'public_key'          => 'pk_7392c988354fd0493e88064f586cf',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'support'        => false,
                ),
            ) );
        }

        return $fre_fs;
    }

    // Init Freemius.
    fre_fs();
    // Signal that SDK was initiated.
    do_action( 'fre_fs_loaded' );
}
defined('ABSPATH') or die('No script kiddies please!');

define('BUDDHA_ADS_FILE', __FILE__);
define('BUDDHA_ADS_BASE_DIR',plugin_dir_path(__FILE__));
define('BUDDHA_ADS_BASE_URL',plugin_dir_url(__FILE__));

require_once __DIR__.'/src/ads.main.php';

new wpbuddha_publishad();