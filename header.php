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

// Options
$hcod = get_option('_dooplay_header_code');
$regi = doo_is_true('permits','eusr');
$acpg = doo_compose_pagelink('pageaccount');
$fvic = doo_compose_image_option('favicon');
$logo = doo_compose_image_option('headlogo');
$toic = doo_compose_image_option('touchlogo');
$logg = is_user_logged_in();
$bnme = get_option('blogname');
$styl = dooplay_get_option('style');
$colr = dooplay_get_option('maincolor');
$colr = ($colr) ? $colr : '#408bea';
$ilgo = ($styl == 'default') ? 'dooplay_logo_dark' : 'dooplay_logo_white';
$logo = ($logo) ? "<img src='{$logo}' alt='{$bnme}'/>" : "<img src='".DOO_URI."/assets/img/brand/{$ilgo}.svg' alt='{$bnme}'/>";
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<?php if($toic) echo "<link rel='apple-touch-icon' href='{$toic}'/>\n"; ?>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php dooplay_meta_theme_color($styl); ?>
<?php if($fvic) echo "<link rel='shortcut icon' href='{$fvic}' type='image/x-icon' />\n"; ?>
<?php get_template_part('inc/doo_seo'); ?>
<?php if(is_single()) { doo_facebook_image("w780", $post->ID); } ?>
<?php wp_head(); ?>
<?php echo stripslashes($hcod); ?>
</head>
<body <?php body_class(); ?>>
<?php if(is_single() && is_user_logged_in()) { ?>
<div class="dtloadpage">
	<div class="dtloadbox">
		<span><i class="icons-spinner9 loading"></i> <?php _d('Generating data..'); ?></span>
		<p><?php _d('Please wait, not close this page to complete the upload'); ?></p>
	</div>
</div>
<?php } ?>
<div id="dt_contenedor">
<header id="headr" class="main">
	<div class="hbox">
		<div class="fix-hidden">
			<div class="logo">
				<a href="<?php echo esc_url(home_url()); ?>"><?php echo $logo; ?></a>
			</div>
			<div class="head-main-nav">
				<?php wp_nav_menu(array('theme_location'=>'header','menu_class'=>'main-header','menu_id'=>'main_header','fallback_cb'=>false)); ?>
			</div>
			<div class="headitems <?php if($regi OR $logg) { echo 'register_active'; } ?>">
				<div id="advc-menu" class="search">
					<form method="get" id="searchform" action="<?php echo esc_url(home_url()); ?>">
						<input type="text" placeholder="<?php _d('Search...'); ?>" name="s" id="s" value="<?php echo get_search_query(); ?>" autocomplete="off">
						<button class="search-button" type="submit"><span class="fas fa-search"></span></button>
					</form>
				</div>
				<!-- end search -->
				<?php if($logg) { ?>
				<div class="dtuser">
					<div class="gravatar">
	                    <div class="image">
	                        <a href="<?php echo $acpg; ?>"><?php doo_email_avatar_header(); ?></a>
	                        <?php if (current_user_can('administrator')) { $total = doo_total_count('dt_links','pending'); if($total >= 1) { ?><span><?php echo $total; ?></span><?php } } ?>
	                    </div>
						<a href="#" id="dooplay_signout"><?php _d('Sign out'); ?></a>
					</div>
				</div>
	            <?php } else { if($regi == true) { ?>
				<div class="dtuser">
					<a href="#" class="clicklogin">
						<i class="fas fa-user-circle"></i>
					</a>
				</div>
				<?php } } ?>
				<!-- end dt_user -->
			</div>
		</div>
		<div class="live-search <?php echo (is_rtl()) ? 'rtl' : 'ltr'; ?>"></div>
	</div>
</header>
<div class="fixheadresp">
	<header class="responsive">
		<div class="nav"><a class="aresp nav-resp"></a></div>
		<div class="search"><a class="aresp search-resp"></a></div>
		<div class="logo">
            <a href="<?php echo esc_url( home_url() ); ?>/"><?php echo $logo; ?></a>
        </div>
	</header>
	<div class="search_responsive">
		<form method="get" id="form-search-resp" class="form-resp-ab" action="<?php echo esc_url(home_url()); ?>">
			<input type="text" placeholder="<?php _d('Search...'); ?>" name="s" id="ms" value="<?php echo get_search_query(); ?>" autocomplete="off">
			<button type="submit" class="search-button"><span class="fas fa-search"></span></button>
		</form>
		<div class="live-search"></div>
	</div>
	<div id="arch-menu" class="menuresp">
		<div class="menu">
			<?php if($logg) { ?>
			<div class="user">
				<div class="gravatar">
					<a href="<?php echo $acpg; ?>">
					<?php doo_email_avatar_header(); ?>
					<span><?php _d('My account'); ?></span>
					</a>
				</div>
				<div class="logout">
					<a href="#" id="dooplay_signout"><?php _d('Sign out'); ?></a>
				</div>
			</div>
        <?php } elseif( $regi == true) { ?>
			<div class="user">
				<a class="ctgs clicklogin"><?php _d('Login'); ?></a>
				<a class="ctgs" href="<?php echo $acpg .'?action=sign-in'; ?>"><?php _d('Sign Up'); ?></a>
			</div>
        <?php } ?>
			<?php wp_nav_menu( array('theme_location'=>'header','menu_class'=>'resp','menu_id'=>'main_header','fallback_cb'=>false)); ?>
		</div>
	</div>
