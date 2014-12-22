<?php
/*
   Plugin Name: Blow Link
   Version: 0.2
   Plugin URI: http://tzangms.com/
   Description: Find the right post from various URL pattern
   Author: tzangms
   Author URI: http://tzangms.com/
   License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

   License:
   ==============================================================================
   Copyright (c) 2014 Ming Hsien Tseng <tzangms@gmail.com>

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

function blow_link() {
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
add_action('wp', 'blow_link', 1);

?>
