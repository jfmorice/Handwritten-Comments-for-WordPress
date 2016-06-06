<?Php
/*
Plugin Name: Handwritten Comments for WordPress
Plugin URI: http://dev.myscript.com
Description: A WordPress plugin that allows users to use handwriting in comment forms. To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for <a href="https://dev.myscript.com/pricing/cdk/" target="_blank">CDK Starter pack</a> to get an API key, and 3) Go to your Handwritten Comments for WordPress configuration page, and save your API key.
Version: 0.3
Author: Jean-François MORICE
Author URI: http://dev.myscript.com
*/

// patch URL
define('handwriting_iframe_BASENAME', plugin_basename(__FILE__));
define('handwriting_iframe_DIR_URL', plugins_url('', handwriting_iframe_BASENAME));

function Available_languages(){
    $languages = array(
                    'en_US' => 'English',
                    'fr_FR' => 'French',
                    'es_ES' => 'Spanish',
                    'zh_CN' => 'Chinese'
                );
	return $languages;
}

 // Javascript and CSS files: jQuery, Bower component (MyScript techno), plugin jquery and plugin css rules
function handwriting_method_iframe() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script(
		'myscript-techno',
		plugins_url( '/bower_components/webcomponentsjs/webcomponents-lite.js' , __FILE__ )
	);
	wp_enqueue_script(
		'myscript-input',
		plugins_url( '/js/myscript-input.js' , __FILE__ )
	);
	wp_enqueue_style( 'myscript-css', plugin_dir_url( __FILE__ ).'css/myscript-css.css' );
}

add_action( 'wp_enqueue_scripts', 'handwriting_method_iframe' );

 
 // Create shortcode [MyScriptInput] 
add_shortcode('MyScriptInput','MyScriptInput'); 

function MyScriptInput() {
	
	// MyScript parameters stocked in database
    $handwriting_iframe_options = get_option('handwriting_iframe_plugin_options');
 
    $applicationkey = $handwriting_iframe_options['handwriting_iframe_applicationkey'];
    $hmackey = $handwriting_iframe_options['handwriting_iframe_hmackey'];
    $handwriting_iframe_language_first = $handwriting_iframe_options['handwriting_iframe_language_first'];
	$url =  plugin_dir_url( __FILE__ );

	if(($applicationkey!='')&&($applicationkey!='xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')){
	// MyScript input definition
		$Text = '
			<div class="MyScriptHolder">
				<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required" class="MyScriptTextarea" ></textarea>
				
				<div class="MyScriptLoading">
					<img src="'.$url.'img/loading.gif">
				</div>
				
				<iframe src="'.$url.'handwriting-iframe.php?applicationkey='.$applicationkey.'&hmackey='.$hmackey.'&language='.$handwriting_iframe_language_first.'" width="1000" height="350" frameborder="0" scrolling="no" id="IframeMyScript" class="MyScriptFrame" ALLOWTRANSPARENCY="true"></iframe>
				
				<div class="MyScriptInstructions">
					Tap here to write
				</div>
				<div class="MyScriptError MyScriptHidden" id="MyScriptError"></div>
				
				<div class="MyScriptAdd MyScriptHidden">
					<input type="button" id="AddButton" value="Add to comment">		
				</div>
				
				<div class="MyScriptBar">
					<img src="'.$url.'flags/'.$handwriting_iframe_language_first.'.png" id="MyScriptSelectedLanguage" alt="Selected language" title="Selected language">
					<div class="MyScriptLanguages">';

				    $languages = Available_languages();
		foreach($languages as $code => $label) :
			
			$Text .= '<img src="'.$url.'flags/'.$code.'.png" title="'.$label.'" class="'.$code;
				if( $code == $handwriting_iframe_language_first ) $Text .= ' MyScriptHidden'; 
			$Text .= '">';
			
		endforeach; 

				$Text .= '</div>
				
				</div>
			</div>
			<div id="MyScriptSignature"><a href="https://dev.myscript.com" target="_blank" title="MyScript Dev Portal"><img src="'.$url.'img/powered-by-myscript.png" alt="Powered by MyScript"></a></div>';
	}
	else $Text = '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea>';
	
	return $Text;

}

function handwriting_iframe_plugin() {
    // Rappel option 
}
 
 // Create fields for application key and hmac key in settings
function handwriting_iframe_applicationkey_html() {
    $handwriting_iframe_options = get_option('handwriting_iframe_plugin_options');
    echo "<input name='handwriting_iframe_plugin_options[handwriting_iframe_applicationkey]' type='text' value='{$handwriting_iframe_options['handwriting_iframe_applicationkey']}' style='width: 280px;'/>";
	
}
function handwriting_iframe_hmackey_html() {
    $handwriting_iframe_options = get_option('handwriting_iframe_plugin_options');
    echo "<input name='handwriting_iframe_plugin_options[handwriting_iframe_hmackey]' type='text' value='{$handwriting_iframe_options['handwriting_iframe_hmackey']}' style='width: 280px;'/>";
	
}

