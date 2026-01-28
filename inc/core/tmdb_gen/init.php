<?php
/*
* ----------------------------------------------------
* @author: TMDB Generator
* @version: 1.0.0
* @description: Complete TMDB Import System with Bulk Import
* ----------------------------------------------------
*/

if(!class_exists('TMDBGenerator')){
    class TMDBGenerator{
        
        public function __construct(){
            $this->init();
        }
        
        public function init(){
            // Define Constants
            define('TMDBGEN_VERSION', '1.0.0');
            define('TMDBGEN_API_KEY', 'fed86956458f19fb45cdd382b6e6de83');
            define('TMDBGEN_API_URL', 'https://api.themoviedb.org/3');
            define('TMDBGEN_IMG_URL', 'https://image.tmdb.org/t/p/');
            define('TMDBGEN_URI', get_template_directory_uri().'/inc/core/tmdb_gen');
            define('TMDBGEN_DIR', get_template_directory().'/inc/core/tmdb_gen/');
            
            // Load Classes
            require_once TMDBGEN_DIR . 'classes/helpers.php';
            require_once TMDBGEN_DIR . 'classes/servers.php';
            require_once TMDBGEN_DIR . 'classes/importer.php';
            require_once TMDBGEN_DIR . 'classes/bulk_import.php';
            require_once TMDBGEN_DIR . 'classes/ajax.php';
            require_once TMDBGEN_DIR . 'classes/admin.php';
            
            // Initialize
            new TMDBGen_Servers();
            new TMDBGen_Ajax();
            new TMDBGen_Admin();
        }
    }
    
    new TMDBGenerator();
}
