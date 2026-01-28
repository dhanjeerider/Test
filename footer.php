<?php
/*
* ----------------------------------------------------
* @author: Doothemes
* @author URI: https://doothemes.com/
* @copyright: (c) 2021 Doothemes. All rights reserved
* ----------------------------------------------------
*
* @since 2.5.0
*
*/
// Options
$focode = get_option('_dooplay_footer_code');
$footer = dooplay_get_option('footer');
$fotext = dooplay_get_option('footertext');
$foclm1 = dooplay_get_option('footerc1');
$foclm2 = dooplay_get_option('footerc2');
$foclm3 = dooplay_get_option('footerc3');
$focopy = dooplay_get_option('footercopyright');
$fologo = doo_compose_image_option('logofooter');
$fologo = !empty($fologo) ? $fologo : DOO_URI.'/assets/img/brand/dooplay_logo_gray.svg';
// Copyright
$copytext = sprintf( __d('%s %s by %s. All Rights Reserved. Powered by %s'), '&copy;', date('Y'), '<strong>'.get_option('blogname').'</strong>', '<a href="https://doothemes.com/items/dooplay/"><strong>DooPlay</strong></a>' );
$copyright = isset($focopy) ? str_replace('{year}', date('Y'), $focopy) : $copytext;
?>
</div>
<footer class="main">
	<div class="fbox">
		<div class="fcmpbox">
			<?php if( $footer == 'complete' ) { ?>
			<div class="primary">
				<div class="columenu">
					<div class="item">
					   <?php echo ( $foclm1 ) ? '<h3>'. $foclm1. '</h3>' : null; ?>
					   <?php wp_nav_menu( array('theme_location' => 'footer1', 'fallback_cb' => null ) ); ?>
					</div>
					<div class="item">
						<?php echo ( $foclm2 ) ? '<h3>'. $foclm2. '</h3>' : null; ?>
						<?php wp_nav_menu( array('theme_location' => 'footer2', 'fallback_cb' => null)); ?>
					</div>
					<div class="item">
						<?php echo ( $foclm3 ) ? '<h3>'. $foclm3. '</h3>' : null; ?>
						<?php wp_nav_menu( array('theme_location' => 'footer3', 'fallback_cb' => null)); ?>
					</div>
				</div>
				<div class="fotlogo">
					<?php
					// Logo And text
					echo '<div class="logo"><img src="'. $fologo .'" alt="'.get_option('blogname').'" /></div>';
					echo ( $fotext ) ? '<div class="text"><p>'. $fotext. '</p></div>' : null;
					?>
				</div>
			</div>
			<?php } ?>
			<span class="top-page"><a id="top-page"><i class="fas fa-angle-up"></i></a></span>
			<?php wp_nav_menu( array('theme_location' => 'footer','container_class' => 'fmenu', 'fallback_cb' => null ) ); ?>
		</div>
	</div>

</footer>
</div>
<?php wp_footer();  if( $focode ) echo stripslashes( $focode ). "\n"; ?>
<div id="oscuridad"></div>
<?php if(is_single() == true AND get_post_type() != 'seasons' AND get_post_meta($post->ID, 'imagenes', true) ) { ?>
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev"><?php _d('&lsaquo;'); ?></a>
    <a class="next"><?php _d('&rsaquo;'); ?></a>
    <a class="close"><?php _d('&times;'); ?></a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>



<?php } ?>


<?php if ( is_single() ) : ?>
	<script>//<![CDATA[
// TMDB Configuration  
const TMDB_API_KEY = '7bffed716d50c95ed1c4790cfab4866a';  
const TMDB_BASE_URL = 'https://api.themoviedb.org/3';  
const TMDB_IMG_BASE = 'https://image.tmdb.org/t/p';  
  
