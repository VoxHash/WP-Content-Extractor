<?php
/**
 * Plugin Name: WP Content Extractor
 * Plugin URI: https://github.com/VoxHash/WPContent-extractor
 * Description: Extracts content from a specified URL and publishes posts in WordPress. Perfect for content creators who need to aggregate and republish content from multiple sources.
 * Version: 1.0.0
 * Author: VoxHash
 * Author URI: https://github.com/VoxHash
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: wp-content-extractor
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Network: false
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
include_once plugin_dir_path( __FILE__ ) . 'functions.php';

// Register settings page
function ce_register_settings_page() {
    add_options_page(
        'Content Extractor Settings',
        'Content Extractor',
        'manage_options',
        'content-extractor',
        'ce_settings_page'
    );
}
add_action( 'admin_menu', 'ce_register_settings_page' );

// Render settings page
function ce_settings_page() {
    ?>
    <div class="wrap">
        <h1>Content Extractor Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'ce_settings_group' );
            do_settings_sections( 'content-extractor' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Sanitize settings callback
function ce_sanitize_settings( $input ) {
    $sanitized = array();
    if ( isset( $input['ce_url'] ) ) {
        $sanitized['ce_url'] = esc_url_raw( $input['ce_url'] );
    }
    return $sanitized;
}

// Register and define settings
function ce_register_settings() {
    register_setting(
        'ce_settings_group',
        'ce_settings',
        'ce_sanitize_settings'
    );

    add_settings_section(
        'ce_settings_section',
        'Settings',
        null,
        'content-extractor'
    );

    add_settings_field(
        'ce_url',
        'Source URL',
        'ce_url_callback',
        'content-extractor',
        'ce_settings_section'
    );
}
add_action( 'admin_init', 'ce_register_settings' );

// URL callback
function ce_url_callback() {
    $options = get_option( 'ce_settings' );
    echo '<input type="text" name="ce_settings[ce_url]" value="' . esc_attr( $options['ce_url'] ?? '' ) . '" />';
}

// Add custom intervals to the cron schedule
function ce_add_custom_cron_intervals( $schedules ) {
    $schedules['every_30_minutes'] = array(
        'interval' => 1800, // 30 minutes in seconds
        'display'  => __( 'Every 30 Minutes' ),
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'ce_add_custom_cron_intervals' );

// Hook the cron event to the extract content function
add_action( 'ce_daily_event', 'ce_extract_content' );

// Schedule events
if ( ! wp_next_scheduled( 'ce_daily_event' ) ) {
    wp_schedule_event( time(), 'every_30_minutes', 'ce_daily_event' );
}

// Clear scheduled event on plugin deactivation
function ce_clear_scheduled_event() {
    $timestamp = wp_next_scheduled( 'ce_daily_event' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'ce_daily_event' );
    }
}
register_deactivation_hook( __FILE__, 'ce_clear_scheduled_event' );

// Extract content function
function ce_extract_content() {
    $options = get_option( 'ce_settings' );
    $url = $options['ce_url'] ?? '';

    if ( empty( $url ) ) {
        return;
    }

    $current_page = get_option( 'ce_current_page', 1 );
    $posts_per_page = 5; // Number of posts to process per batch

    $response = wp_remote_get( $url . '?page=' . $current_page );
    if ( is_wp_error( $response ) ) {
        ce_log_error( 'Failed to fetch content from ' . $url );
        return;
    }

    $body = wp_remote_retrieve_body( $response );
    $posts = ce_parse_and_publish_content( $body );

    // Update current page
    if ( ! empty( $posts ) && count( $posts ) > $posts_per_page ) {
        update_option( 'ce_current_page', $current_page + 1 );
    } else {
        // All done, reset the page
        delete_option( 'ce_current_page' );
    }
}

function ce_log_error( $message ) {
    if ( WP_DEBUG ) {
        error_log( $message );
    }
}
?>
