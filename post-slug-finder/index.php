<?php

/*
Plugin Name: Post Slug Finder
Plugin URI: http://tzangms.com/
Description: Find the right post from url
Author: tzangms
Author URI: http://tzangms.com/
Version: 0.1
Stable tag: 0.1
License: MIT License

License:
 ==============================================================================
 Copyright (c) 2014 Ming Hsien Tseng <tzangms@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

function post_slug_finder() {
	global $wp_query;
	
	$the_slug = $_SERVER['REQUEST_URI'];

	// TODO: handle slug for various situation

	if ($wp_query->is_404) {
		$args = array(
		  'name' => $the_slug,
		  'post_type' => 'post',
		  'post_status' => 'publish',
		  'numberposts' => 1
		);
		$my_posts = get_posts($args);
		if($my_posts) {
		  $permalink = get_permalink($my_posts[0]->ID);
		  wp_redirect($permalink, 301);
		  exit;
		}

		// redirect to index page, if nothing found
		wp_redirect(get_bloginfo('wpurl'), 301);
		exit;
  	}
}
add_action('wp', 'post_slug_finder', 1);

?>
