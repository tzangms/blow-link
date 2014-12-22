<?php

/*
Plugin Name: Post Slug Finder
Plugin URI: http://tzangms.com/
Description: Find the right post from url
Author: tzangms
Author URI: http://tzangms.com/
Version: 0.2
Stable tag: 0.2
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
	/*
	Allow URL Format:

	1. /<post_id>-<slug>/
	2. /<post_id>/
	3. /<slug>/
	
	*/
	global $wp_query;
	$post_id = null;

	if ($wp_query->is_404) {

 		$url = parse_url($_SERVER['REQUEST_URI']);
	    $the_slug = end(explode('/', rtrim($url['path'], '/')));

	    if (preg_match('/^(\d+)-(.*)/', $the_slug, $matches)) {
		    $post_id = $matches[1];
		    $the_slug = $matches[2];
	    } else if (preg_match('/^(\d+)/', $the_slug, $matches)) {
	    	$post_id = $matches[1];
	    }

		// query post by post_id, and then slug
		if (!is_null($post_id)) {
			$args['post_id'] = $post_id;
			$post = get_post($post_id);
		} else {
			$args = array(
			  'post_type' => 'post',
			  'post_status' => 'publish',
			  'numberposts' => 1,
			  'name' => $the_slug,
			);
			$posts = get_posts($args);
			if ($posts) {
				$post = $posts[0];
			}
		}

		if($post) {
		  $permalink = get_permalink($post->ID);
		  wp_redirect($permalink, 301);
		  exit;
		}

		wp_redirect(get_bloginfo('wpurl'), 301);
		exit;
  	}
}
add_action('wp', 'post_slug_finder', 1);

?>
