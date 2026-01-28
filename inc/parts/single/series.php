<?php
/*
* -------------------------------------------------------------------------------------
* @author: Doothemes
* @author URI: https://doothemes.com/
* @aopyright: (c) 2021 Doothemes. All rights reserved
* -------------------------------------------------------------------------------------
*
* @since 2.5.0
*
*/

// All Postmeta
$postmeta = doo_postmeta_tvshows($post->ID); // doo_isset($postmeta, '')
$adsingle = doo_compose_ad('_dooplay_adsingle');
// Get User ID
global $user_ID;

// Movies Meta data
$dt_clgnrt = doo_get_postmeta('clgnrt');
// Datos
$backgound = dooplay_get_option('dynamicbg');
$manually  = doo_isset($_GET,'manually');
// Image
$dynamicbg  = dbmovies_get_rand_image(doo_isset($postmeta,'imagenes'));
// Sidebar
$sidebar = dooplay_get_option('sidebar_position_single','right');
// Update Status for generator
if($manually == 'true') {
    if( $user_ID && current_user_can('level_10') ) {
        update_post_meta($post->ID,'clgnrt','1');
    }
}

// Dynamic Background
if($backgound == true) { ?>
<style>
#dt_contenedor {
    background-image: url(<?php echo $dynamicbg; ?>);
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-position: 50% 0%;
}
</style>
<?php } ?>
<!-- Start Single POST -->
<div id="single" class="full_width_layout " itemscope itemtype="http://schema.org/TVSeries">

 <?php
// Get the current post ID
$post_id = get_the_ID();

// Get meta values
$imdb_id = get_post_meta($post_id, 'ids', true);
$tmdb_id = get_post_meta($post_id, 'idtmdb', true);
$trailer_id = get_post_meta($post_id, 'youtube_id', true);

// Detect content type (movie or tv)
$post_type = get_post_type($post_id);
$content_type = ($post_type == 'tvshows') ? 'tv' : 'movie';

// Get total seasons (if TV show)
$total_seasons = 0;
if ($content_type == 'tv') {
    // Count seasons from taxonomy
    $seasons = get_the_terms($post_id, 'seasons');
    if ($seasons && !is_wp_error($seasons)) {
        $total_seasons = count($seasons);
    }
    
    // Or from meta field
    $seasons_meta = get_post_meta($post_id, 'number_of_seasons', true);
    if ($seasons_meta) {
        $total_seasons = $seasons_meta;
    }
}
	$season  = get_query_var('season');
  $episode = get_query_var('episode');
?>

<div class="movie-meta-data" data-tmdb="<?php echo esc_attr($tmdb_id); ?>" data-type="<?php echo esc_attr($content_type); ?>" data-imdb="<?php echo esc_attr($imdb_id); ?>" data-total-seasons="<?php echo esc_attr($total_seasons); ?>" data-trailer="<?php echo esc_attr($trailer_id); ?>"></div>

	<!-- Start Post -->
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <div class="content <?php echo $sidebar; ?>">

        <!-- Custom Video Player -->
        <?php doo_custom_video_player($post->ID, 'tv'); ?>

<div id="dktczn-player" class="dktczn-player-container"></div>
<!-- Download Accordion Section for TV Shows -->
<div class="download-accordion" style="margin: 20px auto;">
  <button class="download-accordion-btn">
    <span><i class="fas fa-download icon"></i> Download TV Show</span>
    <i class="fas fa-chevron-down arrow"></i>
  </button>
  <div class="download-accordion-content">
    <div class="download-accordion-inner">
      <p id="dl-info" style="margin-bottom:15px;font-size:13px;color:#666;">Select an episode to download</p>
      <ul class="download-links-list">
        <li>
          <button id="dl-btn" class="download-link-item" style="width:100%;border:none;cursor:pointer;">
            <div class="download-link-info">
              <div class="download-link-icon">
                <i class="fas fa-download"></i>
              </div>
              <div class="download-link-details">
                <div class="download-link-title">Standard Download</div>
                <div class="download-link-meta">High Quality • Multiple Servers</div>
              </div>
            </div>
            <i class="fas fa-arrow-right download-link-arrow"></i>
          </button>
        </li>
        <li>
          <button id="dl-btn-2" class="download-link-item" style="width:100%;border:none;cursor:pointer;">
            <div class="download-link-info">
              <div class="download-link-icon">
                <i class="fas fa-bolt"></i>
              </div>
              <div class="download-link-details">
                <div class="download-link-title">Direct Download</div>
                <div class="download-link-meta">Fast Speed • Instant Download</div>
              </div>
            </div>
            <i class="fas fa-arrow-right download-link-arrow"></i>
          </button>
        </li>
      </ul>
    </div>
  </div>
