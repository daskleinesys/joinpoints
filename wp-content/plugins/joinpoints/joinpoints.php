<?php
/**
 * Plugin Name: Joinpoints
 * Plugin URI: https://www.joinpoints.net/
 * Description: The very first joinpoints wordpress plugin.
 * Version: 1.0
 * Author: Thomas Schagerl
 * Author URI: https://socket.games/
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function joinpoints_plugin_options_init() {
	register_setting( 'joinpoints_api_auth', 'joinpoints_api_auth' );
	add_settings_section(
		'joinpoints_main',
		'API Auth',
		'joinpoints_plugin_section_api',
		'joinpoints'
	);
	add_settings_field(
		'joinpoints_app_id',
		'APP-ID:',
		'joinpoints_plugin_setting_app_id',
		'joinpoints',
		'joinpoints_main'
	);
	add_settings_field(
		'joinpoints_access_key',
		'API-KEY:',
		'joinpoints_plugin_setting_access_key',
		'joinpoints',
		'joinpoints_main'
	);
	add_settings_field(
		'joinpoints_secret',
		'Secret:',
		'joinpoints_plugin_setting_secret',
		'joinpoints',
		'joinpoints_main'
	);
}

function joinpoints_plugin_section_api() {
	echo '<p>Get values from <a href="https://www.joinpoints.net/settings/developer" target="_blank">Joinpoints developer page</a>.</p>';
}

function joinpoints_plugin_setting_app_id() {
	$options = get_option('joinpoints_api_auth');
	echo "<input id='joinpoints_app_id' name='joinpoints_api_auth[app_id]' size='32' type='text' value='{$options['app_id']}' />";
}

function joinpoints_plugin_setting_access_key() {
	$options = get_option('joinpoints_api_auth');
	echo "<input id='joinpoints_access_key' name='joinpoints_api_auth[access_key]' size='64' type='text' value='{$options['access_key']}' />";
}

function joinpoints_plugin_setting_secret() {
	$options = get_option('joinpoints_api_auth');
	echo "<input id='joinpoints_secret' name='joinpoints_api_auth[secret]' size='24' type='text' value='{$options['secret']}' />";
}

function joinpoints_plugin_menu() {
	add_options_page(
		'Joinpoints Options',
		'Joinpoints',
		'manage_options',
		'joinpoints',
		'joinpoints_plugin_options_page'
	);
}

function joinpoints_plugin_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
		<div class="wrap">
			<h1>Joinpoints Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields('joinpoints_api_auth'); ?>
				<?php do_settings_sections('joinpoints'); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
}

if ( is_admin() ) {
	add_action( 'admin_menu', 'joinpoints_plugin_menu' );
	add_action( 'admin_init', 'joinpoints_plugin_options_init' );
}

function joinpoints_form( $args = [], $content = null ) {
	wp_enqueue_script(
		'joinpoints',
		plugins_url( '/public/js/joinpoints.js', __FILE__ ),
		[ 'jquery' ],
		false,
		true
	);
	wp_localize_script(
		'joinpoints',
		'joinpoints',
		[ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
	);
	$content = '
        <form
        	method="post"
        	class="joinpoints-form"
        >
        	<input
	            type="text"
	            name="test"
	            placeholder="Test Value"
	            required
        	>
            <button
            	type="submit"
            	class="joinpoints-form__submit"
            	disabled
            >
            	Absenden
            </button>
        </form>
    ';

	return $content;
}

function joinpoints_ajax_handler( $args ) {
	$options = get_option('joinpoints_api_auth');
	$ch  = curl_init();
	$url = 'https://www.joinpoints.net/api-v1/oauth/auth';
	$url .= '?app_id=' . $options['app_id'];
	$url .= '&grant_type=implicit';
	$url .= '&access_key=' . $options['access_key'];
	$url .= '&secret=' . $options['secret'];
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = json_decode( curl_exec( $ch ) );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	wp_send_json( [
		'status'   => 'success',
		'message'  => "hello {$_POST['data']}",
		'response' => [
			'head' => $response,
			'code' => $httpCode,
		],
	] );
	wp_die();
}

add_action( 'wp_ajax_nopriv_jointpoins-form__submit', 'joinpoints_ajax_handler' );
add_action( 'wp_ajax_jointpoins-form__submit', 'joinpoints_ajax_handler' );

add_shortcode( 'joinpoints_form', 'joinpoints_form' );
