<?Php
/*
Plugin Name: Handwritten Comments for WordPress
Plugin URI: http://dev.myscript.com
Description: A WordPress plugin that allows users to use handwriting in comment forms. To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for <a href="https://dev.myscript.com/pricing/cdk/" target="_blank">CDK Starter pack</a> to get an API key, and 3) Go to your MyScript WP Forms configuration page, and save your API key.
Version: 0.4
Author: Jean-François MORICE
Author URI: http://dev.myscript.com
*/

// patch URL
define('myscript_iframe_BASENAME', plugin_basename(__FILE__));
define('myscript_iframe_DIR_URL', plugins_url('', myscript_iframe_BASENAME));

 // Javascript and CSS files: jQuery, Bower component (myscript techno), plugin jquery and plugin css rules
function myscript_method_iframe() {
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

add_action( 'wp_enqueue_scripts', 'myscript_method_iframe' );
 
 // Create shortcode [MyScriptInput] 
add_shortcode('MyScriptInput','MyScriptInput'); 

function MyScriptInput() {
	
	// MyScript parameters stocked in database
    $myscript_iframe_options = get_option('myscript_iframe_plugin_options');
 
    $applicationkey = $myscript_iframe_options['myscript_iframe_applicationkey'];
    $hmackey = $myscript_iframe_options['myscript_iframe_hmackey'];
	// Default language
    $myscript_iframe_language_default = $myscript_iframe_options['myscript_iframe_language_first'];
	// Alternative languages
    $myscript_iframe_language_second = $myscript_iframe_options['myscript_iframe_language_second'];
    $myscript_iframe_language_third = $myscript_iframe_options['myscript_iframe_language_third'];
    $myscript_iframe_language_fourth = $myscript_iframe_options['myscript_iframe_language_fourth'];
	
	
	$url =  plugin_dir_url( __FILE__ );

	if(($applicationkey!='')&&($applicationkey!='xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')){
	// MyScript input definition
			include('myscript-languages.php'); 	
			$Text = '
			<div class="MyScriptHolder">
				<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required" class="MyScriptTextarea" ></textarea>
				
				<div class="MyScriptLoading">
					<img src="'.$url.'img/loading.gif">
				</div>
				
				<iframe src="'.$url.'handwriting-iframe.php?applicationkey='.$applicationkey.'&hmackey='.$hmackey.'&language='.$myscript_iframe_language_default.'" width="1000" height="350" frameborder="0" scrolling="no" id="IframeMyScript" class="MyScriptFrame" ALLOWTRANSPARENCY="true"></iframe>
				
				
				<div class="MyScriptInstructions">
					Tap here to write
				</div>
				<div class="MyScriptError MyScriptHidden" id="MyScriptError"></div>
				
				<div class="MyScriptAdd MyScriptHidden">
					<input type="button" id="AddButton" value="Add to comment">		
				</div>
				
				<div class="MyScriptBar">
					<img src="'.$url.'flags/'.$myscript_iframe_language_default.'.png" id="MyScriptSelectedLanguage" alt="Selected language" title="Selected language">';
					
				if($myscript_iframe_language_second!=''){
					$Text .='					
					<div class="MyScriptLanguages">
						<img src="'.$url.'flags/'.$myscript_iframe_language_default.'.png" 
						title="'.$main_language[$myscript_iframe_language_default].'" 
						class="'.$myscript_iframe_language_default.' MyScriptHidden">
						
						<img src="'.$url.'flags/'.$myscript_iframe_language_second.'.png" 
						title="'.$main_language[$myscript_iframe_language_second].'" 
						class="'.$myscript_iframe_language_second.'">';
						
						if($myscript_iframe_language_third!=''){
							$Text .='<img src="'.$url.'flags/'.$myscript_iframe_language_third.'.png" 
							title="'.$main_language[$myscript_iframe_language_third].'" 
							class="'.$myscript_iframe_language_third.'">';
							
							if($myscript_iframe_language_fourth!=''){
								$Text .='<img src="'.$url.'flags/'.$myscript_iframe_language_fourth.'.png" 
								title="'.$main_language[$myscript_iframe_language_fourth].'" 
								class="'.$myscript_iframe_language_fourth.'">';
							}
						}
					$Text .='</div>';
				}
				$Text .='</div>
			</div>
			<div id="MyScriptSignature"><a href="https://dev.myscript.com" target="_blank" title="MyScript Dev Portal"><img src="'.$url.'img/powered-by-myscript.png" alt="Powered by MyScript"></a></div>';
	}
	else $Text = '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea>';
	
	return $Text;

}

if ( !function_exists('myscript_manage_default_fields')) {
   function myscript_manage_default_fields( $default ) {
 
		// Update of the comment field with MyScript Techno inclusion
		$default['comment_field'] = '
		<p class="comment-form-comment">
			<label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required">*</span></label>'. 
			do_shortcode('[MyScriptInput]').
		'</p>';
		
      return $default;
   }
}

add_filter( 'comment_form_defaults', 'myscript_manage_default_fields');


function myscript_iframe_plugin() {
    // Rappel option 
}
 
 // Create fields for application key and hmac key in settings
function myscript_iframe_applicationkey_html() {
    $myscript_iframe_options = get_option('myscript_iframe_plugin_options');
    echo "<input name='myscript_iframe_plugin_options[myscript_iframe_applicationkey]' type='text' value='{$myscript_iframe_options['myscript_iframe_applicationkey']}' style='width: 280px;'/>";
	
}
function myscript_iframe_hmackey_html() {
    $myscript_iframe_options = get_option('myscript_iframe_plugin_options');
    echo "<input name='myscript_iframe_plugin_options[myscript_iframe_hmackey]' type='text' value='{$myscript_iframe_options['myscript_iframe_hmackey']}' style='width: 280px;'/>";
	
}

// Manage the default language in settings
function myscript_iframe_language_html() {
	
    $myscript_iframe_options = get_option('myscript_iframe_plugin_options');
	// Languages inclusion
	include('myscript-languages.php'); 	

	?>
<table id="myscript_language_table">
<tr style="display:block"><td>
       <strong>Default</strong>:
       </td><td>
        <select id='language_code' name='myscript_iframe_plugin_options[myscript_iframe_language_first]'>
	<?php
		foreach($main_language as $code => $label) :
		if( $code == $myscript_iframe_options['myscript_iframe_language_first'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
		echo "<option {$selected} value='{$code}'>{$label}</option>";
		endforeach; 
	?>
	</select>
</td></tr>
<tr style="display:block"><td>
       Second:
        </td><td>
       <select id='language_code_second' name='myscript_iframe_plugin_options[myscript_iframe_language_second]'>
	<?php
		foreach($second_languages as $code => $label) :
		if( $code == $myscript_iframe_options['myscript_iframe_language_second'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
		echo "<option {$selected} value='{$code}'>{$label}</option>";
		endforeach; 
	?>
	</select>
</td></tr>
<tr id="third_language_line" <?php if($myscript_iframe_options['myscript_iframe_language_second']=='') echo 'style="display:none"'; else echo 'style="display:block"'; ?>><td>
       Third:
       </td><td>
        <select id='language_code_third' name='myscript_iframe_plugin_options[myscript_iframe_language_third]'>
	<?php
		foreach($second_languages as $code => $label) :
		if( $code == $myscript_iframe_options['myscript_iframe_language_third'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
		echo "<option {$selected} value='{$code}'>{$label}</option>";
		endforeach; 
	?>
	</select>
</td></tr>
<tr id="fourth_language_line" <?php if(($myscript_iframe_options['myscript_iframe_language_second']=='')||($myscript_iframe_options['myscript_iframe_language_third']=='')) echo 'style="display:none"'; else echo 'style="display:block"'; ?>><td>
       Fourth:
       </td><td>
        <select id='language_code_fourth' name='myscript_iframe_plugin_options[myscript_iframe_language_fourth]'>
	<?php
		foreach($second_languages as $code => $label) :
		if( $code == $myscript_iframe_options['myscript_iframe_language_fourth'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
		echo "<option {$selected} value='{$code}'>{$label}</option>";
		endforeach; 
	?>
	</select>
</td></tr>
</table>
	<?php
}


// All parameters used in WordPress
function myscript_iframe_register_settings_and_fields() {
 
    // $option_group, $option_name, $sanitize_callback
    register_setting('myscript_iframe_plugin_options','myscript_iframe_plugin_options');
 
    // $id, $title, $callback, $page
    add_settings_section('myscript_iframe_plugin_main_section', 'Main Settings', 'myscript_iframe_plugin', __FILE__);
 
    // $id, $title, $callback, $page, $section, $args
    add_settings_field('myscript_iframe_applicationkey', 'applicationkey: ', 'myscript_iframe_applicationkey_html', __FILE__, 'myscript_iframe_plugin_main_section');
    add_settings_field('myscript_iframe_hmackey', 'hmackey: ', 'myscript_iframe_hmackey_html', __FILE__, 'myscript_iframe_plugin_main_section');
 
    // $id, $title, $callback, $page, $section, $args
    add_settings_field(
		'myscript_iframe_language', 
		'Available languages: ', 
		'myscript_iframe_language_html',
		__FILE__, 
		'myscript_iframe_plugin_main_section'
	);

}
 
add_action('admin_init', 'myscript_iframe_register_settings_and_fields');

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

// Option page for plugin in administration -- called MyScript WP Forms in settings
function myscript_iframe_options_page_html() {

	echo '
	<div class="wrap">
		<h2>MyScript WP Forms Parameters</h2>
		<p>This plugin has been created to use <a href="https://dev.myscript.com/" target="_blank">MyScript</a> Cloud technology.<br>
		To enable it, sign up for <a href="https://dev.myscript.com/pricing/cdk/" target="_blank">CDK Starter pack</a>.<br>
		You will then access to <a href="https://cloud.myscript.com" target="_blank" title="MyScript Cloud dashboard">MyScript Cloud dashboard</a> to get an API key.<br>
		There, don\'t forget to create an application filter with <b>'.protocol().'://'.$_SERVER['SERVER_NAME'].'</b> as referer to protect it.
		</p>
		<form method="post" action="options.php" enctype="multipart/form-data">';
	// $option_group
	settings_fields( 'myscript_iframe_plugin_options' );
	
	// $page 
	do_settings_sections( __FILE__ );
	
	echo '   
		<p class="submit">
			<input type="submit" class="button-primary" name="submit" value="Save Changes">
		</p>
		</form>
		<p>
		</p>
	</div>';
}


// Activation dans le menu d'administration
function myscript_iframe_options_init() {
    // page_title,  menu_title, capability, menu_slug, function
    add_options_page('MyScript WP Forms', 'MyScript WP Forms', 'administrator', __FILE__, 'myscript_iframe_options_page_html');
}
add_action('admin_menu', 'myscript_iframe_options_init');

// Activation et vérification des paramètres si ils existent.
function myscript_iframe_activate() {
    $defaults = array(
                    'myscript_iframe_applicationkey' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                    'myscript_iframe_hmackey' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                    'myscript_iframe_language_first' => 'en_US',
                    'myscript_iframe_language_second' => '',
                    'myscript_iframe_language_third' => '',
                    'myscript_iframe_language_fourth' => ''
                );  
 
  if(get_option('myscript_iframe_plugin_options')) return;
 
  add_option( 'myscript_iframe_plugin_options', $defaults );
}
 
register_activation_hook( __FILE__, 'myscript_iframe_activate' );

 
// Setting menu directly added in extension page
function myscript_plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=handwritten-comments-for-wordpress/handwritten-comments-for-wordpress.php">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'myscript_plugin_settings_link' );

// Css added to the admin panel
function myscript_admin_css() {
	$admin_handle = 'admin_css';
	$admin_stylesheet = plugin_dir_url( __FILE__ ).'css/myscript-admin.css';
	wp_enqueue_style( $admin_handle, $admin_stylesheet );
}
add_action('admin_print_styles', 'myscript_admin_css', 100 );

// Javascript added to the admin panel
/**
 * Proper way to enqueue scripts and styles
 */
function myscript_js() {
	wp_enqueue_script( 'myscript-admin', plugin_dir_url( __FILE__ ).'js/myscript-admin.js', array(), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'myscript_js' );
