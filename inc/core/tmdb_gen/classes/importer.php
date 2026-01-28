<?php
/*
* TMDB Generator - Main Importer
*/

class TMDBGen_Importer extends TMDBGen_Helpers{
    
    private $servers;
    
    public function __construct(){
        $this->servers = new TMDBGen_Servers();
    }
    
    /**
     * Import Movie
     */
    public function import_movie($tmdb_id){
        $start_time = microtime(TRUE);
        
        // Check if exists
        $existing = $this->post_exists($tmdb_id, 'movies');
        if($existing){
            return array(
                'success' => false,
                'message' => 'Movie already exists',
                'post_id' => $existing
            );
        }
        
        // Fetch data from TMDB
        $movie = $this->fetch_movie_data($tmdb_id);
        
        if(!$movie){
            return array(
                'success' => false,
                'message' => 'Failed to fetch movie data'
            );
        }
        
        // Create post
        $post_id = $this->create_movie_post($movie);
        
        if(!$post_id){
            return array(
                'success' => false,
                'message' => 'Failed to create post'
            );
        }
        
        // Add metadata
        $this->add_movie_metadata($post_id, $movie);
        
        // Add taxonomies
        $this->add_taxonomies($post_id, $movie, 'movie');
        
        // Upload poster
        if(!empty($movie['poster_path'])){
            $this->upload_image($movie['poster_path'], $post_id);
        }
        
        // Add servers
        $this->servers->add_servers_to_post($post_id, $tmdb_id, $movie['imdb_id']);
        
        return array(
            'success' => true,
            'post_id' => $post_id,
            'title' => $movie['title'],
            'poster' => $movie['poster_path'],
            'time' => $this->time_exec($start_time),
            'link' => get_permalink($post_id)
        );
    }
    
    /**
     * Import TV Show
     */
    public function import_tvshow($tmdb_id){
        $start_time = microtime(TRUE);
        
        $existing = $this->post_exists($tmdb_id, 'tvshows');
        if($existing){
            return array(
                'success' => false,
                'message' => 'TV Show already exists',
                'post_id' => $existing
            );
        }
        
        $tvshow = $this->fetch_tvshow_data($tmdb_id);
        
        if(!$tvshow){
            return array(
                'success' => false,
                'message' => 'Failed to fetch TV show data'
            );
        }
        
        $post_id = $this->create_tvshow_post($tvshow);
        
        if(!$post_id){
            return array(
                'success' => false,
                'message' => 'Failed to create post'
            );
        }
        
        $this->add_tvshow_metadata($post_id, $tvshow);
        $this->add_taxonomies($post_id, $tvshow, 'tv');
        
        if(!empty($tvshow['poster_path'])){
            $this->upload_image($tvshow['poster_path'], $post_id);
        }
        
        $this->servers->add_servers_to_post($post_id, $tmdb_id, $tvshow['imdb_id']);
        
        return array(
            'success' => true,
            'post_id' => $post_id,
            'title' => $tvshow['name'],
            'poster' => $tvshow['poster_path'],
            'time' => $this->time_exec($start_time),
            'link' => get_permalink($post_id)
        );
    }
    
