<?php
/**
 * Custom Theme Enhancements
 * Load custom fixes for mobile performance and download accordion
 */

// Enqueue custom CSS and JS
function dooplay_custom_fixes() {
    // Custom CSS for mobile fixes and download accordion
    wp_enqueue_style(
        'dooplay-custom-fixes',
        get_template_directory_uri() . '/assets/css/custom-fixes.css',
        array(),
        '1.0.0'
    );
    
    // Download accordion script
    wp_enqueue_script(
        'download-accordion',
        get_template_directory_uri() . '/assets/js/download-accordion.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'dooplay_custom_fixes', 100);

/**
 * Add Font Awesome if not already loaded
 */
function dooplay_load_fontawesome() {
    if (!wp_style_is('font-awesome', 'enqueued')) {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
            array(),
            '5.15.4'
        );
    }
}
add_action('wp_enqueue_scripts', 'dooplay_load_fontawesome', 99);
