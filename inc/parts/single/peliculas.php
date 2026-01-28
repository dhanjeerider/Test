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
$classlinks = new DooLinks;
$postmeta = doo_postmeta_movies($post->ID);
$adsingle = doo_compose_ad('_dooplay_adsingle');
// Movies Meta data
$trailer = doo_isset($postmeta,'youtube_id');
$pviews  = doo_isset($postmeta,'dt_views_count');
$player  = doo_isset($postmeta,'players');
$player  = maybe_unserialize($player);
$images  = doo_isset($postmeta,'imagenes');
$tviews  = ($pviews) ? sprintf( __d('%s Views'), $pviews) : __d('0 Views');
//  Image
$dynamicbg  = dbmovies_get_rand_image($images);
// Options
$player_ads = doo_compose_ad('_dooplay_adplayer');
$player_wht = dooplay_get_option('playsize','regular');
// Sidebar
$sidebar = dooplay_get_option('sidebar_position_single','right');
// Dynamic Background
if(dooplay_get_option('dynamicbg') == true) { ?>
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
<?php get_template_part('inc/parts/single/report-video'); ?>

<!-- Big Player -->
<!-- Start Single -->
<div id="single" class="dtsingle" itemscope itemtype="http://schema.org/Movie">
  				
		
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
?>

<div class="movie-meta-data" data-tmdb="<?php echo esc_attr($tmdb_id); ?>" data-type="<?php echo esc_attr($content_type); ?>" data-imdb="<?php echo esc_attr($imdb_id); ?>" data-total-seasons="<?php echo esc_attr($total_seasons); ?>" data-trailer="<?php echo esc_attr($trailer_id); ?>"></div>
 			
	<!-- Edit link response Ajax -->
    <div id="edit_link"></div>
    <!-- Start Post -->
    <?php if(have_posts()) :while (have_posts()) : the_post(); ?>
    <div class="content <?php echo $sidebar; ?>">

        <!-- Views Counter -->
        <?php DooPlayViews::Meta($post->ID); ?>
<div id="dktczn-player" class="dktczn-player-container"></div>

        <!-- Regular Player and Player Options --><div class="dl-wrap">
  <!-- Download Accordion Section -->
  <div class="download-accordion">
    <button class="download-accordion-btn">
      <span><i class="fas fa-download icon"></i> Download Movie</span>
      <i class="fas fa-chevron-down arrow"></i>
    </button>
    <div class="download-accordion-content">
      <div class="download-accordion-inner">
        <ul class="download-links-list">
          <li>
            <a href="https://dl.vidsrc.vip/movie/<?php echo $tmdb_id; ?>" class="download-link-item" target="_blank">
              <div class="download-link-info">
                <div class="download-link-icon">
                  <i class="fas fa-download"></i>
                </div>
                <div class="download-link-details">
                  <div class="download-link-title">VidSrc Download</div>
                  <div class="download-link-meta">High Quality • Multiple Servers</div>
                </div>
              </div>
              <i class="fas fa-arrow-right download-link-arrow"></i>
            </a>
          </li>
          <li>
            <a href="https://bunnyddl.termsandconditionshere.workers.dev/movie/<?php echo esc_attr($tmdb_id); ?>" class="download-link-item" target="_blank">
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
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
		
        <!-- Head movie Info -->
        <div class="sheader">
        	<div class="poster">
        		<img itemprop="image" src="<?php echo dbmovies_get_poster($post->ID,'medium'); ?>" alt="<?php the_title(); ?>">
        	</div>
        	<div class="data">
        		<h1><?php the_title(); ?></h1>
        		<div class="extra">
        		<?php
                // Movie Meta Info
                if($d = doo_isset($postmeta,'tagline')) echo "<span class='tagline'>{$d}</span>";
        		if($d = doo_isset($postmeta,'release_date')) echo "<span class='date' itemprop='dateCreated'>".doo_date_compose($d,false)."</span>";
        		if($d = doo_isset($postmeta,'Country')) echo "<span class='country'>{$d}</span>";
        		if($d = doo_isset($postmeta,'runtime')) echo "<span itemprop='duration' class='runtime'>{$d} ".__d('Min.')."</span>";
        		if($d = doo_isset($postmeta,'Rated')) echo "<span itemprop='contentRating' class='C{$d} rated'>{$d}</span>";
                // end..
                ?>
        		</div>
        		<?php echo do_shortcode('[starstruck_shortcode]'); ?>
        		<div class="sgeneros">
        		<?php echo get_the_term_list($post->ID, 'genres', '', '', ''); ?>
        		</div>
        	</div>
        </div>

        <!-- Movie Tab single -->
        <div class="single_tabs">
            <?php if(is_user_logged_in() && doo_is_true('permits','eusr')){ ?>
        	<div class="user_control">
        		<?php dt_list_button($post->ID); dt_views_button($post->ID); ?>
        	</div>
            <?php } ?>
        	<ul id="section" class="smenu idTabs">
            	<li><a id="main_ali" href="#info"><?php _d('Info'); ?></a></li>
                <li><a href="#cast"><?php _d('Cast'); ?></a></li>
                <?php if(doo_here_links($post->ID)) echo '<li><a href="#linksx">'.__d('Links').'</a></li>'; ?>
        	</ul>
        </div>

        <!-- Single Post Ad -->
        <?php if($adsingle) echo '<div class="module_single_ads">'.$adsingle.'</div>'; ?>

        <!-- Movie more info -->
        <div id="info" class="sbox">
            <h2><?php _d('Synopsis'); ?></h2>
            <div itemprop="description" class="wp-content">
                <?php the_content(); ?>
                <?php the_tags('<ul class="wp-tags"><li>','</li><li>','</li></ul>'); ?>
                <?php dbmovies_get_images($images); ?>
            </div>
            <?php if($d = doo_isset($postmeta, 'original_title')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('Original title'); ?></b>
                <span class="valor"><?php echo $d; ?></span>
            </div>
            <?php } if($d = doo_isset($postmeta, 'imdbRating')) { ?>
            <div class="custom_fields">
        	    <b class="variante"><?php _d('IMDb Rating'); ?></b>
        	    <span class="valor">
        		    <b id="repimdb"><?php echo '<strong>'.$d.'</strong> '; if($votes = doo_isset($postmeta, 'imdbVotes')) echo sprintf( __d('%s votes'), doo_format_number($votes) ); ?></b>
        	        <?php if(current_user_can('administrator')) { ?><a data-id="<?php echo $post->ID; ?>" data-imdb="<?php echo doo_isset($postmeta, 'ids'); ?>" id="update_imdb_rating"><?php _d('Update Rating'); ?></a><?php } ?>
        	    </span>
            </div>
            <?php } if($d = doo_isset($postmeta, 'vote_average')) { ?>
            <div class="custom_fields">
                <b class="variante"><?php _d('TMDb Rating'); ?></b>
                <span class="valor"><?php echo '<strong>'.$d.'</strong> '; if($votes = doo_isset($postmeta, 'vote_count')) echo sprintf( __d('%s votes'), number_format($votes) ); ?></span>
            </div>
            <?php } ?>
        </div>

        <!-- Movie Cast -->
        <div id="cast" class="sbox fixidtab">
            <h2><?php _d('Director'); ?></h2>
            <div class="persons">
            	<?php doo_director(doo_isset($postmeta,'dt_dir'), "img", true); ?>
            </div>
            <h2><?php _d('Cast'); ?></h2>
            <div class="persons">
            	<?php doo_cast(doo_isset($postmeta,'dt_cast'), "img", true); ?>
            </div>
        </div>

        <!-- Movie Links -->
        <?php if(DOO_THEME_DOWNLOAD_MOD) get_template_part('inc/parts/single/links'); ?>

        <!-- Movie Social Links -->
        <?php doo_social_sharelink($post->ID); ?>

        <!-- Movie Related content -->
        <?php if(DOO_THEME_RELATED) get_template_part('inc/parts/single/relacionados'); ?>

        <!-- Movie comments -->
        <?php get_template_part('inc/parts/comments'); ?>

        <!-- Movie breadcrumb -->
        <?php doo_breadcrumb( $post->ID, 'movies', __d('Movies'), 'breadcrumb_bottom' ); ?>

    </div>
    <!-- End Post-->
    <?php endwhile; endif; ?>


</div>
<!-- End Single -->

    <!-- Movie Sidebar -->
    <div class="sidebar  <?php echo $sidebar; ?> scrolling">
    	<?php dynamic_sidebar('sidebar-movies'); ?>
    </div>
    <!-- End Sidebar -->