=== LH Instant Articles ===
Contributors:      shawfactor
Donate link: 	   https://lhero.org/portfolio/lh-instant-articles/
Tags:              facebook, syndication, instant articles, mobile
Requires at least: 3.0
Tested up to:      4.9
Stable tag:        trunk
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Simply enable Instant Articles for Facebook on your WordPress site. None of the bloat

== Description ==

This plugin is a very simple bloat free way of enabling instant articles. At its most basic it creates a Facebook compliant RSS feed that allows you to publish Instant Articles to Facebook pages. E.G. https://princesparktouch.com/?feed=lh-instant-articles or https://princesparktouch.com/feed/lh-instant-articles/ .

You can and should specify your Facebook page id in the settings screen. You can optionally also include additional post types in the instant articles feed. And by the use of many different hooks you can modify the html outputted in the Instant Articles feed.

It also supports:

* Adding related posts (see faq)
* Adding analytics and tracking (see faq)
* Adding advertisements (see faq)

== Installation ==

= From your WordPress dashboard =
* Visit 'Plugins > Add New'
* Search for 'LH Instant Articles for WP'
* Activate the plugin on your Plugins page
* Go to *Settings* -> *Instant Articles* in the WordPress menu and specify the settings.

= From WordPress.org =
* Download LH Instant Articles
* Upload the uncompressed directory to '/wp-content/plugins/'
* Activate the plugin on your Plugins page
* Go to *Settings* -> *Instant Articles* in the WordPress menu and specify the settings.

== Frequently Asked Questions ==

= Why use this plugin over the the other instant article plugins? =
* Three reasons. It is simpler, it just works, and it integrates perfectly with the other LH plugin.

= How do I change the behaviour of this plugin? =
* Through filters, all of which are commented in the code and will be documented.

= Can I choose what post types are shown in the Instant Article feed? =
* Yes, in the post editor for every post is an option to send to Instant Articles, by default this is set to yes but if want to exclude an post (or custom post type), set it to no.

= What post types are included in the feed? =
* By default just posts are in the feed, but if you wish to publish other posts types (eg pages) as instant articles you can add them via the settings screen.

= How can I add related articles to me instant articles? =
* Install the LH Jetpack Related Posts plugin from the repository. This will work in your instant article feed whether jetpack is installed or not. To set the related articles go the the post edit screen and use the related posts metabox.

= How can I add analytics or tracking? =
Go to settings and add your analytics or tracking code, for inline tracking you must enclose the script tags within an iframe, otherwise include an iframe with a src attribute.

= How can I change the number of feed items? =
The plugin uses the same 'Syndication feeds show the most recent' option which is set for all feeds under: Settings->Reeading 

= How can I include advertisements? =
Go to settings and add your advertisement code, the format is as described in the Facebook Instant Articles Ad Placement documentation. Making sure to include the figure an iframe elements on each advertisement.

= Why is my feed url showing 404 not found? =
It is probably because another plugin is interfering with cron. Therefore you will need to regenerater your permalinks manually. Go to Settings->Permalinks and save changes

== Changelog ==

= 1.00 - September 10, 2016 =
* Initial release

= 1.01 - September 16, 2016 =
* Minor tweaks

= 1.02 - September 27, 2016 =
* Option to include other post types

= 1.03 - October 3, 2016 =
* Settings Api and extra filter
 
= 1.04 - October 16, 2016 =
* Replace non standard tags

= 1.05 - November 06, 2016 =
* Better nesting support

= 1.06 - January 02, 2017 =
* Moved dom cleanup to its own class

= 1.07 - January 10, 2017 =
* Minor fix

= 1.08 - February 20, 2017 =
* Better Settings

= 1.09 - March 20, 2017 =
* Tracking code and better faq

= 1.10 - May 05, 2017 =
* Added Advertisement insertion

= 1.11 - May 16, 2017 =
* Minor code improvement

= 1.12 - May 20, 2017 =
* More compliant code for lower php versions

= 1.13 - May 21, 2017 =
* Proper escaping for old php

= 1.14 - June 06, 2017 =
* Fixed array issue

= 1.16 - June 09, 2017 =
* Ditched settings api and fixed wp_kses

= 1.17 - September 17, 2017 =
* More translational friendly

= 1.18 - December 17, 2017 =
* Minor improvements

= 1.19 - January 18, 2017 =
* Admin bugfix

= 1.20 - February 10, 2017 =
* Added lh_instant_articles_default_option filter