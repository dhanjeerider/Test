<?php
/*
* TMDB Generator - Bulk Import Handler
*/

class TMDBGen_BulkImport extends TMDBGen_Helpers{
    
    private $importer;
    
    public function __construct(){
        $this->importer = new TMDBGen_Importer();
    }
    
    /**
     * Process bulk import
     */
    public function process_bulk($ids, $type = 'movie'){
        $results = array();
        
        foreach($ids as $id){
            if($type == 'tvshow' || $type == 'tv'){
                $result = $this->importer->import_tvshow($id);
            } else {
                $result = $this->importer->import_movie($id);
            }
            
            $results[] = $result;
            
            // Small delay to prevent API rate limiting
            usleep(500000); // 0.5 second delay
        }
        
        return $results;
    }
    
    /**
     * Get popular movies for bulk import
     */
    public function get_popular_movies($page = 1){
        $url = TMDBGEN_API_URL . '/movie/popular';
        
        $data = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'page' => $page
        ));
        
        if(!$data || !isset($data['results'])){
            return array();
        }
        
        return $data['results'];
    }
    
    /**
     * Get popular TV shows for bulk import
     */
    public function get_popular_tvshows($page = 1){
        $url = TMDBGEN_API_URL . '/tv/popular';
        
        $data = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'page' => $page
        ));
        
        if(!$data || !isset($data['results'])){
            return array();
        }
        
        return $data['results'];
    }
    
    /**
     * Search content with filters
     */
    public function search_content($query, $type = 'movie', $page = 1, $genre = 0, $year = 0){
        $url = TMDBGEN_API_URL . '/search/' . $type;
        
        $params = array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'query' => $query,
            'page' => $page
        );
        
        // Add filters
        if($genre > 0){
            $params['with_genres'] = $genre;
        }
        
        if($year > 0){
            if($type == 'tv'){
                $params['first_air_date_year'] = $year;
            } else {
                $params['primary_release_year'] = $year;
            }
        }
        
        $data = $this->remote_json($url, $params);
        
        if(!$data || !isset($data['results'])){
            return array();
        }
        
        return $data['results'];
    }
}
