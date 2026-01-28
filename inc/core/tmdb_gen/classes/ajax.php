<?php
/*
* TMDB Generator - AJAX Handler
*/

class TMDBGen_Ajax extends TMDBGen_Helpers{
    
    public function __construct(){
        // Single import
        add_action('wp_ajax_tmdbgen_import_single', array($this, 'import_single'));
        
        // Bulk import
        add_action('wp_ajax_tmdbgen_import_bulk', array($this, 'import_bulk'));
        
        // Get popular content
        add_action('wp_ajax_tmdbgen_get_popular', array($this, 'get_popular'));
        
        // Search content
        add_action('wp_ajax_tmdbgen_search', array($this, 'search'));
        
        // Save server settings
        add_action('wp_ajax_tmdbgen_save_servers', array($this, 'save_servers'));
        
        // Import season
        add_action('wp_ajax_tmdbgen_import_season', array($this, 'import_season'));
        
        // Import episode
        add_action('wp_ajax_tmdbgen_import_episode', array($this, 'import_episode'));
    }
    
    /**
     * Import single content
     */
    public function import_single(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        if(!current_user_can('manage_options')){
            wp_send_json_error('Permission denied');
        }
        
        $tmdb_id = intval($_POST['tmdb_id']);
        $type = sanitize_text_field($_POST['type']);
        
        $importer = new TMDBGen_Importer();
        
        if($type == 'tvshow' || $type == 'tv'){
            $result = $importer->import_tvshow($tmdb_id);
        } else {
            $result = $importer->import_movie($tmdb_id);
        }
        
        if($result['success']){
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Bulk import
     */
    public function import_bulk(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        if(!current_user_can('manage_options')){
            wp_send_json_error('Permission denied');
        }
        
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : array();
        $type = sanitize_text_field($_POST['type']);
        
        if(empty($ids)){
            wp_send_json_error('No IDs provided');
        }
        
        $bulk = new TMDBGen_BulkImport();
        $results = $bulk->process_bulk($ids, $type);
        
        wp_send_json_success(array(
            'results' => $results,
            'total' => count($results)
        ));
    }
    
    /**
     * Get popular content
     */
    public function get_popular(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        $type = sanitize_text_field($_POST['type']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        
        $bulk = new TMDBGen_BulkImport();
        
        if($type == 'tvshow' || $type == 'tv'){
            $results = $bulk->get_popular_tvshows($page);
        } else {
            $results = $bulk->get_popular_movies($page);
        }
        
        wp_send_json_success($results);
    }
    
    /**
     * Search content
     */
    public function search(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        $query = sanitize_text_field($_POST['query']);
        $type = sanitize_text_field($_POST['type']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $genre = isset($_POST['genre']) ? intval($_POST['genre']) : 0;
        $year = isset($_POST['year']) ? intval($_POST['year']) : 0;
        
        $bulk = new TMDBGen_BulkImport();
        $results = $bulk->search_content($query, $type, $page, $genre, $year);
        
        wp_send_json_success($results);
    }
    
    /**
     * Save server settings
     */
    public function save_servers(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        if(!current_user_can('manage_options')){
            wp_send_json_error('Permission denied');
        }
        
        $enabled = isset($_POST['enabled_servers']) ? array_map('intval', $_POST['enabled_servers']) : array();
        
        $this->update_option('enabled_servers', $enabled);
        
        wp_send_json_success('Settings saved');
    }
    
    /**
     * Import season
     */
    public function import_season(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        if(!current_user_can('manage_options')){
            wp_send_json_error('Permission denied');
        }
        
        $tmdb_id = intval($_POST['tmdb_id']);
        $season_number = intval($_POST['season_number']);
        $tvshow_name = sanitize_text_field($_POST['tvshow_name']);
        
        $importer = new TMDBGen_Importer();
        $result = $importer->import_season($tmdb_id, $season_number, $tvshow_name);
        
        if($result['success']){
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Import episode
     */
    public function import_episode(){
        check_ajax_referer('tmdbgen_nonce', 'nonce');
        
        if(!current_user_can('manage_options')){
            wp_send_json_error('Permission denied');
        }
        
        $tmdb_id = intval($_POST['tmdb_id']);
        $season_number = intval($_POST['season_number']);
        $episode_number = intval($_POST['episode_number']);
        $tvshow_name = sanitize_text_field($_POST['tvshow_name']);
        
        $importer = new TMDBGen_Importer();
        $result = $importer->import_episode($tmdb_id, $season_number, $episode_number, $tvshow_name);
        
        if($result['success']){
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
}