// Streaming Servers  
const SERVERS = [  
  { name: "🔥 Dezzu (Recommended)", type: "imdb", url: "https://dezzu370xol.com/play/{imdb_id}", url_tv: "https://dezzu370xol.com/play/{imdb_id}" },  
  { name: "Vidify", type: "tmdb", url: "https://vidify.top/embed/movie/{tmdb_id}", url_tv: "https://vidify.top/embed/tv/{tmdb_id}/{season}/{episode}" },  
  { name: "RiveStream", type: "tmdb", url: "https://rivestream.org/embed? type=movie&id={tmdb_id}", url_tv: "https://rivestream.org/embed?type=tv&id={tmdb_id}&season={season}&episode={episode}" },  
  { name: "VidJoy", type: "tmdb", url: "https://vidjoy.pro/embed/movie/{tmdb_id}", url_tv: "https://vidjoy.pro/embed/tv/{tmdb_id}/{season}/{episode}" },  
  { name: "AutoEmbed", type: "tmdb", url: "https://autoembed.co/movie/tmdb/{tmdb_id}", url_tv: "https://autoembed.co/tv/tmdb/{tmdb_id}-{season}-{episode}" },  
  { name: "All in one 🔥", type: "tmdb", url: "https://iframe.pstream.mov/media/tmdb-movie-{tmdb_id}", url_tv: "https://iframe.pstream.mov/media/tmdb-tv-{tmdb_id}-{season}-{episode}" },  
  { name: "Hexa", type: "tmdb", url: "https://hexa.watch/watch/movie/{tmdb_id}", url_tv: "https://hexa.watch/watch/tv/{tmdb_id}/{season}/{episode}" },  
  { name: "VidSrc VIP", type: "imdb", url: "https://vidsrc.vip/embed/movie/{imdb_id}", url_tv: "https://vidsrc.vip/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "VidSrc TO", type: "imdb", url: "https://vidsrc.to/embed/movie/{imdb_id}", url_tv: "https://vidsrc.to/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "VidSrc ICU", type: "imdb", url: "https://vidsrc.icu/embed/movie/{imdb_id}", url_tv:  "https://vidsrc.icu/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "MoviesAPI", type: "tmdb", url: "https://moviesapi.club/movie/{tmdb_id}", url_tv: "https://moviesapi.club/tv/{tmdb_id}-{season}-{episode}" },  
  { name: "2Embed", type: "tmdb", url: "https://www.2embed.cc/embed/{tmdb_id}", url_tv: "https://www.2embed.cc/embedtv/{tmdb_id}&s={season}&e={episode}" },  
  { name: "VidSrc CC", type: "imdb", url: "https://vidsrc.cc/v2/embed/movie/{imdb_id}", url_tv: "https://vidsrc.cc/v2/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "Embed SU", type: "imdb", url: "https://embed.su/embed/movie/{imdb_id}", url_tv: "https://embed.su/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name:  "VidSrc ME", type: "imdb", url: "https://vidsrc.me/embed/movie/{imdb_id}", url_tv: "https://vidsrc.me/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "AutoEmbed Pro", type: "imdb", url: "https://autoembed.pro/embed/movie/{imdb_id}", url_tv: "https://autoembed.pro/embed/tv/{imdb_id}/{season}/{episode}" },  
  { name: "MultiEmbed", type: "tmdb", url: "https://multiembed.mov/? video_id={tmdb_id}&tmdb=1", url_tv: "https://multiembed.mov/?video_id={tmdb_id}&tmdb=1&s={season}&e={episode}" },  
  { name: "SmashyStream", type: "tmdb", url: "https://player.smashy.stream/movie/{tmdb_id}", url_tv: "https://player.smashy.stream/tv/{tmdb_id}? s={season}&e={episode}" }  
];  
  
class TMDBPlayer {  
  constructor() {  
    this.currentServer = SERVERS[0];  
    this.currentSeason = 1;  
    this.currentEpisode = 1;  
    this.currentMode = 'episode';  
    this.episodesData = {};  
    this.trailerUrl = null;  
    this. playerMode = this.detectPlayerMode();  
    this.iframeLoaded = false;  
    this.pendingUrl = null;
    this.playerContainer = document.getElementById('dktczn-player');
    
    if (! this.playerContainer) {
      console.error('❌ Player container #dktczn-player not found! ');
      return;
    }
    
    this.init();  
  }  
  
  detectPlayerMode() {  
    const mediaData = document.querySelector('.movie-meta-data');  
    if (mediaData) {  
      const type = mediaData.getAttribute('data-type');  
      return type === 'tv' ? 'tv' : 'movie';  
    }  
    return 'movie';  
  }  
  
  async init() {  
    console.log(`🎬 Player Mode: ${this.playerMode. toUpperCase()}`);  
      
    this.loadUserPreferences();  
    this.addCustomStyles();  
    this.injectPlayer();
    this.setupTrailerUrl();  
      
    if (this.playerMode === 'tv') {  
      await this.fetchAndDisplayEpisodes();  
    }
    
    this.setupControlButtons();  
  }  
  
  // ==================== INJECT PLAYER ====================
  injectPlayer() {
    this.playerContainer.innerHTML = `
      <div class="dktczn-play-overlay" id="play-overlay">  
        <button class="dktczn-play-button" id="play-button">  
          <svg class="play-icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18.4 12.5L9 18.38L8 19V6zm-1.9 0L9 7.8v9.4z"/></svg>
        </button>  
        <div class="play-info">Ready to stream</div>  
      </div>  
      <iframe id="video-player-iframe" class="dktczn-iframe" allowfullscreen allow="autoplay; encrypted-media" style="display: none;"></iframe>
      
      <div class="dktczn-selectors">
        <div class="dktczn-selectors-wrapper">
          <div class="dktczn-selector-group">
            <label class="ad-blocker-toggle">  
              <input type="checkbox" id="sandbox-toggle" checked>
              <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 48 48">
                <g fill="none">
                  <circle cx="24" cy="24" r="20" stroke="#ed2828" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                  <path stroke="#ed2828" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m38 38l-3-3M10 10l3 3"/>
                  <path d="M21. 143 28L18 17l-3.143 11z"/>
                  <path stroke="#ed2828" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m14 31l. 857-3M22 31l-. 857-3m0 0L18 17l-3.143 11m6. 286 0h-6.286M35 24c0 5-3. 582 7-8 7V17c4.418 0 8 2 8 7"/>
                </g>
              </svg>  
            </label>
            <label for="dktczn-server-select" class="dktczn-selector-label">🌐 Server</label>
            <select id="dktczn-server-select" class="dktczn-selector">
              ${SERVERS.map((server, index) => 
                `<option value="${index}" ${this.currentServer === server ?  'selected' : ''}>${server.name}</option>`
              ).join('')}
            </select>
          </div>
        </div>
      </div>
      
      ${this.playerMode === 'tv' ? '<div class="dktczn-episodes-container"></div>' : ''}
    `;
    
    this.setupEventListeners();
  }
  
  setupEventListeners() {
    const playButton = document.getElementById('play-button');  
    const playOverlay = document.getElementById('play-overlay');  
      
    playButton.addEventListener('click', () => {  
      this.iframeLoaded = true;  
      playOverlay. style.display = 'none';  
        
      if (this.pendingUrl) {  
        this.loadMediaNow(this.pendingUrl);  
        this.pendingUrl = null;  
      } else {  
        this.loadMedia();  
      }  
    });
    
    const serverSelect = document.getElementById('dktczn-server-select');  
    serverSelect.addEventListener('change', (e) => {  
      const serverIndex = parseInt(e.target. value);  
      this.currentServer = SERVERS[serverIndex];  
      this.saveServerPreference(serverIndex);  
        
      if (! this.iframeLoaded) {  
        this.iframeLoaded = true;  
        playOverlay.style. display = 'none';  
      }  
        
      if (this.currentMode === 'episode') {  
        this.loadMedia();  
      }  
    });
    
    const sandboxToggle = document.getElementById('sandbox-toggle');  
    sandboxToggle.addEventListener('change', (e) => {  
      const iframe = document.getElementById('video-player-iframe');  
      if (iframe) {  
        if (e.target.checked) {  
          iframe.setAttribute('sandbox', 'allow-forms allow-pointer-lock allow-same-origin allow-scripts allow-top-navigation');  
        } else {  
          iframe.removeAttribute('sandbox');  
        }  
      }  
    });
    
    const iframe = document.getElementById('video-player-iframe');  
    if (iframe) {  
      iframe.setAttribute('sandbox', 'allow-forms allow-pointer-lock allow-same-origin allow-scripts allow-top-navigation');  
    }
  }
  
  // ==================== USER PREFERENCES ====================  
  loadUserPreferences() {  
    try {  
      const savedServerIndex = localStorage.getItem('dktczn_selected_server');  
      if (savedServerIndex !== null) {  
        const index = parseInt(savedServerIndex);  
        if (index >= 0 && index < SERVERS.length) {  
          this.currentServer = SERVERS[index];  
          console.log(`💾 Loaded server: ${this. currentServer.name}`);  
        }  
      }  
    } catch (error) {  
      console.error('Error loading preferences:', error);  
    }  
  }  
  
  saveServerPreference(serverIndex) {  
    try {  
      localStorage.setItem('dktczn_selected_server', serverIndex);  
      console.log(`💾 Server saved: ${SERVERS[serverIndex].name}`);  
    } catch (error) {  
      console.error('Error saving server:', error);  
    }  
  }  
  
  setupTrailerUrl() {  
    const mediaData = this.getMediaData();  
    if (mediaData) {  
      const trailerYoutubeId = mediaData.getAttribute('data-trailer');  
      if (trailerYoutubeId && trailerYoutubeId.trim() !== '') {  
        // Remove brackets if present
        const cleanId = trailerYoutubeId.replace(/[\[\]]/g, '');
        this.trailerUrl = `https://www.youtube.com/embed/${cleanId}? autoplay=1&controls=1`;  
      }  
    }  
  }  
  
  // ==================== CONTROL BUTTONS ====================  
  setupControlButtons() {  
    const selectorsDiv = this.playerContainer.querySelector('.dktczn-selectors');
    if (! selectorsDiv) return;  
  
    const controlsContainer = document.createElement('div');  
    controlsContainer.className = 'dktczn-controls-container';  
      
    let buttonsHTML = '';  
  
    // Trailer Button  
    if (this.trailerUrl) {  
      buttonsHTML += `  
        <button class="dktczn-control-btn dktczn-trailer-btn" id="trailer-toggle-btn" title="Watch Trailer">  
          <span class="btn-icon">🎥</span>  
          <span class="btn-text">Watch Trailer</span>  
        </button>  
      `;  
    }  
  
    controlsContainer.innerHTML = buttonsHTML;  
    selectorsDiv.insertAdjacentElement('afterend', controlsContainer);  
  
    this.setupControlListeners();  
  }  
  
  setupControlListeners() {  
    const trailerBtn = document.getElementById('trailer-toggle-btn');  
    if (trailerBtn) {  
      let isTrailerMode = false;  
        
      trailerBtn.addEventListener('click', () => {  
        isTrailerMode = !isTrailerMode;  
          
        if (! this.iframeLoaded) {  
          this.iframeLoaded = true;  
          const playOverlay = document.getElementById('play-overlay');  
          if (playOverlay) playOverlay.style.display = 'none';  
        }  
          
        if (isTrailerMode) {  
          this.currentMode = 'trailer';  
          this.loadMedia();  
            
          trailerBtn.classList.add('active');  
          trailerBtn.innerHTML = `  
            <span class="btn-icon">◀️</span>  
            <span class="btn-text">Back to ${this.playerMode === 'movie' ? 'Movie' : 'Episode'}</span>  
          `;  
        } else {  
          this.currentMode = 'episode';  
          this.loadMedia();  
            
          trailerBtn.classList.remove('active');  
          trailerBtn.innerHTML = `  
            <span class="btn-icon">🎥</span>  
            <span class="btn-text">Watch Trailer</span>  
          `;  
        }  
      });  
    }  
  }  
  
  // ==================== TV EPISODES ====================  
  async fetchAndDisplayEpisodes() {  
    const mediaData = this.getMediaData();  
    if (!mediaData) return;  
  
    const tmdbId = mediaData.tmdb_id;  
    const totalSeasons = parseInt(mediaData.getAttribute('data-total-seasons')) || 1;  
  
    const episodesDiv = this.playerContainer.querySelector('.dktczn-episodes-container');
    if (!episodesDiv) return;
  
    episodesDiv.innerHTML = '<div class="dktczn-loading">📺 Loading Episodes...</div>';  
  
    try {  
      const promises = [];  
      for (let season = 1; season <= totalSeasons; season++) {  
        promises.push(this.fetchSeasonEpisodes(tmdbId, season));  
      }  
      await Promise.all(promises);  
        
      this.renderEpisodesUI(episodesDiv, totalSeasons);  
    } catch (error) {  
      console.error('Error fetching episodes:', error);  
      episodesDiv.innerHTML = '<div class="dktczn-error">❌ Failed to load episodes</div>';  
    }  
  }  
  
  async fetchSeasonEpisodes(tmdbId, season) {  
    if (! TMDB_API_KEY || TMDB_API_KEY === 'YOUR_TMDB_API_KEY') {  
      this.episodesData[season] = this.generatePlaceholderEpisodes(season);  
      return;  
    }  
  
    try {  
      const url = `${TMDB_BASE_URL}/tv/${tmdbId}/season/${season}?api_key=${TMDB_API_KEY}&language=en-US`;  
      const response = await fetch(url);  
        
      if (!response.ok) throw new Error(`HTTP ${response.status}`);  
        
      const data = await response. json();  
      this.episodesData[season] = data. episodes || [];  
    } catch (error) {  
      console.error(`Error fetching season ${season}: `, error);  
      this.episodesData[season] = this.generatePlaceholderEpisodes(season);  
    }  
  }  
  
  generatePlaceholderEpisodes(season, count = 10) {  
    return Array.from({ length: count }, (_, i) => ({  
      episode_number: i + 1,  
      name: `Episode ${i + 1}`,  
      still_path: null,  
      overview: `Season ${season} Episode ${i + 1}`  
    }));  
  }  
  
  renderEpisodesUI(container, totalSeasons) {  
    let html = `  
      <div class="dktczn-seasons-header">  
        <label for="dktczn-season-dropdown" class="dktczn-season-label">📺 Select Season</label>  
        <select id="dktczn-season-dropdown" class="dktczn-season-dropdown">  
    `;  
  
    for (let season = 1; season <= totalSeasons; season++) {  
      html += `<option value="${season}" ${season === 1 ? 'selected' :  ''}>Season ${season}</option>`;  
    }  
  
    html += '</select></div><div class="dktczn-episodes-wrapper">';  
      
    for (let season = 1; season <= totalSeasons; season++) {  
      const episodes = this.episodesData[season] || [];  
      html += `<div class="dktczn-season-grid" data-season="${season}" style="display: ${season === 1 ? 'grid' : 'none'};">`;  
  
      episodes.forEach(episode => {  
        const episodeNum = String(episode.episode_number).padStart(2, '0');  
        const thumbnail = episode.still_path   
          ? `${TMDB_IMG_BASE}/w342${episode.still_path}`  
          : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22169%22%3E%3Crect fill=%22%23333%22 width=%22300%22 height=%22169%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E';  
  
        html += `  
          <div class="dktczn-episode-card" data-season="${season}" data-episode="${episode.episode_number}">  
            <div class="dktczn-episode-thumbnail">  
              <img src="${thumbnail}" alt="Episode ${episodeNum}" loading="lazy">  
              <div class="dktczn-episode-play">▶</div>  
            </div>  
            <div class="dktczn-episode-info">  
              <div class="dktczn-episode-number">Ep ${episodeNum}</div>  
              <div class="dktczn-episode-title">${episode.name || `Episode ${episodeNum}`}</div>  
            </div>  
          </div>  
        `;  
      });  
  
      html += '</div>';  
    }  
  
    html += '</div>';  
    container.innerHTML = html;  
  
    document.getElementById('dktczn-season-dropdown').addEventListener('change', (e) => {  
      const selectedSeason = parseInt(e.target.value);  
      document.querySelectorAll('.dktczn-season-grid').forEach(grid => {  
        grid.style.display = grid.dataset.season == selectedSeason ? 'grid' : 'none';  
      });  
    });  
  
    document.querySelectorAll('.dktczn-episode-card').forEach(card => {  
      card.addEventListener('click', () => {  
        document.querySelectorAll('.dktczn-episode-card').forEach(c => c.classList.remove('active'));  
        card.classList. add('active');  
  
        this.currentSeason = parseInt(card.dataset. season);  
        this.currentEpisode = parseInt(card. dataset.episode);  
        this.currentMode = 'episode';  
          
        if (!this.iframeLoaded) {  
          this.iframeLoaded = true;  
          const playOverlay = document.getElementById('play-overlay');  
          if (playOverlay) playOverlay.style.display = 'none';  
        }  
          
        this.loadMedia();  
      });  
    });  
  
    const firstEpisode = document.querySelector('.dktczn-episode-card');  
    if (firstEpisode) firstEpisode.classList.add('active');  
  }  
  
  // ==================== MEDIA LOADING ====================  
  getMediaData() {  
    const mediaDataEl = document.querySelector('.movie-meta-data');  
    if (!mediaDataEl) return null;  
  
    return {  
      tmdb_id: mediaDataEl.getAttribute('data-tmdb'),  
      type: mediaDataEl.getAttribute('data-type'),  
      imdb_id: mediaDataEl. getAttribute('data-imdb') || '',  
      getAttribute:  (attr) => mediaDataEl.getAttribute(attr)  
    };  
  }  
  
  buildUrl(server, season = null, episode = null) {  
    const mediaData = this. getMediaData();  
    if (!mediaData) return '';  
  
    let url = mediaData.type === 'tv' && season && episode   
      ? (server. url_tv || server.url)  
      : server.url;  
  
    return url  
      . replace('{tmdb_id}', mediaData.tmdb_id)  
      .replace('{imdb_id}', mediaData. imdb_id)  
      .replace('{season}', season || this.currentSeason)  
      .replace('{episode}', episode || this.currentEpisode);  
  }  
  
  loadMedia(customUrl = null) {  
    let url;  
      
    if (customUrl) {  
      url = customUrl;  
    } else if (this.currentMode === 'trailer' && this.trailerUrl) {  
      url = this.trailerUrl;  
    } else {  
      url = this.buildUrl(this.currentServer, this.currentSeason, this.currentEpisode);  
    }  
  
    if (! this.iframeLoaded) {  
      this.pendingUrl = url;  
      console.log('⏳ Waiting for play button click, URL queued');  
      return;  
    }  
  
    this. loadMediaNow(url);  
  }  
  
  loadMediaNow(url) {  
    const iframe = document.getElementById('video-player-iframe');  
      
    if (!iframe) return;  
  
    console.log(`▶️ Loading:  ${url}`);  
      
    iframe.src = url;  
    iframe.style.display = 'block';  
  }  
  
  // ==================== STYLES ====================  
  addCustomStyles() {  
    if (document.getElementById('dktczn-player-styles')) return;  
  
    const style = document.createElement('style');  
    style.id = 'dktczn-player-styles';  
    style.textContent = `  
      #dktczn-player {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
      }
      
      . dktczn-iframe {
        width: 100%;
        aspect-ratio: 16/9;
        border: none;
        border-radius: 12px;
      }
      
      .dktczn-play-overlay {  
        position: relative;
        width: 100%;
        aspect-ratio: 16/9;
        display: flex;  
        flex-direction: column;  
        align-items: center;  
        justify-content: center;  
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  
        border-radius: 12px;  
        overflow: hidden;  
      }  
  
      .dktczn-play-overlay::before {  
        content: '';  
        position: absolute;  
        top:  0;  
        left:  0;  
        right:  0;  
        bottom:  0;  
        background:  url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat center bottom;  
        background-size: cover;  
        opacity: 0.3;  
      }  
  
      .dktczn-play-button {  
        position: relative;  
        z-index: 2;  
        background: rgba(255, 255, 255, 0.95);  
        border: none;  
        border-radius: 50%;  
        width: 80px;  
        height: 80px;  
        display: flex;  
        flex-direction: column;  
        align-items: center;  
        justify-content: center;  
        cursor: pointer;  
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);  
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);  
        gap: 8px;  
      }  
  
      .dktczn-play-button:hover {  
        transform: scale(1.1);  
        box-shadow:  0 12px 48px rgba(0, 0, 0, 0.4);  
        background: #fff;  
      }  
  
      .dktczn-play-button:active {  
        transform: scale(1.05);  
      }  
  
      .play-icon {  
        width: 58px;  
        height: 58px;  
        color: #667eea;  
        transition: color 0.3s;  
      }  

      #sandbox-toggle {  
        border: none;  
        outline: none;  
      }  
    
      #sandbox-toggle:focus {  
        outline: none;  
        box-shadow: none;  
      }  
      
      .dktczn-play-button:hover .play-icon {  
        color: #764ba2;  
      }  
  
      .play-text {  
        font-size: 12px;  
        font-weight: 600;  
        color: #667eea;  
        text-transform: uppercase;  
        letter-spacing: 1px;  
      }  
  
      . dktczn-play-button: hover .play-text {  
        color: #764ba2;  
      }  
  
      .play-info {  
        position: relative;  
        z-index:  2;  
        margin-top: 20px;  
        color: rgba(255, 255, 255, 0.9);  
        font-size: 16px;  
        font-weight: 500;  
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);  
      }  
      
      .dktczn-selectors {
        margin-top: 20px;
      }
      
      .dktczn-selectors-wrapper {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
      }
      
      .dktczn-selector-group {
        display: flex;
        align-items: center;
        gap: 10px;
      }
      
      .dktczn-selector-label {
        font-weight: 600;
        color: #333;
      }
      
      .dktczn-selector {
        padding: 8px 16px;
        border-radius: 8px;
        border: 2px solid #667eea;
        background: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
      }
      
      .dktczn-selector:hover {
        border-color: #764ba2;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
      }
      
      .ad-blocker-toggle {
        display:  flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
      }
      
      .ad-blocker-toggle input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
      }
  
      . dktczn-controls-container {  
        display: flex;  
        gap: 12px;  
        justify-content: center;  
        margin:  20px 0;  
        padding: 0 15px;  
        flex-wrap: wrap;  
      }  
  
      .dktczn-control-btn {  
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  
        color: #fff;  
        border: none;  
        border-radius:  12px;  
        padding: 12px 24px;  
        font-size: 15px;  
        font-weight: 600;  
        cursor: pointer;  
        display: flex;  
        align-items: center;  
        gap: 8px;  
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);  
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);  
        position: relative;  
        overflow:  hidden;  
      }  
  
      .dktczn-control-btn:: before {  
        content: '';  
        position: absolute;  
        top: 0;  
        left: -100%;  
        width: 100%;  
        height: 100%;  
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);  
        transition:  left 0.5s;  
      }  
  
      .dktczn-control-btn:hover::before {  
        left: 100%;  
      }  
  
      .dktczn-control-btn:hover {  
        transform: translateY(-2px) scale(1.05);  
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);  
      }  
  
      .dktczn-control-btn:active {  
        transform: translateY(0) scale(0.98);  
      }  
  
      .dktczn-control-btn.active {  
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);  
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);  
      }  
  
      . btn-icon {  
        font-size: 18px;  
      }  
  
      .btn-text {  
        position: relative;  
        z-index: 1;  
      }  
      
      . dktczn-episodes-container {
        margin-top: 30px;
      }
      
      .dktczn-seasons-header {
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: center;
        margin-bottom: 20px;
      }
      
      .dktczn-season-label {
        font-weight: 600;
        color: #333;
      }
      
      .dktczn-season-dropdown {
        padding: 8px 16px;
        border-radius: 8px;
        border: 2px solid #667eea;
        background:  white;
        font-size:  14px;
        cursor:  pointer;
      }
      
      .dktczn-season-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        padding: 15px;
      }
      
      .dktczn-episode-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }
      
      . dktczn-episode-card:hover {
        transform: translateY(-4px);
        box-shadow:  0 4px 16px rgba(0,0,0,0.2);
      }
      
      .dktczn-episode-card.active {
        border:  3px solid #667eea;
      }
      
      . dktczn-episode-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        background: #333;
      }
      
      .dktczn-episode-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width:  100%;
        height: 100%;
        object-fit:  cover;
      }
      
      .dktczn-episode-play {
        position: absolute;
        top: 50%;
        left:  50%;
        transform: translate(-50%, -50%);
        font-size: 32px;
        color: white;
        opacity: 0;
        transition:  opacity 0.3s;
      }
      
      . dktczn-episode-card:hover .dktczn-episode-play {
        opacity:  1;
      }
      
      .dktczn-episode-info {
        padding: 12px;
      }
      
      .dktczn-episode-number {
        font-weight: 600;
        color: #667eea;
        font-size: 12px;
      }
      
      .dktczn-episode-title {
        font-size: 14px;
        color: #333;
        margin-top: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      
      .dktczn-loading,
      .dktczn-error {
        text-align: center;
        padding: 20px;
        font-size: 16px;
        color: #666;
      }
  
      @media (max-width: 768px) {  
        .dktczn-play-button {  
          width: 60px;  
          height: 60px;  
        }  
  
        .play-icon {  
          width: 30px;  
          height: 30px;  
        }  
  
        .play-text {  
          font-size: 10px;  
        }  
  
        . dktczn-controls-container {  
          flex-direction: column;  
        }  
          
        .dktczn-control-btn {  
          width: 100%;  
          justify-content: center;  
        }
        
        .dktczn-season-grid {
          grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
      }  
    `;  
    document.head.appendChild(style);  
  }  
}  
  
// Initialize  
document.addEventListener('DOMContentLoaded', () => {  
  window.tmdbPlayerInstance = new TMDBPlayer();  
  window.player = window.tmdbPlayerInstance;  
  console.log('✅ Player Initialized');  
});  
//]]></script>
<?php endif; ?>

</body>