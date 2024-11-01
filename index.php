<?php
/*
Plugin Name: Shorten Link Creator
Plugin URI: http://blog.gokhankara.net/wordpress-icin-bit-ly-link-kisaltma-eklentisi/
Description: http://bit.ly Api Fast Shorten Link Creator
Version: 1.0
Author: Gökhan KARA
Author URI: http://www.gokhankara.net
*/


// Plugin Language Add
load_plugin_textdomain('Shorten-Link-Creator', false, dirname(plugin_basename(__FILE__)) . '/langs');
// Plugin Language Add


// Plugin File Get
function shortenlinkcreator_enqueue_style() {
	wp_enqueue_style( 'shortenlink_css', plugins_url( 'assest/css/style.css', __FILE__ ), array(), '1.0.0', false );
	wp_enqueue_style( 'sweetalert_css', plugins_url( 'assest/css/sweetalert.css', __FILE__ ), array(), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'shortenlinkcreator_enqueue_style' );

function shortenlinkcreator_enqueue_script() {
	wp_enqueue_script( 'sweetalert_js', plugins_url( 'assest/js/sweetalert-dev.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'fontawesome_js', plugins_url( 'assest/js/font-awesome.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'shortenlinkcreator_enqueue_script' );
// Plugin File Get




// Plugin User Profile Box Add
function shortenlinkcreator_key( $shortenlinkcreator_key_meta_key ) {
    $shortenlinkcreator_key_meta_key['bitlyusername'] = 'Bit.ly Username'; // Key
	$shortenlinkcreator_key_meta_key['bitlyapikey'] = 'Bit.ly Api Key'; // ID
	
    return $shortenlinkcreator_key_meta_key;
}
add_filter('user_contactmethods','shortenlinkcreator_key',10,1); 
// Plugin User Profile Box Add

/////////////////////////////////////////////////////////////////////////////////////////////
// Plugin Widget Settings Add
function shortenlinkcreator_functions ($args) {
	extract($args);
	$options = get_option('shortenlinkcreator_widget');
	
	if ( current_user_can( 'administrator' )) { 

	$plugintext = __('Shorten with bit.ly!','Shorten-Link-Creator');
	echo '<div id="soldasabit" style="position: fixed; left: 0px; top: 0px; z-index: 1;margin-left:100px; margin-top:100px;">
		<div class="share-box">
		  <input type="checkbox" id="share-menu" class="share-menu-tg"/>
		  <label for="share-menu">
			<div class="fa fa-external-link"></div>
		  </label>
		  <ul class="share-menu">
			<li class="share-menu-item"><a href="javascript:void(0);" class="box-bitly"><i class="fa fa-external-link" aria-hidden="true"></i> '.$plugintext.'</a></li>
			<li class="share-menu-item"><a target="_blank" href="http://www.facebook.com/sharer.php?u='.get_the_permalink().'&amp;t='.get_the_title('').'" class="box-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
			<li class="share-menu-item"><a target="_blank" href="https://twitter.com/intent/tweet?url='.get_the_permalink().';text='.get_the_title('').';" class="box-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
			<li class="share-menu-item"><a target="_blank" href="https://plus.google.com/share?url='.get_the_permalink().'" class="box-google-plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
		  </ul>
		</div>
	</div>';

	
    $username = get_the_author_meta('bitlyusername');
	$apikey = get_the_author_meta('bitlyapikey');
    $url = get_the_permalink('');
	$bitly_url = file_get_contents('http://api.bitly.com/v3/shorten?login='.$username.'&apiKey='.$apikey.'&longUrl='.$url.'');
	preg_match('@"global_hash": "(.*?)"@si',$bitly_url,$hash);
	$bitlynewurl = "http://bit.ly/".$hash[1];
	
	$text1 = __('Quick Link Abbreviation Process','Shorten-Link-Creator');
	$text2 = __('Do you want to quickly shorten the URL address of this page you are currently in with bit.ly?','Shorten-Link-Creator');
	$text3 = __('Yes I want','Shorten-Link-Creator');
	$text4 = __('No, I gave up.','Shorten-Link-Creator');
	$text5 = __('Congratulations!','Shorten-Link-Creator');
	$text6 = __('Shortened Bit.ly URL:','Shorten-Link-Creator');
	$text7 = __('It is cancelled','Shorten-Link-Creator');
	$text8 = __('Transaction Canceled','Shorten-Link-Creator');
	
	// Alert Get Code //
	echo '<script>
	document.querySelector(".share-menu .share-menu-item .box-bitly").onclick = function(){
	swal({
		title: "'.$text1.'",
		text: "'.$text2.'",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "'.$text3.'",
		cancelButtonText: "'.$text4.'",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm){
    if (isConfirm){
      swal("'.$text5.'", "'.$text6.' '.$bitlynewurl.'", "success");
    } else {
      swal("'.$text7.'", "'.$text8.'", "error");
    }
	});
	};</script>';
	// Alert Get Code //
	}
	
}


// Plugin Settings Page Menu Add
add_action('admin_menu', 'shortenlinkcreator_function');
function shortenlinkcreator_function(){
 add_menu_page('Shorten Link Creator','SLink Creator', 'manage_options', 'shortenlinkcreator', 'shortenlinkcreator_settings_functions' , 'dashicons-external');
}
// Plugin Settings Page Menu Add

// Plugin Settings Page Details
function shortenlinkcreator_settings_functions() {
?>
	<div id="my-content-id" style="display:none;">
    <?php add_thickbox(); ?>
	<center>
		<h3><?php echo _e('Installation & Usage Videos','Shorten-Link-Creator');?></h3>
        <iframe width="600" height="315" src="https://www.youtube.com/embed/9bqK6r2d-I4" frameborder="0" allowfullscreen></iframe>
		<p><?php echo _e('Watch the video in full screen and repeat the process gradually. If there is a problem please contact the developer','Shorten-Link-Creator');?>
		<hr/>Support URL : http://blog.gokhankara.net/wordpress-icin-bit-ly-link-kisaltma-eklentisi/</p>
	</center>
	</div>
	
	<div id="screenshots" style="display:none;">
    <?php add_thickbox(); ?>
	<center>
		<h3><?php echo _e('Look at the Operation Screenshot ..','Shorten-Link-Creator');?></h3>
       <p><?php echo '<img src="' . plugins_url( 'assest/img/Shorten-Link-Creator-key.gif', __FILE__ ) . '"/>'; ?></p>
	</center>
	</div>
	
	<div id="welcome-panel" class="welcome-panel">
	<div class="welcome-panel-content">
	<div class="welcome-panel-column">
		<?php echo '<img src="' . plugins_url( 'assest/img/icon-128x128.png', __FILE__ ) . '" > '; ?>
		<h3><?php echo _e('Shorten Link Creator','Shorten-Link-Creator');?>
		<p><?php echo _e('Bit.ly Quick Link Abbreviation Extension','Shorten-Link-Creator');?></p></h3>
		<p><?php echo _e('With this add-on you can shorten the links of all the content pages on your site.','Shorten-Link-Creator');?></p>
		<p><?php echo _e('Please use the following steps carefully to use the plug-in without any problems.','Shorten-Link-Creator');?></p>
		<h3 style="color:red;"><?php echo _e('1. Process * Create Api Key','Shorten-Link-Creator');?></h3>
		<h4><?php echo _e('You activated the plugin and you are now on this page.','Shorten-Link-Creator');?></h4>
		<p><?php echo _e('This page was created with the purpose of informing and routing. Carefully follow the steps to ensure that this add-on works properly.','Shorten-Link-Creator');?></p>
		<h4><?php echo _e('Log in to Bit.ly and make a note of your Api key and User ID','Shorten-Link-Creator');?></h4>
		<a class="button button-secondary" target="_blank" href="https://bitly.com/a/sign_in"><?php echo _e('Log in to Bit.ly','Shorten-Link-Creator');?></a>
		<p><?php echo _e('Log in with your nickname and password for your Bit.ly membership. Follow directions and directions.','Shorten-Link-Creator');?></p>
		<a href="#TB_inline?width=700&height=580&inlineId=screenshots" class="thickbox button button button-primary button-hero load-customize hide-if-no-customize"><?php echo _e('Look at the Operation Screenshot ..','Shorten-Link-Creator');?></a>
		</br></br>
	</div>
	<div class="welcome-panel-column">
		<h3 style="color:red;"><?php echo _e('2. Process * Enter Information Panel','Shorten-Link-Creator');?></h3>
			<h4><?php echo _e('Write your username and your Api Key in the fields in your WordPress Profile. Instead of typing manually, you can take notes directly with Ctrl + C - Ctrl + V','Shorten-Link-Creator');?></h4>
			<p><?php echo _e('Check your details a few times. </br> Your plugin may not work after the least missing or incorrect entry.','Shorten-Link-Creator');?></p>
			<p><a class="button-secondary" target="_blank" href="<?php echo admin_url( "profile.php", "http" ); ?>"><?php echo _e('You can access the profile page here.','Shorten-Link-Creator');?></a></p>
			<?php echo '<img src="' . plugins_url( 'assest/img/Screenshot_2.png', __FILE__ ) . '" style="width: 100%;
  height: auto;"> '; ?>
		<h3 style="color:red;"><?php echo _e('3. Process * Activate Component','Shorten-Link-Creator');?></h3>
			<h4><?php echo _e('Activate the plugin component','Shorten-Link-Creator');?></h4>
			<p><?php echo _e('To use the plugin, please admit and save the plugin component from the components page to the appropriate location. For trouble free use, your theme must be supported. You can visit this page for information.','Shorten-Link-Creator');?></p>
			<p><a class="button-secondary" target="_blank" href="<?php echo admin_url( "widgets.php", "http" ); ?>"><?php echo _e('You can access the components of your site from here.','Shorten-Link-Creator');?></a></p>
	</div>
	<div class="welcome-panel-column">
		<h3 style="color:red;"><?php echo _e('4. Process * Shorten your links freely!','Shorten-Link-Creator');?></h3>
		<h4><?php echo _e('The process is complete, use it for free for life!','Shorten-Link-Creator');?></h4>
		<p><?php echo _e('With this component you will see a builder in the upper left corner of your website. This builder can only be viewed by site administrators. Members and visitors never appear.','Shorten-Link-Creator');?></p>
		<p><a class="button-secondary" target="_blank" href="<?php bloginfo('url'); ?>"><?php echo _e('Check your website.','Shorten-Link-Creator');?></a></p>
		<?php echo '<img src="' . plugins_url( 'assest/img/Screenshot_4.png', __FILE__ ) . '" style="width:100%;
  height:auto;"> '; ?>
		<p><?php echo _e('As you can see from the picture above, the button with the number 1 automatically picks up the link of that page and shorten it by using the bit.ly API to deliver the new link to you.','Shorten-Link-Creator');?>
		</br></br><?php echo _e('In addition to this, social networks are automatically created on Facebook, Twitter and Google+ links.','Shorten-Link-Creator');?></p>
		<a href="#TB_inline?width=700&height=550&inlineId=my-content-id" class="thickbox button button button-primary button-hero load-customize hide-if-no-customize"><?php echo _e('Promotion and Use Videos','Shorten-Link-Creator');?></a>
		</br></br>
		<ul>
			<li><a target="_blank" href="https://profiles.wordpress.org/gokhankara/#content-plugins"><?php echo '<img src="' . plugins_url( 'assest/img/rating.png', __FILE__ ) . '"> '; ?></li>
			<?php echo _e('Do you vote for this rating?','Shorten-Link-Creator');?></a></li>
			<li><a target="_blank" href="https://profiles.wordpress.org/gokhankara/#content-plugins"><?php echo _e('Browse other add-ons','Shorten-Link-Creator');?></a></li>
			<li><a target="_blank" href="http://www.gokhankara.net"><?php echo _e('Visit the Developers Website','Shorten-Link-Creator');?></a></li>
			<li><a target="_blank" href="http://www.gokhankara.net/donate/"><?php echo _e('Donate','Shorten-Link-Creator');?></a></li>
			<li><a target="_blank" href="http://www.gokhankara.net/contact/"><?php echo _e('Communicate for a private job','Shorten-Link-Creator');?></a></li>
		</ul>
	</div>
	</div>
	</div>
	<div class="card pressthis" style="max-width:100% !important">	
		<p><?php echo '<img src="' . plugins_url( 'assest/img/author_logo.png', __FILE__ ) . '" > '; ?> <?php echo _e('Shorten Link Creator','Shorten-Link-Creator');?> | <?php echo _e('Bit.ly Quick Link Abbreviation Extension','Shorten-Link-Creator');?> <a target="_blank" href="http://www.gokhankara.net">Gökhan KARA</a> - gkdesigned@gmail.com</p>
	</div>
<?php }
// Plugin Settings Page Details

function shortenlinkcreator_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}
 register_sidebar_widget('Shorten Link Creator', 'shortenlinkcreator_functions');
 register_widget_control('Shorten Link Creator', 'shortenlinkcreator_control', 400, 300);
}
add_action('plugins_loaded', 'shortenlinkcreator_init');



function shortenlinkcreator_control () {
 $options = $usernewinfo = get_option('shortenlinkcreator_widget');
 if ( $_POST["shortenlinkcreator_functions_submit"] ) {
    $usernewinfo['bitly_username'] = strip_tags(stripslashes($_POST["bitly_username"]));
	$usernewinfo['bitly_apikey'] = strip_tags(stripslashes($_POST["bitly_apikey"]));
	 
  }
if ( $options != $usernewinfo ){
     $options = $usernewinfo;
     update_option('shortenlinkcreator_widget', $options);
  }
$bitly_username = htmlspecialchars($options['bitly_username'], ENT_QUOTES);
$bitly_apikey = htmlspecialchars($options['bitly_apikey'], ENT_QUOTES);
?>
		<div class="widget-content">
			<p><label for="widget-callback-title"><?php echo _e('Visit the link below for information on installing and using the add-on.','Shorten-Link-Creator');?></label></p>
			<p><a class="button-secondary" target="_blank" href="<?php echo admin_url( "options-general.php?page=shortenlinkcreator", "http" ); ?>"><?php echo _e('Browse Shorten Link Creator Settings','Shorten-Link-Creator');?></a></p>
		</div>
			
		<p style="color: red;"><small><?php echo _e('* This plugin setting and the Bit.ly link builder on the site page can only be viewed by administrators','Shorten-Link-Creator');?></small></p>
		<input type="hidden" name="shortenlinkcreator_functions_submit" id="shortenlinkcreator_functions_submit " value="1" /> 
<?php
}
/////////////////////////////////////////////////////////////////////////////////////////////
// // Plugin Widget Settings Add
/////////////////////////////////////////////////////////////////////////////////////////////
?>