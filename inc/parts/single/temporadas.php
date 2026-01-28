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
$postmeta = doo_postmeta_seasons($post->ID);
$adsingle = doo_compose_ad('_dooplay_adsingle');
// Get User ID
global $user_ID;
// Main data
$ids    = doo_isset($postmeta,'ids');
$temp   = doo_isset($postmeta,'temporada');
$clgnrt = doo_isset($postmeta,'clgnrt');
$tvshow = doo_get_tvpermalink($ids);
// Link generator
$addlink = wp_nonce_url(admin_url('admin-ajax.php?action=seasonsf_ajax','relative').'&se='.$ids.'&te='.$temp.'&link='.$post->ID ,'add_episodes', 'episodes_nonce');
// Sidebar
$sidebar = dooplay_get_option('sidebar_position_single','right');
// Title Options
$title_opti = dooplay_get_option('dbmvstitleseasons',__d('{name}: Season {season}'));
$title_data = array(
    'name'   => get_the_title($tvshow),
    'season' => $temp
);

// End PHP
?>

<!-- Start Single POST -->
<div id="single" class="dtsingle">

<?php
// Get the current post ID
$post_id = get_the_ID();

// Get meta values
$imdb_id = get_post_meta($post_id, 'ids', true);
$tmdb_id = get_post_meta($post_id, 'idtmdb', true);
$trailer_id = get_post_meta($post_id, 'youtube_id', true);

// Seasons are TV type
$content_type = 'tv';

// Get total seasons and current season
$total_seasons = 0;
$current_season = doo_isset($postmeta, 'temporada');
$tvshow_id = doo_get_tvpermalink($ids);
if ($tvshow_id) {
    $seasons = get_the_terms($tvshow_id, 'seasons');
    if ($seasons && !is_wp_error($seasons)) {
        $total_seasons = count($seasons);
    }
    
    $seasons_meta = get_post_meta($tvshow_id, 'number_of_seasons', true);
    if ($seasons_meta) {
        $total_seasons = $seasons_meta;
    }
}
?>

<div class="movie-meta-data" 
     data-tmdb="<?php echo esc_attr($tmdb_id); ?>" 
     data-type="<?php echo esc_attr($content_type); ?>" 
     data-imdb="<?php echo esc_attr($imdb_id); ?>" 
     data-total-seasons="<?php echo esc_attr($total_seasons); ?>" 
     data-season="<?php echo esc_attr($current_season); ?>"
     data-trailer="<?php echo esc_attr($trailer_id); ?>"></div>

    <!-- Start Post -->
    <?php if (have_posts()) :while (have_posts()) : the_post(); ?>
    <div class="content <?php echo $sidebar; ?>">

        <!-- Views Counter -->
        <?php DooPlayViews::Meta($post->ID); ?>

        <!-- Custom Video Player -->
        <?php doo_custom_video_player($post->ID, 'tv'); ?>

        <div id="dktczn-player" class="dktczn-player-container"></div>

        <!-- Heading Info Season -->
        <div class="sheader">
        	<div class="poster">
        		<a href="<?php echo get_permalink($tvshow); ?>">
        			<img src="<?php echo dbmovies_get_poster($post->ID,'medium'); ?>" alt="<?php the_title(); ?>">
        		</a>
        	</div>
        	<div class="data">
        		<h1><?php echo dbmovies_title_tags($title_opti, $title_data); ?></h1>
        		<div class="extra">
        			<?php if($d = doo_isset($postmeta,'air_date')) echo '<span class="date">'.doo_date_compose($d,false).'</span>'; ?>
        		</div>
        		<?php echo do_shortcode('[starstruck_shortcode]'); ?>
        		<div class="sgeneros">
        			<a href="<?php echo get_permalink($tvshow); ?>"><?php echo get_the_title($tvshow); ?></a>
        		</div>
        	</div>
        </div>

        <!-- Single Post Ad -->
        <?php if($adsingle) echo '<div class="module_single_ads">'.$adsingle.'</div>'; ?>

        <!-- Content and Episodes list -->
        <div class="sbox">
            <?php if(get_the_content()){ ?>
            <div class="wp-content" style="margin-bottom: 10px;">
        	    <?php the_content(); ?>
        	</div>
            <?php } ?>
            <?php get_template_part('inc/parts/single/listas/seasons'); ?>
        </div>

        <!-- Season social links -->
    	<?php doo_social_sharelink($post->ID); ?>

        <!-- Season comments -->
        <?php get_template_part('inc/parts/comments'); ?>

    </div>
    <!-- End Post-->
    <?php endwhile; endif; ?>


    <!-- Season sidebar -->
    <div class="sidebar <?php echo $sidebar; ?> scrolling">
    	<?php dynamic_sidebar('sidebar-seasons'); ?>
    </div>


</div>
<!-- End Single -->
