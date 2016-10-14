=== WP News and Scrolling Widgets  ===
Contributors: wponlinesupport, anoopranawat
Tags: wponlinesupport, wordpress news plugin, main news page scrolling , wordpress vertical news plugin widget, wordpress horizontal news plugin widget , Free scrolling news wordpress plugin, Free scrolling news widget wordpress plugin, WordPress set post or page as news, WordPress dynamic news, news, latest news, custom post type, cpt, widget, vertical news scrolling widget, news widget
Requires at least: 3.1
Tested up to: 4.6
Author URI: http://wponlinesupport.com
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an News custom post type, News widget, vertical scrolling news widget to Wordpress.

== Description ==

Every CMS site needs a news section. SP News allows you add, manage and display news, date archives, widget, vertical  news scrolling widget on your website.

View [DEMO](http://wponlinesupport.com/wp-plugin/sp-news-and-scrolling-widgets/) for additional information.

View [PRO DEMO and Features](http://wponlinesupport.com/wp-plugin/sp-news-and-scrolling-widgets/) for additional information.

View [Masonry Add-on](http://wponlinesupport.com/wordpress-plugin-addon/masonry-addon-wp-news-widgets/) with 12 designs and 7 effects.

> <strong>Have you checked our latest News / Blog Themes?</strong><br>
>
> * 100% Multilanguage.
> * Fully Responsive.
> * Multiple layouts.
> * 3 Featured Posts grid and slider designs.
> * Google Fonts.
> * Fully Customizable.
> * Easy Installation.
>
> View [DEMO and Features](http://wponlinesupport.com/themes/) for additional information.
>

= Important Note For How to Install =
* Please make sure that Permalink link should not be "/news" Otherwise all your news will go to archive page. You can give it other name like "/ournews, /latestnews etc"
* Now you can Display news post with the help of short code :
<code> [sp_news] </code>
* Also you can Display the news post with category wise :
<code> Sports news [sp_news category="category_id"] </code>
* Display News with Grid:
<code>[sp_news grid="2"] </code>
* Also you can Display the news post with Multiple categories wise
<code> Sports news :
[sp_news category="category_id"]
Arts news
[sp_news category="category_id"]
</code>
* **Complete shortcode example:**
<code>[sp_news limit="10" category="category_id" grid="2"
 show_content="true" show_full_content="true" show_category_name="true"
show_date="false" content_words_limit="30" ]</code>
* Comments for the news
* Added Widget Options like Show News date, Show News Categories, Select News Categories.
* Template code :
<code><?php echo do_shortcode('[sp_news]'); ?></code>
* [Check video](https://wordpress.org/plugins/sp-news-and-widget/installation/) How to install.

= Following are News Parameters: =

* **limit :** [sp_news limit="10"] (Display latest 10 news and then pagination).
* **category :**  [sp_news category="category_id"] (Display News categories wise).
* **grid :** [sp_news grid="2"] OR [sp_news grid="list"] (Display News in Grid formats. To display News in list view, Use grid="list").
* **show_date :** [sp_news show_date="false"] (Display News date OR not. By default value is "True". Options are "ture OR false")
* **show_content :** [sp_news show_content="true" ] (Display News Short content OR not. By default value is "True". Options are "ture OR false").
* **show_full_content :** [sp_news show_full_content="true"] (Display Full news content on main page if you do not want word limit. By default value is "false")
* **show_category_name :** [sp_news show_category_name="true" ] (Display News category name OR not. By default value is "True". Options are "ture OR false").
* **content_words_limit :** [sp_news content_words_limit="30" ] (Control News short content Words limt. By default limit is 20 words).

The plugin adds a News tab to your admin menu, which allows you to enter news items just as you would regular posts.

If you are getting any kind of problum with news page means your are not able to see all news items then please remodify your permalinks Structure for example
first select "Default" and save then again select "Custom Structure "  and save.

= Languages Support  : =
* Added translation in German, French (France), Polish languages (Beta)

= Added New Features : =
* Added List view <code>[sp_news grid="list"] </code>
* Added translation in German, French (France), Polish languages (Beta)
* Widget News Scrolling setting page removed and added setting in widget only.
* Added Widget Options like Show News date, Show News Categories, Select News Categories.
* Category wise News <code> Sports news [sp_news category="category_id"] </code>
* Display News with Grid <code>[sp_news grid="2"]</code> and List <code>[sp_news grid="list"]</code>
* Added pagination [sp_news limit="10"]
* Added new shortcode parameters ie **show_content, show_category_name and content_words_limit**
* Added new shortcode parameters **show_date**
* Added shortcode parameter **show_full_content**

= PRO Features : =
> <strong>Premium Version</strong><br>
>
> * Added 2 shortcodes with various parameters.
> <code>[sp_news] and [sp_news_slider]</code>
> * Recent News Slider.
> * Recent News carousel slider.
> * Recent News in Grid view.
> * 6 different types of Latest News widgets.
> * News display with categories.
> * Added 50 stunning and cool layouts.
> * Popular grid slider feature.
> * Custom Read More link for News Post.
> * Create a News Page OR News website.
> * Drag & Drop feature to display News post in your desired order and other 6 types of order parameter.
> * 'Publicize' support with Jetpack to publish your News post on your social network.
> * 100% Multilanguage.
> * Template code :
> <code><?php echo do_shortcode('[sp_news]'); ?> </code>
> <code> <?php echo do_shortcode('[sp_news_slider]'); ?> </code>
>
> View [PRO DEMO and Features](http://wponlinesupport.com/wp-plugin/sp-news-and-scrolling-widgets/) for additional information.
>
> View [Masonry Add-on](http://wponlinesupport.com/wordpress-plugin-addon/masonry-addon-wp-news-widgets/) with 12 designs and 7 effects.
>

= How to install : =
[youtube https://www.youtube.com/watch?v=07IRBn1oXrU]


== Installation ==

1. Upload the 'sp-news-and-widget' folder to the '/wp-content/plugins/' directory.
1. Activate the SP News plugin through the 'Plugins' menu in WordPress.
1. Add and manage news items on your site by clicking on the  'News' tab that appears in your admin menu.
1. Create a page with the any name and paste this short code  <code> [sp_news] </code>.

= How to install : =
[youtube https://www.youtube.com/watch?v=07IRBn1oXrU]



== Frequently Asked Questions ==

= Can I filter the list of news items by date? =

Yes. Just as you can display a list of your regular posts by year, month, or day, you can display news items for a particular year (/news/2013/), month (/news/2013/04/), or day (/news/2013/04/20/).

= Do I need to update my permalinks after I activate this plugin? =

No, not usually. But if you are geting "/news" page OR 404 error on single news then please  update your permalinks to Custom Structure.

= Are there shortcodes for news items? =

Yse  <code> [sp_news] </code>

== Screenshots ==

1. Display News with grid view
2. A complate view with comments
3. Display News with List view
4. Add new news
5. Single News view
6. Widgets
7. Widgets Options

== Changelog ==

= 3.2.8 =
* Fixed image display issue.
* Fixed some css issue.
* Fuxed widget with image issue.

= 3.2.7 =
* Added excerpt functionality in post description.
* Resolved display post content issue.

= 3.2.6 =
* Fixed some css issues
* Updated PRO plugin design page.

= 3.2.5 =
* Fixed some css issues.

= 3.2.4 =
* Added translation in German, French (France), Polish languages (Beta)
* Fixed some bug
* Added 2 new design for pro version

= 3.2.3 =
* Added textdomain
* Widget scrolling setting page renoved and added setting in widget only.
* Fixed some bug

= 3.2.2 =
* Added Pro version
* Fixed some bugs

= 3.2.1 =
* Added new shortcode parameters show_date.
* Fixed some bugs.

= 3.2 =
* Widget Options like Show News date, Show News Categories, Select News Categories.

= 3.1.1 =
* Solved categories bug

= 3.1 =
* Added new shortcode parameters ie show_content, show_category_name and content_words_limit
* Fixed some bug

= 3.0 =
* Display News with List view
* Display News with Grid [sp_news grid="2"]
* Added pagination [sp_news limit="10"]

= 2.2.1 =
* fixed the bug : Shows news on top of static page

= 2.2 =
* Call the news post with shortcode
* Call the news post with category wise

= 2.1 =
* Scroll main page news
* Setting page for enable or disable main page news scrolling
* Setting page for main news page vertical and horizontal news scrolling

= 2.0 =
* Added Vertical and horizontal news scrolling widget with setting page
* New UI designs
* Admin setting page

= 1.0 =
* Initial release
* Adds custom post type for News item
* Adds all and single page templates for news
* Adds Letest news widget
* Adds Vertical news scrolling widget

== Upgrade Notice ==

= 3.2.8 =
* Fixed image display issue.
* Fixed some css issue.
* Fuxed widget with image issue.

= 3.2.7 =
* Added excerpt functionality in post description.
* Resolved display post content issue.

= 3.2.6 =
* Fixed some css issues
* Updated PRO plugin design page.

= 3.2.5 =
* Fixed some css issues.

= 3.2.4 =
* Added translation in German, French (France), Polish languages (Beta)
* Fixed some bug
* Added 2 new design for pro version

= 3.2.3 =
* Added textdomain
* Widget scrolling setting page renoved and added setting in widget only.
* Fixed some bug

= 3.2.2 =
* Added Pro version
* Fixed some bugs

= 3.2.1 =
* Added new shortcode parameters show_date.
* Fixed some bugs.

= 3.2 =
* Widget Options like Show News date, Show News Categories, Select News Categories.

= 3.1.1 =
* Solved categories bug

= 3.1 =
* Added new shortcode parameters ie show_content, show_category_name and content_words_limit
* Fixed some bug

= 3.0 =
* Display News with List view
* Display News with Grid [sp_news grid="2"]
* Added pagination [sp_news limit="10"]

= 2.2.1 =
* fixed the bug : Shows news on top of static page

= 2.2 =
* Call the news post with shortcode
* Call the news post with category wise

= 2.1 =
Scroll main page news
Setting page for enable or disable main page news scrolling
Setting page for main news page vertical and horizontal news scrolling

= 2.0 =
Added Vertical and horizontal news scrolling widget with setting page

= 1.0 =
Initial release
