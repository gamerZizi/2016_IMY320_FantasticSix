=== If Menu ===
Contributors: andrei.igna
Tags: menu, if, conditional, statements, hide, show, dispaly, roles, nav menu
Requires at least: 4
Tested up to: 4.6
Stable tag: trunk
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show/hide menu items with conditional statements

== Description ==

Simple plugin that adds extra functionality to Menu Items. The plugin will allow to show or hide menu items based on condition statements (Is single page, User is Logged In, etc).

The management is very easy, each menu item will have a "Enable Conditional Logic" check that will allow to select a conditional statement (Example in Screenshots)

Basic conditional statements are included in the plugin, other will be included in future releases or can be added by another plugins or themes.

Example of adding a new conditional statement is described in the FAQ section

== Installation ==

To install the plugin, follow the steps below

1. Upload `if-menu` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable conditional statements for your Menu Items in Appearance -> Menus page

== Frequently Asked Questions ==

= If Menu is broken =

The code for modifying the menu items is limited and if other plugins/themes try to alter the menu items, this plugin will break.

This is an ongoing [issue with WordPress](http://core.trac.wordpress.org/ticket/18584) which hopefully will be fixed in a future release.

Try to use just one plugin that changes the functionality for menu items.


= How can I add a conditinal statement for menu items? =

New conditional statements can be added by any other plugins or themes.

Example of adding a new conditional statement for disaplying/hiding a menu item when current page is a custom-post-type.

`
// theme's functions.php or plugin file
add_filter( 'if_menu_conditions', 'my_new_menu_conditions' );

function my_new_menu_conditions( $conditions ) {
  $conditions[] = array(
    'name'    =>  'If single custom-post-type', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
      return is_singular( 'my-custom-post-type' );
    }
  );

  return $conditions;
}
`

= Where do I find conditional functions? =

WordPress provides [a lot of functions](http://codex.wordpress.org/Conditional_Tags) which can be used to create conditional statements for almost any combination that a theme/plugin developer can think of.

= Who made that really cool icon =

Got the icons from here https://dribbble.com/shots/1045549-Light-Switches-PSD, so giving the credit to Louie Mantia

== Screenshots ==

1. Enable conditional statements for Menu Items

== Changelog ==

= 0.6 =
*Release Date - 27 August 2016*

* Improvement - Dynamic conditions based on default & custom user roles (added by plugins or themes) [thanks Daniele](https://wordpress.org/support/topic/feature-request-custom-roles)
* Improvement - Grouped conditions by User, Page or other types
* Fix - Filter menu items in admin section
* Fix - Better menu items filter saving code

= 0.5 =
*Release Date - 20 August 2016*

* Improvement - Support for WordPress 4.6
* Feature - New condition checking logged in user for current site in Multi Site [requested here](https://wordpress.org/support/topic/multi-site-user-is-logged-in-conditi
on)
* Feature - Added support for multi conditions [thanks for this ideea](https://wordpress.org/support/topic/more-than-one-condition-operators-1)
* Improvement - RO & DE translations

= 0.4.1 =
*Release Date - 13 December 2015*

* Fix - Fixes [issue](https://wordpress.org/support/topic/cant-add-items-to-menu-with-plugin-enabled) with adding new menu items

= 0.4 =
*Release Date - 29 November 2015*

* Improved compatibility with other plugins/themes using a [shared action hook for menu item fields](https://core.trac.wordpress.org/ticket/18584#comment:37)
* Enhancement - show visibility status in menu item titles

= 0.3 =

Small update

* Plugin icon
* Set as compatible with WordPress 4

= 0.2.1 =

Minor fixes

* [Fix](https://twitter.com/joesegal/status/480386235249082368) - Editing menus - show/hide conditions when adding new item (thanks [Joseph Segal](https://twitter.com/joesegal))

= 0.2 =

Update for compatibility with newer versions of WordPress

* [Feature](http://wordpress.org/support/topic/new-feature-power-to-the-conditions) - access to menu item object in condition callback (thanks [BramNL](http://wordpress.org/support/profile/bramnl))
* [Fix](http://wordpress.org/support/topic/save-is-requested-before-leaving-menu-page) - alert for leaving page even if no changes were made for menus (thanks [Denny](http://wordpress.org/support/profile/ddahly))
* Fix - update method in `Walker_Nav_Menu_Edit` to be compatible with newer version of WP
* [Fix](http://wordpress.org/support/topic/bugfix-for-readmetxt) - example in Readme (thanks [BramNL](http://wordpress.org/support/profile/bramnl))

= 0.1 =
* Plugin release. Included basic menu conditional statements
