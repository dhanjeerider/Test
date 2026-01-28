<?php
/*
* TMDB Generator - Helper Functions
*/

class TMDBGen_Helpers{
    
    /**
     * Get option
     */
    public function get_option($key, $default = ''){
        $options = get_option('tmdbgen_settings', array());
        return isset($options[$key]) ? $options[$key] : $default;
    }
    
    /**
     * Update option
     */
    public function update_option($key, $value){
        $options = get_option('tmdbgen_settings', array());
        $options[$key] = $value;
        update_option('tmdbgen_settings', $options);
    }
    
    /**
     * Remote JSON request
     */
    public function remote_json($url, $args = array()){
        $url = add_query_arg($args, $url);
        
        $response = wp_remote_get($url, array(
            'timeout' => 30,
            'sslverify' => false
        ));
        
        if(is_wp_error($response)){
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
    
    /**
     * Check if post exists
     */
    public function post_exists($tmdb_id, $post_type = 'movies'){
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = 'idtmdb' 
            AND meta_value = %s 
            LIMIT 1",
            $tmdb_id
        );
        
        $result = $wpdb->get_var($query);
        
        if($result){
            $post_type_check = get_post_type($result);
            return ($post_type_check == $post_type) ? $result : false;
        }
        
        return false;
    }
    
    /**
     * Upload image
     */
    public function upload_image($image_url, $post_id){
        if(empty($image_url)){
            return false;
        }
        
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $image_url = str_replace('https://image.tmdb.org/t/p/', TMDBGEN_IMG_URL.'w780', $image_url);
        
        $tmp = download_url($image_url);
        
        if(is_wp_error($tmp)){
            return false;
        }
        
        $file_array = array(
            'name' => basename($image_url) . '.jpg',
            'tmp_name' => $tmp
        );
        
        $id = media_handle_sideload($file_array, $post_id);
        
        if(is_wp_error($id)){
            @unlink($file_array['tmp_name']);
            return false;
        }
        
        set_post_thumbnail($post_id, $id);
        
        return $id;
    }
    
    /**
     * Get IMDB ID from TMDB
     */
    public function get_imdb_from_tmdb($tmdb_id, $type = 'movie'){
        $url = TMDBGEN_API_URL . '/' . $type . '/' . $tmdb_id;
        
        $data = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'append_to_response' => 'external_ids'
        ));
        
        if($data && isset($data['external_ids']['imdb_id'])){
            return $data['external_ids']['imdb_id'];
        }
        
        return '';
    }
    
    /**
     * Time execution
     */
    public function time_exec($start_time){
        $end_time = microtime(TRUE);
        $time_taken = round($end_time - $start_time, 2);
        return $time_taken . 's';
    }
    
    /**
     * Text cleaner
     */
    public function text_clean($text){
        return sanitize_text_field(trim($text));
    }
}
