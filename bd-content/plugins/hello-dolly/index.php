a<?php
/**
 * Plugin Name: Hello Dolly
 * Plugin URI: http://blasdoise.com/hello-dolly/
 * Author: Ibnu Sina
 * Author URI: http://blasdoise.com/author/ibnusina/
 * Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from Hello, Dolly in the upper right of your admin screen on every page.
 * Version: 1.0.0
 * License: GNU General Public License
 * Domain Path: /languages/
 * Text Domain: hello-dolly
 **/

function hello_dolly_get_lyrics() {

	/** These are the lyrics to Hello Dolly */
	$lyrics = "Hello, Dolly
	Well, hello, Dolly
	It's so nice to have you back where you belong
	You're lookin' swell, Dolly
	I can tell, Dolly
	You're still glowin', you're still crowin'
	You're still goin' strong
	We feel the room swayin'
	While the band's playin'
	One of your old favourite songs from way back when
	So, take her wrap, fellas
	Find her an empty lap, fellas
	Dolly'll never go away again
	Hello, Dolly
	Well, hello, Dolly
	It's so nice to have you back where you belong
	You're lookin' swell, Dolly
	I can tell, Dolly
	You're still glowin', you're still crowin'
	You're still goin' strong
	We feel the room swayin'
	While the band's playin'
	One of your old favourite songs from way back when
	Golly, gee, fellas
	Find her a vacant knee, fellas
	Dolly'll never go away
	Dolly'll never go away
	Dolly'll never go away again";

	// Here we split it into lines
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line
	return bdtexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function lyrics() {
	$chosen = hello_dolly_get_lyrics();
	echo "<p id='lyrics'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'lyrics' );

// We need some CSS to position the paragraph
function hello_dolly_css() {
	$x = is_rtl() ? 'left' : 'right';

	echo "
        <style type='text/css'>
            #lyrics {
                float: right;
                padding-$x: 15px;
                padding-top: 5px;		
                margin: 0;
                font-size: 11px;
            }
        </style>
	";
}

add_action( 'admin_head', 'hello_dolly_css' );

?>