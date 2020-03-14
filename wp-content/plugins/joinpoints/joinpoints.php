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
	wp_send_json([
		'status' => 'success',
		'message' => "hello {$_POST['data']}",
	]);
	wp_die();
}

add_action( 'wp_ajax_nopriv_jointpoins-form__submit', 'joinpoints_ajax_handler' );

add_shortcode( 'joinpoints_form', 'joinpoints_form' );