</div>
        <!-- Views Counter -->
        <?php DooPlayViews::Meta($post->ID); ?>

        <!-- Head Show Info -->
        <div class="sheader">
	        <div class="poster">
	            <img itemprop="image" src="<?php echo dbmovies_get_poster($post->ID,'medium'); ?>" alt="<?php the_title(); ?>">
	        </div>
	        <div class="data">
		        <h1><?php the_title(); ?></h1>
        		<div class="extra">
        			<?php if($d = doo_isset($postmeta, 'first_air_date')) echo '<span class="date"  itemprop="dateCreated">'.doo_date_compose($d,false).'</span>'; ?>
        			<span><?php echo get_the_term_list($post->ID, 'dtnetworks', '', '', ''); ?></span>
        		</div>
		        <?php echo do_shortcode('[starstruck_shortcode]'); ?>
        		<div class="sgeneros">
        		    <?php echo get_the_term_list($post->ID, 'genres', '', '', ''); ?>
        		</div>
            </div>
        </div>
		
		
		<style>
.dl-btn-main{
  padding:15px 36px;
  background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
  color:#fff;
  border:none;
  border-radius:10px;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  box-shadow:0 4px 15px rgba(102,126,234,.3);
  margin:0 6px;
}

.dl-btn-alt{
  padding:15px 36px;
  background:linear-gradient(135deg,#22c55e,#a3ff12);
  color:#062800;
  border:none;
  border-radius:10px;
  font-size:16px;
  font-weight:700;
  cursor:pointer;
  box-shadow:0 4px 15px rgba(34,197,94,.35);
  margin:0 6px;
}

.dl-btn-main:hover,
.dl-btn-alt:hover{
  transform:translateY(-2px);
}
</style><script>
setTimeout(function() {
  const meta = document.querySelector('.movie-meta-data');
  const imdb = meta?.getAttribute('data-imdb');
  const btn1 = document.getElementById('dl-btn');
  const btn2 = document.getElementById('dl-btn-2');
  const info = document.getElementById('dl-info');

  function update() {
    const p = window.tmdbPlayerInstance;
    const s = p?.currentSeason || 1;
    const e = p?.currentEpisode || 1;

    btn1.textContent = `📥 Download S${s}E${e}`;
    btn2.textContent = `⚡ Direct S${s}E${e}`;
    info.textContent = `Season ${s} • Episode ${e}`;

    // Server 1
    btn1.onclick = () =>
      window.open(`https://dl.vidsrc.vip/tv/${imdb}/${s}/${e}`, '_blank');

    // Server 2
    btn2.onclick = () =>
      window.open(`https://bunnyddl.termsandconditionshere.workers.dev/tv/${imdb}/${s}/${e}`, '_blank');
  }

  update();

  document.addEventListener('click', e => {
    if (e.target.closest('.dktczn-episode-card'))
      setTimeout(update, 500);
  });
}, 1000);
</script>

        <!-- Show Tab single -->
        <div class="single_tabs">
            <?php if(is_user_logged_in() && doo_is_true('permits','eusr')){ ?>
            <div class="user_control">
                <?php dt_list_button( $post->ID ); dt_views_button( $post->ID ); ?>
            </div>
            <?php } ?>
        	<ul id="section" class="smenu idTabs">
                <?php if($dt_clgnrt == '1') echo '<li><a href="#episodes">'.__d('Episodes').'</a></li>'; ?>
        	    <li><a href="#info"><?php _d('Info'); ?></a></li>
        	    <?php if(doo_isset($postmeta,'dt_cast')) echo '<li><a href="#cast">'.__d('Cast').'</a></li>'; ?>
        	    <?php if(doo_isset($postmeta,'youtube_id')) echo '<li><a href="#trailer">'.__d('Trailer').'</a></li>'; ?>
        	</ul>
        </div>

        <!-- Single Post Ad -->
        <?php if($adsingle) echo '<div class="module_single_ads">'.$adsingle.'</div>'; ?>

        <!-- Seasons and Episodes List -->
        <?php get_template_part('inc/parts/single/listas/seasons_episodes'); ?>

        <!-- Show Cast -->
        <div id="cast" class="sbox fixidtab">
        	<h2><?php _d('Creator'); ?></h2>
        	<div class="persons">
        		<?php  doo_creator(doo_isset($postmeta,'dt_creator'), "img", true); ?>
        	</div>
        	<h2><?php _d('Cast'); ?></h2>
        	<div class="persons">
        		<?php doo_cast(doo_isset($postmeta,'dt_cast'), "img", true); ?>
        	</div>
        </div>

        <!-- Show Trailer -->
        <?php if($trailers = doo_isset($postmeta,'youtube_id')){ ?>
        <div id="trailer" class="sbox fixidtab">
        	<h2><?php _d('Video trailer'); ?></h2>
        	<div class="videobox">
        		<div class="embed">
        			<?php echo doo_trailer_iframe($trailers) ?>
        		</div>
        	</div>
        </div>
        <?php } ?>

        <!-- Show Information -->
        <div id="info" class="sbox fixidtab">
            <h2><?php _d('Synopsis'); ?></h2>
            <div class="wp-content">
                <?php the_content(); ?>
                <?php the_tags('<ul class="wp-tags"><li>','</li><li>','</li></ul>'); ?>
                <?php dbmovies_get_images(doo_isset($postmeta,'imagenes')); ?>
            </div>
            <?php if($d = doo_isset($postmeta,'original_name')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Original title'); ?></b>
                <span class="valor"><?php echo $d; ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta, 'imdbRating')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('TMDb Rating'); ?></b>
                <span class="valor">
                    <b id="repimdb"><?php echo '<strong>'.$d.'</strong> '; if($votes = doo_isset($postmeta, 'imdbVotes')) echo sprintf( __d('%s votes'), number_format($votes) ); ?></b>
                </span>
            </div>
            <?php } if($d = doo_isset($postmeta,'first_air_date')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('First air date'); ?></b>
                <span class="valor"><?php doo_date_compose($d); ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta,'last_air_date')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Last air date'); ?></b>
                <span class="valor"><?php doo_date_compose($d); ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta,'number_of_seasons')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Seasons'); ?></b>
                <span class="valor"><?php echo $d; ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta,'number_of_episodes')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Episodes'); ?></b>
                <span class="valor"><?php echo $d; ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta,'episode_run_time')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Average Duration'); ?></b>
                <span class="valor"><?php echo sprintf( __d('%s minutes'), $d); ?> </span>
            </div>
            <?php } ?>
        </div>

        <!-- Show Social Links -->
        <?php doo_social_sharelink($post->ID); ?>

        <!-- Show Related content -->
        <?php if(DOO_THEME_RELATED) get_template_part('inc/parts/single/relacionados'); ?>

        <!-- Show comments -->
        <?php get_template_part('inc/parts/comments'); ?>

        <!-- Show breadcrumb -->
        <?php doo_breadcrumb($post->ID, 'tvshows', __d('TV Shows'), 'breadcrumb_bottom'); ?>

    </div>
    <!-- End Post -->
    <?php endwhile; endif; ?>


    <!-- Show Sidebar -->
    <div class="sidebar <?php echo $sidebar; ?> scrolling">
    	<?php dynamic_sidebar('sidebar-tvshows'); ?>
    </div>


</div>
<!-- End Single POST -->
