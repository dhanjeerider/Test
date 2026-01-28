<?php
/*
* TMDB Generator - Server Manager
*/

class TMDBGen_Servers extends TMDBGen_Helpers{
    
    /**
     * Get all default servers
     */
    public function get_default_servers(){
        return array(
            array(
                'name' => 'Vidify',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidify.top/embed/movie/{tmdb_id}',
                'url_tv' => 'https://vidify.top/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidJoy',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidjoy.pro/embed/movie/{tmdb_id}',
                'url_tv' => 'https://vidjoy.pro/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'AutoEmbed',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://autoembed.co/movie/tmdb/{tmdb_id}',
                'url_tv' => 'https://autoembed.co/tv/tmdb/{tmdb_id}-{season}-{episode}'
            ),
            array(
                'name' => '1xbet',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://dezzu370xol.com/play/{imdb_id}',
                'url_tv' => 'https://dezzu370xol.com/play/{imdb_id}'
            ),
            array(
                'name' => 'VidVip',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://vidrock.net/movie/{imdb_id}',
                'url_tv' => 'https://vidrock.net/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'MoviesAPI',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://moviesapi.club/movie/{tmdb_id}',
                'url_tv' => 'https://moviesapi.club/tv/{tmdb_id}-{season}-{episode}'
            ),
            array(
                'name' => 'VidSrcVIP',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.vip/embed/movie/{tmdb_id}',
                'url_tv' => 'https://vidsrc.vip/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => '2Embed',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://www.2embed.cc/embed/{tmdb_id}',
                'url_tv' => 'https://www.2embed.cc/embedtv/{tmdb_id}&s={season}&e={episode}'
            ),
            array(
                'name' => 'All in one 🔥 (4K + Download)',
                'type' => 'tmdb',
                'mode' => 'both',
                'url' => 'https://iframe.pstream.mov/media/tmdb-movie-{tmdb_id}',
                'url_tv' => 'https://iframe.pstream.mov/media/tmdb-tv-{tmdb_id}-{season}-{episode}'
            ),
            array(
                'name' => 'Beta',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.icu/embed/movie/{imdb_id}',
                'url_tv' => 'https://vidsrc.icu/embed/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'Gamma',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.cc/v2/embed/movie/{imdb_id}',
                'url_tv' => 'https://vidsrc.cc/v2/embed/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidMe',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.me/embed/movie/{imdb_id}',
                'url_tv' => 'https://vidsrc.me/embed/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'AutoEmbed Pro',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://autoembed.pro/embed/movie/{imdb_id}',
                'url_tv' => 'https://autoembed.pro/embed/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidFast',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://vidfast.pro/movie/{imdb_id}',
                'url_tv' => 'https://vidfast.pro/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'High HD',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://hyhd.org/embed/{imdb_id}',
                'url_tv' => 'https://hyhd.org/embed/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => '111 Movies',
                'type' => 'imdb',
                'mode' => 'player',
                'url' => 'https://111movies.com/movie/{imdb_id}',
                'url_tv' => 'https://111movies.com/tv/{imdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'MultiEmbed',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://multiembed.mov/?video_id={tmdb_id}&tmdb=1',
                'url_tv' => 'https://multiembed.mov/?video_id={tmdb_id}&tmdb=1&s={season}&e={episode}'
            ),
            array(
                'name' => 'EmbedSU',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://embed.su/embed/movie/{tmdb_id}',
                'url_tv' => 'https://embed.su/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'Hexa',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://hexa.watch/watch/movie/{tmdb_id}',
                'url_tv' => 'https://hexa.watch/watch/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidLink',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidlink.pro/movie/{tmdb_id}',
                'url_tv' => 'https://vidlink.pro/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidSrcXyz',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.xyz/embed/movie/{tmdb_id}',
                'url_tv' => 'https://vidsrc.xyz/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'VidSrcSU',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidsrc.su/embed/movie/{tmdb_id}',
                'url_tv' => 'https://vidsrc.su/embed/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => '123Embed',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://play2.123embed.net/movie/{tmdb_id}',
                'url_tv' => 'https://play2.123embed.net/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'RiveStream',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://rivestream.org/embed?type=movie&id={tmdb_id}',
                'url_tv' => 'https://rivestream.org/embed?type=tv&id={tmdb_id}&season={season}&episode={episode}'
            ),
            array(
                'name' => 'Vidora',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://vidora.su/movie/{tmdb_id}',
                'url_tv' => 'https://vidora.su/tv/{tmdb_id}/{season}/{episode}'
            ),
            array(
                'name' => 'StreamFlix',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://watch.streamflix.one/movie/{tmdb_id}/watch?server=1',
                'url_tv' => 'https://watch.streamflix.one/tv/{tmdb_id}/watch?server=1&season={season}&episode={episode}'
            ),
            array(
                'name' => 'UEmbed Premium',
                'type' => 'tmdb',
                'mode' => 'player',
                'url' => 'https://uembed.site/?id={tmdb_id}&apikey=thisisforsurenotapremiumkey_right?',
                'url_tv' => 'https://uembed.site/?id={tmdb_id}&season={season}&episode={episode}&apikey=thisisforsurenotapremiumkey_right?'
            )
        );
    }
    
    /**
     * Get enabled servers
     */
    public function get_enabled_servers(){
        $enabled = $this->get_option('enabled_servers', array());
        
        if(empty($enabled)){
            // Default: enable first 5 servers
            $enabled = array(0, 1, 2, 8, 16); // Vidify, VidJoy, AutoEmbed, All-in-one, MultiEmbed
        }
        
        $all_servers = $this->get_default_servers();
        $enabled_servers = array();
        
        foreach($enabled as $index){
            if(isset($all_servers[$index])){
                $enabled_servers[] = $all_servers[$index];
            }
        }
        
        return $enabled_servers;
    }
    
    /**
     * Generate server URL
     */
    public function generate_server_url($server, $tmdb_id, $imdb_id, $post_type, $season = 1, $episode = 1){
        $url = ($post_type == 'tvshows' && isset($server['url_tv'])) ? $server['url_tv'] : $server['url'];
        
        // Replace variables
        $url = str_replace('{tmdb_id}', $tmdb_id, $url);
        $url = str_replace('{imdb_id}', $imdb_id, $url);
        $url = str_replace('{season}', $season, $url);
        $url = str_replace('{episode}', $episode, $url);
        
        return $url;
    }
    
    /**
     * Add servers to post
     */
    public function add_servers_to_post($post_id, $tmdb_id, $imdb_id){
        $servers = $this->get_enabled_servers();
        $post_type = get_post_type($post_id);
        
        $players = array();
        
        foreach($servers as $server){
            $url = $this->generate_server_url($server, $tmdb_id, $imdb_id, $post_type);
            
            $players[] = array(
                'name' => $server['name'],
                'select' => 'iframe',
                'url' => $url,
                'idioma' => 'en'
            );
        }
        
        update_post_meta($post_id, 'players', $players);
    }
}
