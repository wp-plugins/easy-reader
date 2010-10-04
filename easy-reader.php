<?php

/*
Plugin Name: Easy Reader
Plugin URI: http://siteorigin.com/easy-reader/
Description: Gives your readers a nice, easy to read version of your post.
Version: 0.1
Author: Greg Priday
Author URI: http://siteorigin.com/
*/

// For efficiency, only include admin stuff when we're in the admin section
if(is_admin()) require_once(dirname(__FILE__).'/admin/init.php');

function easy_reader_activate(){
	$filter_settings = array(
		'insert' => 'single',
		'position' => 'top',
		'align' => 'right',
		'color' => 'grey',
	);
	add_option('easyreader-filter-settings', $filter_settings);
	add_option('easyreader-share-icons', array('rss', 'digg', 'facebook', 'stumbleupon', 'twitter'));
}
register_activation_hook(__FILE__, 'easy_reader_activate');

function easy_reader_deactivate(){
	delete_option('easyreader-filter-settings');
	delete_option('easyreader-share-icons');
}
register_deactivation_hook(__FILE__, 'easy_reader_deactivate');

function easy_reader_init(){
	// TODO check if all this stuff is actually needed
	if(!is_admin()){
		wp_enqueue_script('jquery');
		wp_enqueue_script('easy-reader',WP_PLUGIN_URL.'/easy-reader/js/reader.js', null, '0.1');
		wp_enqueue_style('easy-reader-css',WP_PLUGIN_URL.'/easy-reader/css/reader.css',null,'0.1','screen');
	}
}
add_action('init', 'easy_reader_init');

function easy_reader_footer(){
	?><script type="text/javascript">EASY_READER_FOLDER = "<?php print addslashes(WP_PLUGIN_URL.'/easy-reader') ?>"</script><?php
}
add_action('wp_footer', 'easy_reader_footer');

function easy_reader_content_filter($content){
	if(defined('EASY_READER_GETPOST')) return $content;
	$filter_settings = get_option('easyreader-filter-settings');
	
	// insert:0 = don't insert. position:2 = insert after title
	if($filter_settings['insert'] == 'never') return $content;
	elseif(($filter_settings['insert'] == 'single' && is_single()) || $filter_settings['insert'] == 'all'){
		// We can insert the button
		switch($filter_settings['align']){
			case 'left': $class = ' easy-reader-align-left'; break;
			case 'right': $class = ' easy-reader-align-right'; break;
			case 'center': $class = ' easy-reader-align-center'; break;
		}
		
		global $easy_reader_colors;
		$button_html = easy_reader_button($filter_settings['color'],'normal', $class, true);
		
		if($filter_settings['position'] == 'top'){
			return $button_html.$content;
		}
		else{
			return $content.$button_html;
		}
	}
	
	return $content;
}
add_filter('the_content', 'easy_reader_content_filter');

/**
 * Outputs a full on button for easy reader. 
 * @param string $color
 * @param string $size
 * @param string $class
 */
function easy_reader_button($color = 'green', $size = 'normal', $class = '', $return = false){
	if(!file_exists(dirname(__FILE__).'/images/buttons/'.$size.'-'.$color.'.png')) return;
	if($return) ob_start();
	
	?>
		<div class="easy-reader-button-holder <?php print $class?>">
			<a href="<?php print WP_PLUGIN_URL ?>/easy-reader/post.php?post_id=<?php the_ID() ?>" onClick="easyReaderClick(this); return false;" rel="nofollow" class="easy-reader-link">
				<img src="<?php print WP_PLUGIN_URL ?>/easy-reader/images/buttons/<?php print $size ?>-<?php print $color?>.png" alt="easy reader" />
			</a>
		</div>
	<?php
	
	if($return) return ob_get_clean();
}

/**
 * Displays a share and enjoy button
 * @param string $type The type of the button
 */
function easy_reader_share_button($type){
	switch($type){
		case 'rss':
			$link = get_bloginfo('rss2_url');
			$title = 'Subscribe';
			break;
		
		case 'bebo':
			$link = 'http://www.bebo.com/c/share?Url='.urlencode(get_permalink()).'&Title='.urlencode(get_the_title());
			$title = 'Share on Bebo';
			break;
		
		case 'delicious':
			$link = "http://del.icio.us/post?url=".urlencode(get_permalink())."&title=".urlencode(get_the_title());
			$title = 'Bookmark on Delicious';
			break;
		
		case 'digg':
			$link = "http://digg.com/submit?phase=2&url=".urlencode(get_permalink())."&title=".urlencode(get_the_title());
			$title = 'Digg This';
			break;
		
		case 'facebook':
			$link = "http://www.facebook.com/sharer.php?u=".urlencode(get_permalink())."&t=".urlencode(get_the_title());
			$title = "Share on Facebook";
			break;
		
		case 'google':
			$link = "http://fusion.google.com/add?feedurl=".urlencode(get_bloginfo('rss2_url'));
			$title = 'Import Into Google Reader';
			break;
		
		case 'myspace':
			$link = "http://www.myspace.com/Modules/PostTo/Pages/?u=".urlencode(get_permalink());
			$title = 'Post On Myspace';
			break;
		
		case 'orkut':
			$link = "http://promote.orkut.com/preview?nt=orkut.com&du=".urlencode(get_permalink())."&tt=".urlencode(get_the_title());
			$title = 'Promote on Orkut';
			break;
		
		case 'reddit':
			$link = "http://reddit.com/submit?&url=".urlencode(get_permalink())."&title=".urlencode(get_the_title());
			$title = "Share on Reddit";
			break;
		
		case 'stumbleupon':
			$link = "http://www.stumbleupon.com/submit?url=".urlencode(get_permalink())."&title=".urlencode(get_the_title());
			$title = "Stumble this";
			break;
		
		case 'technorati':
			$link = "http://technorati.com/faves/?add=".urlencode(get_permalink());
			$title = 'Favorite on Technorati';
			break;
			
		case 'twitter':
			// TODO shorten this and let user add custom message
			$link = "http://twitter.com/home?status=".urlencode(get_permalink());
			$title = "Tweet this";
			break;
	}
	
	?><a href="<?php print $link ?>" title="<?php print esc_attr($title) ?>" rel="nofollow"><img src="<?php print WP_PLUGIN_URL ?>/easy-reader/images/share/<?php print $type?>.png" /></a><?php
}