</div>
	
	
	<style>
.module .content .items .item .poster{border-radius:10px;
	box-shadow:-5px -5px 14px -4px #390000bf;}
.module{
 background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104, 128, 138, .4) 10%, rgb(0 0 0 / 27%) 40%), linear-gradient(to bottom, rgba(57, 173, 219, .25) 0%, rgba(42, 60, 87, .4) 100%), linear-gradient(135deg, #014506 0%, #390022 50%, #47023b 100%) !important;
	background-attachment:fixed!important;
}
.w_item_a .image{aspect-ratio:16/8;}
.quality-buttons-container {
	background:#222;
	display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    padding: 10px; margin:
}
.quality-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 10px;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
    min-width: 10px;
}

.btn-4k {
    background: linear-gradient(45deg, #00bcd4, #006064);
    border-color: #00bcd4;
    color: #e0f7fa;
}
.btn-br {
    background: linear-gradient(45deg, #7b1fa2, #4a148c);
    border-color: #7b1fa2;
    color: #e1bee7;
}
.btn-av1 {
    background: linear-gradient(45deg, #ff4081, #c51162);
    border-color: #ff4081;
    color: #f8bbd0;
}

#single {
    background: rgb(15 15 15 / 71%);
	backdrop-filter: saturate(180%) blur(22px);}

span.valor {
    font-size: 10px;
}
.dktczn-player-container {
  
  border-radius: 12px;
  margin-bottom: 20px;
  overflow: hidden;
  position: relative;
}

.dktczn-player-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    linear-gradient(135deg, rgba(229, 9, 20, 0.1) 0%, transparent 50%),
    linear-gradient(225deg, transparent 50%, rgba(229, 9, 20, 0.05) 100%);
  pointer-events: none;
  z-index: 1;
}

.dktczn-player {
  width: 100%;
  aspect-ratio: 16 / 9;
  border: none;
  display: block;
  background: #000;
  position: relative;
  z-index: 0;
}

/* Server Selection */
.dktczn-selectors {padding:10px;
  border-radius: 12px;
  border: 1px solid rgba(229, 9, 20, 0.3);
  position: relative;
  overflow: hidden;
        margin-bottom:11px;
}

.dktczn-selectors::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #e50914, #ff0a0a, #e50914);
}

.dktczn-selectors-wrapper {

}

.dktczn-selector-group {
  display: flex;
  flex-direction: row;
  gap: 10px;
justify-content: space-around;
       align-items:center;
}

.dktczn-selector-label {
white-space:nowrap;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #e50914;
}

.dktczn-selector {  
    padding: 4px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.04) 100%);
    border: .142px solid rgba(229, 9, 20, 0.4);
    border-radius: 8px;
    color: red;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s 
cubic-bezier(0.4, 0, 0.2, 1);
}

.dktczn-selector:hover {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%);
  border-color: #e50914;
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(229, 9, 20, 0.2);
}

.dktczn-selector:focus {
  outline: none;
  border-color: #ff0a0a;
  box-shadow: 0 0 20px rgba(229, 9, 20, 0.5), inset 0 0 10px rgba(229, 9, 20, 0.1);
}

.dktczn-selector option {
  background: #1a1a1a;
  color: #fff;
  padding: 12px;
  font-weight: 600;
}

.dktczn-selector option:hover {
  background: #e50914;
  
}

/* Episode Grid Styles */
.dktczn-episodes-container {
    background: linear-gradient(135deg, rgb(85 2 6) 0%, #0f0f0f 100%);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    border-left: 5px solid #e50914;
    box-shadow: 0 15px 20px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(229, 9, 20, 0.25)}

.dktczn-loading,
.dktczn-error {
  text-align: center;
  color: #fff;
  font-size: 16px;
  padding: 60px 40px;
}

.dktczn-error {
  color: #ff6b6b;
}

.dktczn-seasons-header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
  padding-bottom: 25px;
  border-bottom: 2px solid rgba(229, 9, 20, 0.3);
}

.dktczn-season-label {
  color: #fff;
  font-size: 16px;
  font-weight: 700;
  white-space: nowrap;
  text-transform: uppercase;
  letter-spacing: 1px;
        
}

