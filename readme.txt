=== Visual Action Hooks ===
Contributors: elpap,codinghabits
Donate link: https://coding-habits.com/
Tags: wordpress, action, hooks, visual, development, guide, universal
Requires at least: 4.7
Tested up to: 5.9
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Detect action hooks added with "do_action" function from any theme or plugin & display them for logged in administrators.

== Description ==

This is a WordPress plugin that helps visualize the position of action hooks in the front-end. It is compatible with any theme or plugin as long as it doesn't use dynamic names for its actions.
This plugin detects once per day all "do_action" (except dynamic ones) functions from .php files in the wp-content/plugins & wp-content/themes folder & highlights their position in the front-end of the website when requested.
Only administrators will be able to see the highlights.

== Installation ==
1. Upload \"visual-action-hooks.zip\" to the \"/wp-content/plugins/\" directory.
2. Activate the plugin through the \"Plugins\" menu in WordPress.
3. Navigate in any front-end page.
4. Click "Toggle visibility" under "Visual Action Hooks" in the top admin bar.
4. Click inside any highlighted area to see the action name.

== Screenshots ==

1. "Toggle visibility" button in top admin bar.
2. Highlighted action hooks.
3. Selected action name displayed.

== Frequently Asked Questions ==

= How do I configure this plugin? =

This plugin needs no configuration after installing. Just install the plugin, activate it & navigate to your desired page & click "Toggle visibility".

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release