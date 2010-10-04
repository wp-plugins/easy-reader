<?php

function easy_reader_menu_init(){
	add_submenu_page('options-general.php','Easy Reader Settings','Easy Reader', 'administrator', 'easy-reader', 'easy_reader_settings_page');
	
}
add_action('admin_menu', 'easy_reader_menu_init');


function easy_reader_admin_init(){
	// TODO check that the user is in the right part of the admin
	if(is_admin()){
		wp_enqueue_script('easyreader-settings-page',WP_PLUGIN_URL.'/easy-reader/admin/js/settings-page.js','jquery',false, true);
	}
}
add_action('init', 'easy_reader_admin_init');

function easy_reader_settings_page(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	global $easy_reader_colors;
	
	// Options for the share button filter
	$filter_setting_options = array(
		'insert' => array(
			'never' => __('Never'),
			'single' => __('Single Pages'),
			'all' => __('All Pages'),
		),
		'position' => array(
			'top' => __('Top of Post'),
			'bottom' => __('Bottom of Post')
		),
		'align' => array(
			'left' => __('Left Align'),
			'right' => __('Right Align'),
			'center' => __('Center Align'),
		),
		'color' => array(
			'grey' => __('Grey'),
			'green' => __('Green'),
			'blue' => __('Blue'),
		),
	);
	
	// Titles for those options
	$filter_setting_titles = array(
		'insert' => 'Insert Easy Reader Button',
		'position' => 'Button Position',
		'align' => 'Button Align',
		'color' => 'Button Color',
	);
	
	// And descriptions for those options
	$filter_setting_descriptions = array(
		'insert' => 'When to insert the easy reader button into your post.',
	);
	
	// The available share icons
	$icons = array(
		'rss',
		'bebo', 'delicious', 'digg', 'facebook', 'google', 'myspace',
		'orkut', 'reddit', 'stumbleupon', 'technorati', 'twitter',
	);
	
	$message = null;
	
	if(!empty($_REQUEST['submit']) && !wp_verify_nonce($_POST['_nonce'], 'easyreader-settings')){
		$message = 'Security failure. Please try again.';
	}
	elseif(!empty($_REQUEST['submit'])){
		// The user sent a response, lets check what it's all about
		
		// Update all the feed icon settings
		$enabled_icons = array();
		foreach ($icons as $icon){
			if($_REQUEST['share_button_'.$icon] == 'enabled' && in_array($icon, $icons)){
				$enabled_icons[] = $icon;
			}
		}
		update_option('easyreader-share-icons',$enabled_icons);
		
		// Update the content filter settings
		$filter_settings = array();
		foreach($_POST as $name => $val){
			if(substr($name,0,8) == 'setting_'){
				$filter_settings[substr($name,8)] = $val;
			}
		}
		update_option('easyreader-filter-settings', $filter_settings);
		$message = 'Settings updated.';
	}
	
	include(dirname(__FILE__).'/settings-page.php');
}