.dktczn-season-dropdown {
  padding: 6px 18px;
  background: linear-gradient(135deg, rgba(229, 9, 20, 0.25) 0%, rgba(229, 9, 20, 0.15) 100%);
  border: 2px solid #e50914;
  border-radius: 8px;
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  font-family: inherit;
  box-shadow: 0 0 20px rgba(229, 9, 20, 0.2);
}

.dktczn-season-dropdown:hover {
  background: linear-gradient(135deg, rgba(229, 9, 20, 0.4) 0%, rgba(229, 9, 20, 0.25) 100%);
  transform: translateY(-3px);
  box-shadow: 0 5px 25px rgba(229, 9, 20, 0.4);
}

.dktczn-season-dropdown:focus {
  outline: none;
  box-shadow: 0 0 25px rgba(229, 9, 20, 0.7), inset 0 0 10px rgba(229, 9, 20, 0.2);
  border-color: #ff0a0a;
}

.dktczn-season-dropdown option {
  background: #2d2d2d;
  color: #fff;
  padding: 12px;
  font-weight: 600;
}


.dktczn-season-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 18px;
  animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dktczn-episode-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
  border: 2px solid rgba(229, 9, 20, 0.2);
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(5px);
       
}

.dktczn-episode-card:hover {
  transform: translateY(-12px);
  border-color: #e50914;
  box-shadow: 
    0 15px 40px rgba(229, 9, 20, 0.3),
    0 0 30px rgba(229, 9, 20, 0.2);
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
}

.dktczn-episode-card.active {
  border-color: #e50914;
  background: linear-gradient(135deg, rgba(229, 9, 20, 0.25) 0%, rgba(229, 9, 20, 0.1) 100%);
  box-shadow: 
    0 0 30px rgba(229, 9, 20, 0.6),
    inset 0 0 20px rgba(229, 9, 20, 0.1);
}

.dktczn-episode-thumbnail {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 aspect ratio */
  overflow: hidden;
  background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
}

.dktczn-episode-thumbnail img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.dktczn-episode-card:hover .dktczn-episode-thumbnail img {
  transform: scale(1.08);
  filter: brightness(0.9);
}

.dktczn-episode-play {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 33px;
  height: 33px;
  background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  opacity: 0;
       
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 10;
  box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
}

.dktczn-episode-card:hover .dktczn-episode-play {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1.1);
}

.dktczn-episode-card.active .dktczn-episode-play {
  opacity: 1;
}

.dktczn-episode-info {
  padding: 10px;
  background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-top: 1px solid rgba(229, 9, 20, 0.2);
}

.dktczn-episode-number {
  color: #e50914;
  font-size: 12px;
  font-weight: 600;
  text-transform: capitalize;

}

.dktczn-episode-title {
  color: #fff;
  font-size: 13px;
  font-weight: 500;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  letter-spacing: 0.3px;
}

/* Responsive */
@media (max-width: 1024px) {
  .dktczn-season-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
  }
iframe{width:100%;aspect-ratio:16/9;}
	.dktczn-title {
    font-size: 28px;
  }
}

@media (max-width: 768px) {
  .dktczn-backdrop {
 
  }

  .dktczn-poster {
    width: 120px;
    height: 180px;
    bottom: 15px;
    left: 15px;
  }

  .dktczn-info {
    padding:15px;
  }

  .dktczn-title {
    font-size: 22px;
    margin-bottom: 17px;
  }

        

  .dktczn-meta span {
    padding: 6px 12px;
  }

  .dktczn-player {
    aspect-ratio: 16 / 9;
  }

  .dktczn-episodes-container {
    padding: 20px;
  }

  .dktczn-seasons-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .dktczn-season-dropdown {
    width: 100%;
  }

  .dktczn-season-grid {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
  }

  .dktczn-episode-card:hover {
    transform: translateY(-8px);
  }

  
}

@media (max-width: 480px) {
  .dktczn-backdrop {
  
  }

  .dktczn-poster {
    width: 90px;
    height: 135px;
    bottom: 10px;
    left: 10px;
  }

  .dktczn-info {
    padding: 10px;
  }

  .dktczn-title {
    font-size: 18px;
  }

  .dktczn-overview {
    font-size: 13px;
  }

  .dktczn-season-grid {
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
  }

  .dktczn-episode-card:hover {
    transform: translateY(-4px);
  }

  .dktczn-episodes-container {
    padding: 15px;
  }

 
  .dktczn-selectors-wrapper {
    gap: 15px;
  }
}

 html{
  scroll-behavior: smooth;
}.ad-blocker-toggle { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer; user-select: none; }
.ad-blocker-toggle{white-space:nowrap;}
.dl-wrap{
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
}

