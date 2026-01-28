<?php
/*
* ----------------------------------------------------
* @author: Doothemes
* @author URI: https://doothemes.com/
* @copyright: (c) 2021 Doothemes. All rights reserved
* ----------------------------------------------------
* @since 2.5.5
*/

# Theme options
define('DOO_THEME_DOWNLOAD_MOD', true);
define('DOO_THEME_PLAYER_MOD',   true);
define('DOO_THEME_DBMOVIES',     true);
define('DOO_THEME_USER_MOD',     true);
define('DOO_THEME_VIEWS_COUNT',  true);
define('DOO_THEME_RELATED',      true);
define('DOO_THEME_SOCIAL_SHARE', true);
define('DOO_THEME_CACHE',        true);
define('DOO_THEME_PLAYERSERNAM', true);
define('DOO_THEME_JSCOMPRESS',   true);
define('DOO_THEME_TOTAL_POSTC',  true);
define('DOO_THEME_LAZYLOAD',     false);
# Repository data
define('DOO_COM','Doothemes');
define('DOO_VERSION','2.5.5');
define('Bes_VERSION','2.5.5.1'); // Bescraper version for wp_update only 23/06/2021
define('DOO_VERSION_DB','2.8');
define('DOO_ITEM_ID','154');
define('DOO_PHP_REQUIRE','7.1');
define('DOO_THEME','Dooplay');
define('DOO_THEME_SLUG','dooplay');
define('DOO_SERVER','https://cdn.bescraper.top/api');
define('DOO_GICO','https://s2.googleusercontent.com/s2/favicons?domain=');

# Configure Here date format #
define('DOO_TIME','M. d, Y');  // More Info >>> https://www.php.net/manual/function.date.php
##############################

# Define Rating data
define('DOO_MAIN_RATING','_starstruck_avg');
define('DOO_MAIN_VOTOS','_starstruck_total');
# Define Options key
define('DOO_OPTIONS','_dooplay_options');
define('DOO_CUSTOMIZE', '_dooplay_customize');
# Define template directory
define('DOO_URI',get_template_directory_uri());
define('DOO_DIR',get_template_directory());

# Translations
load_theme_textdomain('dooplay', DOO_DIR.'/lang/');

# Load Application
require get_parent_theme_file_path('/inc/doo_init.php');

# Load Custom Enhancements (Mobile Fixes & Download Accordion)
require get_parent_theme_file_path('/inc/custom_enhancements.php');

/* Custom functions
========================================================
*/

// Custom Video Player Function
if(!function_exists('doo_custom_video_player')) {
    function doo_custom_video_player($post_id, $post_type = 'movie') {
        // Get custom video URL from meta
        $custom_video = get_post_meta($post_id, 'custom_video_url', true);
        
        // Get TMDB data for fallback
        $imdb_id = get_post_meta($post_id, 'ids', true);
        $tmdb_id = get_post_meta($post_id, 'idtmdb', true);
        
        if (!empty($custom_video)) {
            // Custom video provided - determine type and display
            $custom_video = trim($custom_video);
            
            // Check if it's an MP4 file
            if (preg_match('/\.(mp4|m3u8|webm)$/i', $custom_video)) {
                // Display HTML5 video player for MP4/M3U8/WebM
                echo '<div class="custom-video-player-wrapper">';
                echo '<video id="custom-video-player" class="custom-html5-video" controls playsinline>';
                echo '<source src="' . esc_url($custom_video) . '" type="video/mp4">';
                echo __d('Your browser does not support the video tag.');
                echo '</video>';
                echo '</div>';
                
                // Add basic video player styling
                echo '<style>
                .custom-video-player-wrapper {
                    position: relative;
                    width: 100%;
                    padding-top: 56.25%;
                    background: #000;
                    margin-bottom: 20px;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .custom-html5-video {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }
                </style>';
                
            } else {
                // Display iframe for other types
                echo '<div class="custom-video-player-wrapper">';
                echo '<iframe class="custom-iframe-player" src="' . esc_url($custom_video) . '" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>';
                echo '</div>';
                
                echo '<style>
                .custom-video-player-wrapper {
                    position: relative;
                    width: 100%;
                    padding-top: 56.25%;
                    background: #000;
                    margin-bottom: 20px;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .custom-iframe-player {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }
                </style>';
            }
            
            // Add fallback button to TMDB servers
            if (!empty($tmdb_id) || !empty($imdb_id)) {
                echo '<div class="tmdb-fallback-button" style="text-align: center; margin: 20px 0;">';
                echo '<button onclick="document.getElementById(\'dktczn-player\').style.display=\'block\';this.parentElement.style.display=\'none\';" class="tmdb-server-btn" style="background: #e50914; color: #fff; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s;">';
                echo '<i class="fas fa-play-circle"></i> ' . __d('Play via TMDB Auto Embed Servers');
                echo '</button>';
                echo '</div>';
                
                // JavaScript to hide the TMDB player initially when custom video exists
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var tmdbPlayer = document.getElementById("dktczn-player");
                    if (tmdbPlayer) {
                        tmdbPlayer.style.display = "none";
                    }
                });
                </script>';
            }
        }
        // If no custom video, TMDB player will show automatically (no notice needed)
    }
}