// Manage the default language in settings
function handwriting_iframe_language_first_html() {
    $handwriting_iframe_options = get_option('handwriting_iframe_plugin_options');
 
    $languages = Available_languages();
	?>
        <select id='language_code' name='handwriting_iframe_plugin_options[handwriting_iframe_language_first]'>
	<?php
		foreach($languages as $code => $label) :
		if( $code == $handwriting_iframe_options['handwriting_iframe_language_first'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
		echo "<option {$selected} value='{$code}'>{$label}</option>";
		endforeach; 
	?>
	</select>
	<?php
}


// All parameters used in WordPress
function handwriting_iframe_register_settings_and_fields() {
 
    // $option_group, $option_name, $sanitize_callback
    register_setting('handwriting_iframe_plugin_options','handwriting_iframe_plugin_options');
 
    // $id, $title, $callback, $page
    add_settings_section('handwriting_iframe_plugin_main_section', 'Main Settings', 'handwriting_iframe_plugin', __FILE__);
 
    // $id, $title, $callback, $page, $section, $args
    add_settings_field('handwriting_iframe_applicationkey', 'applicationkey: ', 'handwriting_iframe_applicationkey_html', __FILE__, 'handwriting_iframe_plugin_main_section');
    add_settings_field('handwriting_iframe_hmackey', 'hmackey: ', 'handwriting_iframe_hmackey_html', __FILE__, 'handwriting_iframe_plugin_main_section');
 
    // $id, $title, $callback, $page, $section, $args
    add_settings_field('handwriting_iframe_language_first', 'Default language: ', 'handwriting_iframe_language_first_html', __FILE__, 'handwriting_iframe_plugin_main_section');

}
 
add_action('admin_init', 'handwriting_iframe_register_settings_and_fields');

function protocol(){
	if (isset($_SERVER['HTTPS'])) {
		if ($_SERVER['HTTPS'] == 'on') {
			$Protocol = 'https';
		} else {
			$Protocol = 'http';
		}
	} else {
		$Protocol = 'http';
	}
	return $Protocol;
}

// Option page for plugin in administration -- called Handwritten Comments for WordPress in settings
function handwriting_iframe_options_page_html() {

	echo '
	<div class="wrap">
		<h2>Handwritten Comments for WordPress Parameters</h2>
		<p>This plugin has been created to use <a href="https://dev.myscript.com/" target="_blank">MyScript</a> Cloud technology.<br>
		To enable it, sign up for <a href="https://dev.myscript.com/pricing/cdk/" target="_blank">CDK Starter pack</a>.<br>
		You will then access to <a href="https://cloud.myscript.com" target="_blank" title="MyScript Cloud dashboard">MyScript Cloud dashboard</a> to get an API key.<br>
		There, don\'t forget to create an application filter with <b>'.protocol().'://'.$_SERVER['SERVER_NAME'].'</b> as referer to protect it.
		</p>
		<form method="post" action="options.php" enctype="multipart/form-data">';
	// $option_group
	settings_fields( 'handwriting_iframe_plugin_options' );
	
	// $page 
	do_settings_sections( __FILE__ );
	
	echo '   
		<p class="submit">
			<input type="submit" class="button-primary" name="submit" value="Save Changes">
		</p>
		</form>
	</div>';
}


// Activation dans le menu d'administration
function handwriting_iframe_options_init() {
    // page_title,  menu_title, capability, menu_slug, function
    add_options_page('Handwritten Comments for WordPress', 'Handwritten Com', 'administrator', __FILE__, 'handwriting_iframe_options_page_html');
}
add_action('admin_menu', 'handwriting_iframe_options_init');

// Activation et vérification des paramètres si ils existent.
function handwriting_iframe_activate() {
    $defaults = array(
                    'handwriting_iframe_applicationkey' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                    'handwriting_iframe_hmackey' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                    'handwriting_iframe_language_first' => 'en_US'
                );  
 
  if(get_option('handwriting_iframe_plugin_options')) return;
 
  add_option( 'handwriting_iframe_plugin_options', $defaults );
}
 
register_activation_hook( __FILE__, 'handwriting_iframe_activate' );

 
 
 
if ( !function_exists('handwriting_manage_default_fields')) {
   function handwriting_manage_default_fields( $default ) {
 
		// Update of the comment field with MyScript Techno inclusion
		$default['comment_field'] = '
		<p class="comment-form-comment">
			<label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required">*</span></label>'. 
			do_shortcode('[MyScriptInput]').
		'</p>';
		// Deleting allowed tag indication (optional)
		unset($default['comment_notes_after']);
		
		return $default;
   }
}
add_filter( 'comment_form_defaults', 'handwriting_manage_default_fields');



// Setting menu directly added in extension page
function handwriting_plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=handwritten-comments-for-wordpress/handwritten-comments-for-wordpress.php">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'handwriting_plugin_settings_link' );