.dl-btn{
  padding:12px 26px;
  border-radius:8px;
  font-weight:700;
  text-decoration:none;
  color:#062800;
  background:linear-gradient(135deg,#a3ff12,#22c55e);
  box-shadow:0 6px 18px rgba(34,197,94,.45);
  transition:.25s ease;
}

.dl-btn:hover{
  transform:translateY(-2px) scale(1.03);
  box-shadow:0 10px 26px rgba(34,197,94,.65);
}

.dl-btn-alt{
  background:linear-gradient(135deg,#ccff00,#16a34a);
}.dktczn-play-overlay iframe{width:100%;aspect-ratio:16/9;}
..dktczn-control-btn{margin:10px auto;}
.dktczn-play-overlay iframe {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0e36e97a 0%, #9004e799 100%);
    border-radius: 12px;
    overflow: hidden;
}
.person img{border-radius:10%;}
/* LEFT TOP RED GLOW */
.dktczn-play-overlay::before {
    content: "";
    position: absolute;
    top: -55px;
    left: -55px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(255,40,40,0.45), transparent 70%);
    filter: blur(18px);
    pointer-events: none;
}

/* RIGHT BOTTOM RED GLOW */
.dktczn-play-overlay::after {
    content: "";
    position: absolute;
    bottom: -55px;
    right: -55px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(255,40,40,0.45), transparent 70%);
    filter: blur(18px);
    pointer-events: none;
}
.dktczn-play-overlay {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0e36e97a 0%, #9004e799 100%);
    border-radius: 12px;
    overflow: hidden;
}

/* LEFT TOP RED GLOW */
.dktczn-play-overlay::before {
    content: "";
    position: absolute;
    top: -55px;
    left: -55px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(255,40,40,0.45), transparent 70%);
    filter: blur(18px);
    pointer-events: none;
}

/* RIGHT BOTTOM RED GLOW */
.dktczn-play-overlay::after {
    content: "";
    position: absolute;
    bottom: -55px;
    right: -55px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(255,40,40,0.45), transparent 70%);
    filter: blur(18px);
    pointer-events: none;
}

.sheader .poster img {
    box-shadow: 0 10px 15px -7px #5d2702;
    border: 2px solid #2f86d942;
    border-radius: 10px;
		} .dktczn-control-btn{margin:15px auto;}
		.poster span.valor{text-shadow:1px 1px 1px #000;
}
.module .content .items .item .poster .rating{
    background: linear-gradient(45deg, #0000006e, #4caf50);
    border: .4px solid #4caf50;
    border-radius: 10px;
	font-size:10px;
    padding: 0px 4px;}
.module .content .items .item .poster .rating:before{margin-left:1px; font-weight:300; font-size:9px;}
  
</style>
<div id="contenedor">
  <div id="domainNotice" class="domain-notice" data-nosnippet=""> ✨ Get Instant Notifications for New Uploads <a href="#" target="_blank" rel="nofollow noopener"> Join Telegram!</a> <span class="close-notice" onclick="hideNotice()">×</span></div><style>.domain-notice{background:#1c1c1c;color:#f0f0f0;padding:15px 20px;text-align:center;font-size:15px;border-bottom:1px solid #333;box-shadow:0 2px 10px rgba(255,255,255,0.05);position:relative;z-index:9;font-family:'Segoe UI',sans-serif}.domain-notice a{color:#00bcd4;font-weight:600;text-decoration:underline}.close-notice{position:absolute;right:15px;top:10px;cursor:pointer;font-size:18px;color:#f0f0f0;font-weight:bold;transition:color 0.2s}.close-notice:hover{color:#ff5252}</style><script>function hideNotice() {document.getElementById('domainNotice').style.display = 'none';const now = new Date().getTime();localStorage.setItem('domainNoticeDismissed', now);}(function checkNotice() {const dismissedAt = localStorage.getItem('domainNoticeDismissed');const now = new Date().getTime();const oneDay = 24 * 60 * 60 * 1000;if (dismissedAt && now - dismissedAt < oneDay) {document.getElementById('domainNotice').style.display = 'none';}})();</script>
	
	<!-- HTML for Quality Buttons --><div class="quality-buttons-container"> <a href="/genres/action/" class="quality-btn btn-4k"><span class="btn-desc">Ultra HD Quality</span> </a> <a href="/genres/drama/" class="quality-btn btn-br"> <!--<span class="btn-label">BluRay Remix</span>--> <span class="btn-desc">Enhanced Edition</span> </a> <a href="/genres/crime/" class="quality-btn btn-av1"> <!--<span class="btn-label">AV1</span>--> <span class="btn-desc">Next-gen Encoding</span> </a> </div>
<?php if(!$logg) DooAuth::LoginForm(); ?>
