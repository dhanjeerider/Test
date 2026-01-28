<?php
/*
* TMDB Generator - Admin Interface
*/

class TMDBGen_Admin extends TMDBGen_Helpers{
    
    public function __construct(){
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_menu(){
        add_menu_page(
            'TMDB Generator',
            'TMDB Generator',
            'manage_options',
            'tmdb-generator',
            array($this, 'admin_page'),
            'dashicons-download',
            30
        );
        
        add_submenu_page(
            'tmdb-generator',
            'Bulk Import',
            'Bulk Import',
            'manage_options',
            'tmdb-generator',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'tmdb-generator',
            'Server Settings',
            'Server Settings',
            'manage_options',
            'tmdb-server-settings',
            array($this, 'server_settings_page')
        );
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook){
        if(strpos($hook, 'tmdb') === false){
            return;
        }
        
        wp_enqueue_style('tmdbgen-admin', TMDBGEN_URI . '/assets/admin.css', array(), TMDBGEN_VERSION);
        wp_enqueue_script('tmdbgen-admin', TMDBGEN_URI . '/assets/admin.js', array('jquery'), TMDBGEN_VERSION, true);
        
        wp_localize_script('tmdbgen-admin', 'tmdbgen', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tmdbgen_nonce')
        ));
    }
    
    /**
     * Main admin page
     */
    public function admin_page(){
        require_once TMDBGEN_DIR . 'tpl/bulk_import.php';
    }
    
    /**
     * Server settings page
     */
    public function server_settings_page(){
        require_once TMDBGEN_DIR . 'tpl/server_settings.php';
    }
}