    /**
     * Fetch movie data from TMDB
     */
    private function fetch_movie_data($tmdb_id){
        $url = TMDBGEN_API_URL . '/movie/' . $tmdb_id;
        
        $movie = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'append_to_response' => 'credits,videos,images,external_ids'
        ));
        
        if(!$movie || isset($movie['status_code'])){
            return false;
        }
        
        return $movie;
    }
    
    /**
     * Fetch TV show data from TMDB
     */
    private function fetch_tvshow_data($tmdb_id){
        $url = TMDBGEN_API_URL . '/tv/' . $tmdb_id;
        
        $tvshow = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'append_to_response' => 'credits,videos,images,external_ids'
        ));
        
        if(!$tvshow || isset($tvshow['status_code'])){
            return false;
        }
        
        return $tvshow;
    }
    
    /**
     * Create movie post
     */
    private function create_movie_post($movie){
        $post_data = array(
            'post_type' => 'movies',
            'post_title' => $this->text_clean($movie['title']),
            'post_content' => $movie['overview'],
            'post_status' => 'publish',
            'post_date' => !empty($movie['release_date']) ? $movie['release_date'] : current_time('mysql')
        );
        
        return wp_insert_post($post_data);
    }
    
    /**
     * Create TV show post
     */
    private function create_tvshow_post($tvshow){
        $post_data = array(
            'post_type' => 'tvshows',
            'post_title' => $this->text_clean($tvshow['name']),
            'post_content' => $tvshow['overview'],
            'post_status' => 'publish',
            'post_date' => !empty($tvshow['first_air_date']) ? $tvshow['first_air_date'] : current_time('mysql')
        );
        
        return wp_insert_post($post_data);
    }
    
    /**
     * Add movie metadata
     */
    private function add_movie_metadata($post_id, $movie){
        $metadata = array(
            'idtmdb' => $movie['id'],
            'ids' => isset($movie['external_ids']['imdb_id']) ? $movie['external_ids']['imdb_id'] : '',
            'original_title' => $movie['original_title'],
            'dt_poster' => $movie['poster_path'],
            'dt_backdrop' => $movie['backdrop_path'],
            'tagline' => $movie['tagline'],
            'release_date' => $movie['release_date'],
            'runtime' => $movie['runtime'],
            'vote_average' => $movie['vote_average'],
            'vote_count' => $movie['vote_count'],
            'imdbRating' => $movie['vote_average'],
            'imdbVotes' => $movie['vote_count'],
            'Country' => isset($movie['production_countries'][0]['name']) ? $movie['production_countries'][0]['name'] : ''
        );
        
        // Add cast & crew
        if(isset($movie['credits']['cast'])){
            $cast = array();
            foreach(array_slice($movie['credits']['cast'], 0, 10) as $person){
                $cast[] = '[' . ($person['profile_path'] ?? 'null') . ';' . $person['name'] . ',' . $person['character'] . ']';
            }
            $metadata['dt_cast'] = implode('', $cast);
        }
        
        if(isset($movie['credits']['crew'])){
            $directors = array();
            foreach($movie['credits']['crew'] as $person){
                if($person['job'] == 'Director'){
                    $directors[] = '[' . ($person['profile_path'] ?? 'null') . ';' . $person['name'] . ']';
                }
            }
            $metadata['dt_dir'] = implode('', $directors);
        }
        
        // Add trailer
        if(isset($movie['videos']['results'])){
            foreach($movie['videos']['results'] as $video){
                if($video['type'] == 'Trailer' && $video['site'] == 'YouTube'){
                    $metadata['youtube_id'] = '[' . $video['key'] . ']';
                    break;
                }
            }
        }
        
        // Add gallery images
        if(isset($movie['images']['backdrops'])){
            $images = array();
            foreach(array_slice($movie['images']['backdrops'], 0, 10) as $img){
                $images[] = $img['file_path'];
            }
            $metadata['imagenes'] = implode("\n", $images);
        }
        
        foreach($metadata as $key => $value){
            if(!empty($value)){
                update_post_meta($post_id, $key, $value);
            }
        }
    }
    
    /**
     * Add TV show metadata
     */
    private function add_tvshow_metadata($post_id, $tvshow){
        $metadata = array(
            'idtmdb' => $tvshow['id'],
            'ids' => isset($tvshow['external_ids']['imdb_id']) ? $tvshow['external_ids']['imdb_id'] : '',
            'original_name' => $tvshow['original_name'],
            'dt_poster' => $tvshow['poster_path'],
            'dt_backdrop' => $tvshow['backdrop_path'],
            'tagline' => $tvshow['tagline'] ?? '',
            'first_air_date' => $tvshow['first_air_date'],
            'last_air_date' => $tvshow['last_air_date'] ?? '',
            'number_of_seasons' => $tvshow['number_of_seasons'],
            'number_of_episodes' => $tvshow['number_of_episodes'],
            'episode_run_time' => isset($tvshow['episode_run_time'][0]) ? $tvshow['episode_run_time'][0] : '',
            'imdbRating' => $tvshow['vote_average'],
            'imdbVotes' => $tvshow['vote_count']
        );
        
        // Add cast & creator
        if(isset($tvshow['credits']['cast'])){
            $cast = array();
            foreach(array_slice($tvshow['credits']['cast'], 0, 10) as $person){
                $cast[] = '[' . ($person['profile_path'] ?? 'null') . ';' . $person['name'] . ',' . $person['character'] . ']';
            }
            $metadata['dt_cast'] = implode('', $cast);
        }
        
        if(isset($tvshow['created_by'])){
            $creators = array();
            foreach($tvshow['created_by'] as $person){
                $creators[] = '[' . ($person['profile_path'] ?? 'null') . ';' . $person['name'] . ']';
            }
            $metadata['dt_creator'] = implode('', $creators);
        }
        
        // Trailer
        if(isset($tvshow['videos']['results'])){
            foreach($tvshow['videos']['results'] as $video){
                if($video['type'] == 'Trailer' && $video['site'] == 'YouTube'){
                    $metadata['youtube_id'] = '[' . $video['key'] . ']';
                    break;
                }
            }
        }
        
        // Gallery
        if(isset($tvshow['images']['backdrops'])){
            $images = array();
            foreach(array_slice($tvshow['images']['backdrops'], 0, 10) as $img){
                $images[] = $img['file_path'];
            }
            $metadata['imagenes'] = implode("\n", $images);
        }
        
        foreach($metadata as $key => $value){
            if(!empty($value)){
                update_post_meta($post_id, $key, $value);
            }
        }
    }
    
    /**
     * Add taxonomies
     */
    private function add_taxonomies($post_id, $data, $type){
        // Genres
        if(isset($data['genres'])){
            $genres = array();
            foreach($data['genres'] as $genre){
                $genres[] = $genre['name'];
            }
            wp_set_object_terms($post_id, $genres, 'genres');
        }
        
        // Year
        $year = '';
        if($type == 'movie' && isset($data['release_date'])){
            $year = substr($data['release_date'], 0, 4);
        } elseif($type == 'tv' && isset($data['first_air_date'])){
            $year = substr($data['first_air_date'], 0, 4);
        }
        
        if($year){
            wp_set_post_terms($post_id, $year, 'dtyear');
        }
        
        // Cast
        if(isset($data['credits']['cast'])){
            $cast_names = array();
            foreach(array_slice($data['credits']['cast'], 0, 10) as $person){
                $cast_names[] = $person['name'];
            }
            wp_set_post_terms($post_id, implode(',', $cast_names), 'dtcast');
        }
        
        // Director/Creator
        if($type == 'movie' && isset($data['credits']['crew'])){
            $directors = array();
            foreach($data['credits']['crew'] as $person){
                if($person['job'] == 'Director'){
                    $directors[] = $person['name'];
                }
            }
            wp_set_post_terms($post_id, implode(',', $directors), 'dtdirector');
        } elseif($type == 'tv' && isset($data['created_by'])){
            $creators = array();
            foreach($data['created_by'] as $person){
                $creators[] = $person['name'];
            }
            wp_set_post_terms($post_id, implode(',', $creators), 'dtcreator');
        }
    }
    
    /**
     * Import Season
     */
    public function import_season($tmdb_id, $season_number, $tvshow_name){
        if(empty($tmdb_id) || empty($season_number) || empty($tvshow_name)){
            return array(
                'success' => false,
                'message' => 'Missing required data'
            );
        }
        
        // Check if exists
        $existing = $this->season_exists($tmdb_id, $season_number);
        if($existing){
            return array(
                'success' => false,
                'message' => 'Season already exists',
                'post_id' => $existing
            );
        }
        
        // Fetch season data from TMDB
        $url = TMDBGEN_API_URL . '/tv/' . $tmdb_id . '/season/' . $season_number;
        
        $season = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'append_to_response' => 'images'
        ));
        
        if(!$season || isset($season['status_code'])){
            return array(
                'success' => false,
                'message' => 'Failed to fetch season data'
            );
        }
        
        // Create season post
        $post_data = array(
            'post_type' => 'seasons',
            'post_title' => $this->text_clean($tvshow_name . ': Season ' . $season_number),
            'post_content' => isset($season['overview']) ? $season['overview'] : '',
            'post_status' => 'publish'
        );
        
        $post_id = wp_insert_post($post_data);
        
        if(!$post_id){
            return array(
                'success' => false,
                'message' => 'Failed to create season post'
            );
        }
        
        // Add metadata
        $metadata = array(
            'ids' => $tmdb_id,
            'temporada' => $season['season_number'],
            'serie' => $tvshow_name,
            'air_date' => isset($season['air_date']) ? $season['air_date'] : '',
            'dt_poster' => isset($season['poster_path']) ? $season['poster_path'] : ''
        );
        
        foreach($metadata as $key => $value){
            if(!empty($value)){
                update_post_meta($post_id, $key, $value);
            }
        }
        
        // Upload poster
        if(!empty($season['poster_path'])){
            $this->upload_image($season['poster_path'], $post_id);
        }
        
        return array(
            'success' => true,
            'post_id' => $post_id,
            'season_number' => $season_number
        );
    }
    
    /**
     * Import Episode
     */
    public function import_episode($tmdb_id, $season_number, $episode_number, $tvshow_name){
        if(empty($tmdb_id) || empty($season_number) || empty($episode_number) || empty($tvshow_name)){
            return array(
                'success' => false,
                'message' => 'Missing required data'
            );
        }
        
        // Check if exists
        $existing = $this->episode_exists($tmdb_id, $season_number, $episode_number);
        if($existing){
            return array(
                'success' => false,
                'message' => 'Episode already exists',
                'post_id' => $existing
            );
        }
        
        // Fetch episode data from TMDB
        $url = TMDBGEN_API_URL . '/tv/' . $tmdb_id . '/season/' . $season_number . '/episode/' . $episode_number;
        
        $episode = $this->remote_json($url, array(
            'api_key' => TMDBGEN_API_KEY,
            'language' => 'en-US',
            'append_to_response' => 'images'
        ));
        
        if(!$episode || isset($episode['status_code'])){
            return array(
                'success' => false,
                'message' => 'Failed to fetch episode data'
            );
        }
        
        // Create episode post
        $post_data = array(
            'post_type' => 'episodes',
            'post_title' => $this->text_clean($tvshow_name . ': ' . $season_number . 'x' . $episode_number),
            'post_content' => isset($episode['overview']) ? $episode['overview'] : '',
            'post_status' => 'publish'
        );
        
        $post_id = wp_insert_post($post_data);
        
        if(!$post_id){
            return array(
                'success' => false,
                'message' => 'Failed to create episode post'
            );
        }
        
        // Compose images from stills
        $images = '';
        if(isset($episode['images']['stills'])){
            $image_arr = array();
            foreach(array_slice($episode['images']['stills'], 0, 10) as $img){
                $image_arr[] = $img['file_path'];
            }
            $images = implode("\n", $image_arr);
        }
        
        // Add metadata
        $metadata = array(
            'ids' => $tmdb_id,
            'temporada' => $season_number,
            'episodio' => $episode_number,
            'serie' => $tvshow_name,
            'episode_name' => isset($episode['name']) ? $episode['name'] : '',
            'air_date' => isset($episode['air_date']) ? $episode['air_date'] : '',
            'runtime' => isset($episode['runtime']) ? $episode['runtime'] : '',
            'imagenes' => $images,
            'dt_backdrop' => isset($episode['still_path']) ? $episode['still_path'] : ''
        );
        
        foreach($metadata as $key => $value){
            if(!empty($value)){
                update_post_meta($post_id, $key, $value);
            }
        }
        
        // Upload episode still as thumbnail
        if(!empty($episode['still_path'])){
            $this->upload_image($episode['still_path'], $post_id);
        }
        
        return array(
            'success' => true,
            'post_id' => $post_id,
            'episode_number' => $episode_number,
            'season_number' => $season_number,
            'runtime' => isset($episode['runtime']) ? $episode['runtime'] : 0
        );
    }
    
    /**
     * Check if season exists
     */
    private function season_exists($tmdb_id, $season_number){
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT p.ID FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id
            INNER JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id
            WHERE p.post_type = 'seasons'
            AND m1.meta_key = 'ids'
            AND m1.meta_value = %s
            AND m2.meta_key = 'temporada'
            AND m2.meta_value = %s
            LIMIT 1",
            $tmdb_id,
            $season_number
        );
        
        return $wpdb->get_var($query);
    }
    
    /**
     * Check if episode exists
     */
    private function episode_exists($tmdb_id, $season_number, $episode_number){
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT p.ID FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id
            INNER JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id
            INNER JOIN {$wpdb->postmeta} m3 ON p.ID = m3.post_id
            WHERE p.post_type = 'episodes'
            AND m1.meta_key = 'ids'
            AND m1.meta_value = %s
            AND m2.meta_key = 'temporada'
            AND m2.meta_value = %s
            AND m3.meta_key = 'episodio'
            AND m3.meta_value = %s
            LIMIT 1",
            $tmdb_id,
            $season_number,
            $episode_number
        );
        
        return $wpdb->get_var($query);
    }
}
