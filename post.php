<?php
	define('WP_USE_THEMES', false);
	define('EASY_READER_GETPOST', true);
	
	/** Loads the WordPress Environment and Template */
	require(dirname(__FILE__).'./../../../wp-blog-header.php');
	
	// TODO this should handle post types
	query_posts('posts_per_page=1&p='.$_GET['post_id']);
	the_post();
	
	global $post;
	print_r($post);
	
	header("HTTP/1.1 200 OK");
	header("Status: 200 OK");
	
	// The main reader script
	wp_register_script('easyreader-post',WP_PLUGIN_URL.'/easy-reader/js/post.js');
	wp_register_script('jquery-print',WP_PLUGIN_URL.'/easy-reader/js/jquery.print.js', 'jquery');
	
	$enabled_icons = get_option('easyreader-share-icons');
?>

<html>
<head>
	<title><?php the_title() ?></title>
	<meta name="ROBOTS" value="NOINDEX, NOFOLLOW" />
	<link href="<?php print WP_PLUGIN_URL ?>/easy-reader/css/post.css" type="text/css" rel="stylesheet" media="screen" />
	<link href="<?php print WP_PLUGIN_URL ?>/easy-reader/css/post-print.css" type="text/css" rel="stylesheet" media="print" />
	
	<?php $wp_scripts->print_scripts(array('jquery', 'jquery-print', 'easyreader-post')) ?>
</head>

<body>
	<div id="action-buttons">
		<a href="<?php print the_permalink() ?>" id="close-button">close</a>
		<a href="#" id="print-button">print</a>
	</div>
	
	<div id="easy-reader-content">
		<h1><?php the_title() ?></h1>
		
		<?php the_content(); ?>
	</div>
	
	<?php if(count($enabled_icons) > 0) : ?>
		<div class="share-icons">
			<span>Share and Enjoy</span>
			<?php foreach ($enabled_icons as $icon) {easy_reader_share_button($icon); print ' ';} ?>
		</div>
	<?php endif; ?>
	
</body>
</html>