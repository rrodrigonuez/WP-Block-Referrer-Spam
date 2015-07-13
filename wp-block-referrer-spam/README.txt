=== WP Block Referrer Spam ===
Contributors: barragan
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UGD72JQRCVQM8
Tags: analytics, analytics block, analytics blocker, block analytics, analytics spam, google analytics, google spam, googlespam, block google spam, referrer, referrers, anti-referrers, block referrer, block referrers, referrer bloker, referrers bloker, block referrer spam, spam, spammer, spammers, spam referrer attack, anti-spam, antispam, spam bloker, block spam, spam filter, spambot, security, pingback, trackback, apache, nginx, iis
Requires at least: 3.2
Tested up to: 4.2.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Block 250+ spam sites, such as semalt.com and 4webmasters.org!
Keep your website safe and your Google Analytics statistics clean and accurate.

== Description ==

Keep your WordPress site safe and away from **referrer spam attacks**!

This plugin blocks spam sites and referrer spammers from reaching your website, keeping your Google Analytics statistics clean and accurate. It also improves the overall performance of your site by keeping away these unwanted referrers from your website, which means that your web server won't need to consume resources when a referrer spammer visits your site!


= Why you should use this plugin? =

It is very common to be attacked by **referrer spammers**. Referrer spam consists in making repeated web site requests using a fake referer URL to the site the spammer wishes to advertise.
Sites that publish their access logs, including referrer statistics, will then inadvertently link back to the spammer's site. These links will be indexed by search engines as they crawl the access logs.
This benefits the spammer because the free link improves the spammer site's search engine ranking owing to link-counting algorithms that search engines use.

This is not really harmful for your site or your site's search engine ranking. But it is very annoying when you try to analize your Google Analytics statistics due to big amounts of visits coming from referrer spammers.


= How can I check if my site is being attacked by referrer spammers? =

It is very easy to check if your site is being attacked by referrer spamers. And I bet you that more than likely your site has some suscpicious visits that could be banned using this plugin.

1. Open your Google Analytics account
1. Select the statistics of the site that you want to check
1. Expand `Acquisition` menu
1. Expand `All Traffic` menu
1. Go to `Referrals` menu
1. Underneath the graphic you will see the list of referred traffic
1. If you see weird domains like **trafficmonetize.org**, **webmonetizer.net** or **buttons-for-website.com** it means that your site is suffering **referrer spam attacks**


= Compatibility: =

This plugin is fully compatible with any webserver by default.
Its adaptability ensures that will work with any version of either apache, nginx, iis or lighttpd webserver.

* When apache is used, the plugin adds specific rules into .htaccess file.
* When apache is not used, the plugin automatically recognizes it and uses internal code rules to keep away spammers.

Requires at least WordPress 3.2 and PHP 5.3


= The most updated Referrer Spam List: =

Every week, this plugin updates the list of referrer spammers from [this community-contributed list](https://github.com/piwik/referrer-spam-blacklist) of [referrer spammers](http://en.wikipedia.org/wiki/Referer_spam).


**Development is on Bitbucket: https://bitbucket.org/rrodrigonuez/wp-block-referrer-spam**

Pull requests are more than welcome!


== Installation ==

= From your WordPress dashboard: =

1. Go to the `Plugins > Add New`
1. Search For `WP Block Referral Spam`
1. Install, then Activate it
1. The plugin will update the referrer list automatically every 7 days

= From WordPress.org: =

1. Download `WP Block Referral Spam` plugin
1. Upload `wp-block-referrer-spam` directory to your `/wp-content/plugins/` directory, using your favorite method (ftp, sftp, scp, etc...)
1. Activate `WP Block Referral Spam` through the 'Plugins' page in your WordPress site
1. Go to **Tools > Block Referrer Spam** page and set the options under **Settings** section


== Frequently Asked Questions ==

= Where do I change the plugin's settings? =

Navigate to **Tools > Block Referrer Spam** in your WordPress Dashboard, and look for the **Settings** section.

= Can I add my own list of referrer spammers? =

Yes! This plugin not only protects you against over 200 referrer spammers, but it also allows you to add your own referrer spammers.
To do so, go to the plugin's setting page (**Tools > Block Referrer Spam**) and add your spammers in the text box.

= Is this plugin compatible with my webserver? =

This plugin is fully compatible with any webserver out-of-the-box.
Its adaptability ensures that will work with any version of either apache, nginx, iis or lighttpd webserver.

* When apache is used, the plugin adds specific rules into .htaccess file.
* When apache is not used, the plugin automatically recognizes it and uses internal code rules to keep away spammers.

= How often does the Referrer Spam List get updated? =

The Referrer Spam List is updated automatically every 7 days.
It loads the values from a community-contributed list of [referrer spammers](http://en.wikipedia.org/wiki/Referer_spam), you can see the source [here](https://github.com/piwik/referrer-spam-blacklist).

= How secure is this plugin? =

This plugin has been developed with one thing in mind: to keep your WordPress site as **secure** as possible, **ad free**, **spam free** and with the maximum **performance** reachable.
The developers that contribute to build this plugin are 100% **reliable** and some of the **most experienced WordPress developers**.


== Screenshots ==

1. The plugin's settings section, found under **Tools > Block Referrer Spam**.
2. Example of **referrer spammers** in Google Analytics.


== Changelog ==

= 1.1 =
* Renamed internal functions to ensure compatilibity

= 1.0 =
* Initial public release