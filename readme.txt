=== Ostrichcize ===
Contributors: tollmanz, 10up
Donate Link: http://wordpress.org
Tags: debug, error reporting
Requires at least: 3.3
Tested up to: trunk
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide PHP error reporting for specified plugins or the current theme.

== Description ==

At the first sign of trouble, an ostrich buries its head in the sand. Ostrichcize allows a plugin or theme developer to
bury his or her head in the sand by turning off error reporting for select plugins or the current theme.

If you have ever installed a plugin or worked on a site with a plugin that throws numerous of errors and notices, but do
not have the time to fix the issue, you can turn off those notices with this plugin. By simply filtering the plugin, you
can add to the list of plugins for which no notices will be shown.

To add to this list simply write something like:

<pre><code>
function my_ostrichcized_plugins( $slugs ) {
	$slugs[] = 'debug-bar-cron';
	return $slugs;
}

function my_pre_my_ostrichcized_plugins() {
    add_filter( 'ostrichcized_plugins', 'my_ostrichcized_plugins' );
}

add_action( 'plugins_loaded', 'my_pre_my_ostrichcized_plugins', 1 );
</pre></code>

Note that the filter must be added before any offending code is run in order to redefine the error reporting function
before it is first called. The means that in most cases, this code will need to run from a plugin and not a theme.

To turn off PHP error reporting for a theme, run:

<pre><code>
function my_ostrichcize_theme() {
    add_filter( 'ostrichcize_theme', '__return_true' );
}
add_action( 'plugins_loaded', 'my_ostrichcize_theme', 1 );
</pre></code>

Thanks to Jeremy Felt (@jeremyfelt) for assistance naming the plugin!

== Installation ==

1. Install Ostrichcize if not already installed (http://wordpress.org/extend/plugins/ostrichcize/)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Setup Ostrichcize rules as noted above

== Frequently Asked Questions ==

= Is there a UI to add ostrichcize rules? =

No. At this time, I really only want developer's using this tool. Any WordPress developer that is messing with error
handling should easily be able to make this plugin work. If not, the developer should not be using this tool. Similarly,
users should not be messing with error reporting.

= Can I run this in production? =

You certainly can, but that is not the intent of the tool. It is best to only run this in development.

= What is the use case? =

This plugin is inspired by having installed countless plugins that throw error notices. Many times, these are small,
non-critical errors. Since I often do not have time to fix the errors myself, I allow them to continue to muck up my
error logs or on screen display of errors. I thought it would be nice to have a way to hide these errors so that only
errors due to my custom code are displayed. Ostrichcize allows you to do just that.

== Changelog ==

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.1 =
Initial Release