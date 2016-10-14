== Changelog ==

= 1.2.4 June 19 2015 =
* Escapes all necessary inputs, urls, etc.

= 1.2.2 April 1 2014 =
* Fixes error on .org installs without JetPack enabled

= 1.2.1 May 17 2013 =
* Fixed a bug where header and background args were not passed to the registration functions correctly.
* Updated pot file.

= 1.2 May 16 2013 =
* Updated license information.
* Minor style adjustments in preparation for 3.6 compatability.
* Uses small menu on mobile.
* Ensures correct content_width when a sidebar is active by using init hook instead of template_redirect.
* Allows the get_posts() results in content-gallery.php to be cached.
* Uses get_posts() in content-gallery.php instead of get_children() to get image attachments.
* Enqueues scripts and styles via callback.
* Uses a filter to modify the output of wp_title().
* Updated comments in Jetpack compat files to point to live documentation on jetpack.me.

= 1.1 Nov 5 2012 =
* Fix i18n text domains.
* RTL CSS overhaul.
* Create more consistent rules for displaying category and tags on single posts/archive pages.
* Add styling for HTML5 email inputs.
* Make sure attribute escaping occurs after printing.
* PNG image compression.
* Remove loading of $locale.php.
* Fix Google Fonts to check is_ssl().
* Replace #gallery-x ID with .gallery class.
* Set content_width to the proper values for standard page width and full page width to fix tiled galleries.
* Add function to reset content_width if user has activated the right sidebar.
* Updated screenshot for HiDPI support.
* Add Jetpack compatibility file.