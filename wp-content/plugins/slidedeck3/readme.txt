=== SlideDeck 3 Lite Responsive Content Slider ===
Contributors: SlideDeck
Donate link: http://www.slidedeck.com/
Tags: Slider, dynamic, responsive, image gallery, hbwsl, iPad, jquery, media, photo, pictures, plugin, posts, Search Engine Optimized, seo, skinnable, slide, slide show, slider, slideshow, theme, touch support, video, widget, Flickr, Instagram, 500px, RSS, Pinterest, Google+, YouTube, Vimeo, Dailymotion, Picasa, Dribbble, Facebook, NextGEN Gallery
Requires at least: 3.3
Tested up to: 4.3
Stable tag: trunk
License: GPLv3

Create responsive content sliders on your WordPress blogging platform. Manage SlideDeck content and insert them into templates and posts.

== Description ==

= Responsive Content Slider by SlideDeck 3 =
= Easily create responsive content sliders for your WordPress site without code. Use images & text, plus YouTube, Flickr, Pinterest & more =
SlideDeck 3 for WordPress is a responsive slider plugin that lets you easily create content sliders out of almost any content. Connect to a variety of Content Sources like YouTube, Flickr, WordPress posts and Pinterest to create gorgeous, dynamic sliders in a few clicks - no coding is required.

**Requirements:** WordPress 3.3+, PHP5 and higher

**Important Links:**

* [More Details](http://www.slidedeck.com/)
* [Knowledge Base](http://support.slidedeck.com/)


**Lite Version:**
This Lite version of SlideDeck 3 does not include the full set of 14 content sources, like Facebook and NextGEN Gallery, that are available in the premium version. To see all the features available in the premium versions, check out the [live demo server](http://demo.slidedeck.com/wp-login.php).

This plugin is free to use and is not actively supported by the author, but will be monitored for serious bugs that may need correcting.

== Installation ==

1. Upload the `slidedeck3-lite` folder and all its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new SlideDeck from the “SlideDeck » Manage” menu in the control panel sidebar
4. Insert a SlideDeck in your post or page by clicking on the "Embed a SlideDeck" button above the rich text editor in the post/page view.

You can also place a SlideDeck in your template or theme via the PHP command “do_shortcode()”. Just pass the ID of the SlideDeck you want to render. For example:
`<?php echo do_shortcode( "[SlideDeck2 id=1644]" ); ?>`

Where 1644 is the SlideDeck's ID. You can also see this code snippet when you save a new SlideDeck for the first time.

== Screenshots ==

1. The SlideDeck manage view.
2. The editing interface showing the current deck and the top of the lens selection area.
3. One of the many settings page aviable to you. You can tweak dozens of settings for each SlideDeck.
4. Example - Flickr images from ViewsOfIreland.com using the Tool Kit Lens
5. Example - Pinterest images from dwellmedia using the O-Town lens
6. Example - YouTube videos from NativeInstruments using the Reporter Lens

== Frequently Asked Questions ==

== Changelog ==
= 4.1 =
*  Feature Enhancement:Added Predesigned templates for creating slider

= 3.3.5 =
  Bug Fix: Issue related to Image scaling option set to 'Scale propertionally and do not crop' in case of Custom Video as thumbnail is not getting scaled proportionally has been fixed.
  Bug Fix: If Advanced Option as 'Always load SlideDeck JavaScript/CSS on every page?' is set to OFF and SlideDeck is loaded on page only from Widget then SlideDeck is not working properly and js errors occurs has been fixed.  

= 3.3.0 =
*  Added hooks to support new lenses i.e.: Parfocal Lens and Prime Lens.

= 3.2.2 =
*  New Feature : Now you can fetch playlists from YouTube based on your channel Id.

= 3.2.1 =
*  Bug Fix: Fixed a bug related to SlideDeck3 Addon integration. 

= 3.2.0 =
*  New Feature : Now you can add future posts to SlideDecks using Posts source.

= 3.1.0 =
*  Added changes to support slidedeck addon
= 3.0.0 =
* Lite Plugin is available
