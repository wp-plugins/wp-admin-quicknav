<?php
/*
Plugin Name: WP Admin Quicknav
Version: 0.3
Description: Adds a simple dropdown box at the top admin edit screens allowing you to quickly jump from one page, post, or custom post type to the next without having to return to the respective listing page.
Author: Rion Dooley
Author URI:  http://github.org/deardooley
Plugin URI: http://github.org/deardooley/wp-admin-quicknav
Text Domain: wp-admin-quicknav


Copyright (c) 2014, Rion Dooley
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of the University of Texas at Austin nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS AS IS
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

class WPAdminQuicknav
{
	public function __construct() {
	}

	public static function admin_init()
	{
		add_action("edit_form_after_title", array('WPAdminQuicknav',"edit_form_after_title"), 10);
	}

	public static function edit_form_after_title()
	{
		$screen = get_current_screen();

		if ($screen->parent_base != 'edit' && $screen->parent_base != 'post') return;

		$type = $screen->post_type;

		if (($options = apply_filters("wp_admin_quicknav_options_$type",array())) === false) return;

		if (empty($options)) {

			$params = array("post_type"=>$type,
										  "suppress_filters"=>false,
										  "posts_per_page"=>-1 );

			if ($type !== "attachment") {
				$params["orderby"] = "title";
				$params["order"] = "ASC";
			}

			$posts = get_posts($params);

			if (count($posts) > 0)
			{
				foreach($posts as $post)
				{
					$options[$post->post_title] = $post->ID;
				}
			}
		}

		if ($options)
		{
			$id = isset($_GET['post']) ? $_GET['post'] : '';

			echo '<select id="wp_admin_quicknav" style="margin-left: 10px;">';
			foreach ($options as $title=>$postid)
			{
				echo sprintf('<option value="%s" %s>%s</option>',
										$postid,
										selected($postid, $id, false),
										$title);
			}
			echo '</select>';
			printf('
<script>
	(function($){
		$("#wp_admin_quicknav").appendTo("#wpbody-content h2:first").change(function(){
			if(v=$(this).val()) {
				console.log(v);
				location.href=("%s".replace("{0}", v));
			}
		});
	}(jQuery))
</script>',str_replace($id, '{0}', get_edit_post_link($id,"&")));
		}
	}
}
add_action("admin_init",array('WPAdminQuicknav',"admin_init"));
