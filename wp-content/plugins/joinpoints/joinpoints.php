<?php
/**
 * Plugin Name: Joinpoints
 * Plugin URI: https://www.joinpoints.net/
 * Description: The very first joinpoints wordpress plugin.
 * Version: 1.0
 * Author: Thomas Schagerl
 * Author URI: https://socket.games/
 */

defined('ABSPATH') or die('No script kiddies please!');

function joinpoints_form($args = [], $content = null) {
    wp_enqueue_script(
        'joinpoints',
        plugins_url('/public/js/joinpoints.js', __FILE__),
        [],
        FALSE,
        TRUE
    );
    $content = '
        <form method="post">
            Hello Joinpoints!
            TODO : add form here
            <button type="submit">Absenden</button>
        </form>
    ';
    return $content;
}
add_shortcode('joinpoints_form', 'joinpoints_form');
