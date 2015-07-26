=== WP Admin Quicknav ===
Contributors: Rion Dooley
Tags: admin, quick navigation, productivity
Requires at least: 3.5
Tested up to: 3.9.2
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YUVMERFH5879Q
License: BSD2

Adds a simple quick navigation dropdown box to the top of every admin edit screen.

== Description ==

Adds a simple dropdown box at the top admin edit screens allowing you to quickly jump from one page, post, or custom post type to the next without having to return to the respective listing page.

== Screenshots ==

1. Use the dropdown at the top of the page to quickly navigate between posts, pages, and custom post types.

== Installation ==

1. Upload the extracted archive to `wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu
3. Enjoy!

= Manual Usage =
If you would like to filter the contents of the quicklink box, you can add a custom action for each post type you would like to filter:

`
add_action('wp_admin_quicknav_options_post', 'filter_post_quicknav');
function filter_post_quicknav($options=array())
{
  $params = array("post_type"=>'post',
                  "suppress_filters"=>false,
                  "posts_per_page"=>-1,
                  "orderby"=>'date',
                  "order"=>'ASC');

  $posts = get_posts($params);

  if (count($posts) > 0)
  {
    foreach($posts as $post)
    {
      $options[$post->post_title] = $post->ID;
    }
  }

  return $options;
}

`

You can style the quicknav combo box with css using its custom idenitifier:

`
#wp_admin_quicknav {
  margin-left: 10px;
}
`

== Upgrade Notice ==

Nothing to see here. Move along.

== Frequently Asked Questions ==

= Will this work with my custom post types? =
Yes. It will work with all posts, pages, and custom post types.

== Changelog ==

= 0.2 =
* Fixed php warnings when used on a new post/page

= 0.1 =
* First commit
