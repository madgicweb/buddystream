=== BuddyStream ===
Contributors: Blackphantom
Tags: Buddypress, Twitter, Facebook, Flickr, Tweetstream, Facestream, Google+, Soundcloud, Rss, Last.fm, Vimeo, LinkedIn, Buddystream, Apollo
Requires at least: WP 2.9.1, BuddyPress 1.2.3
Tested up to: WP 3.2.2, BuddyPress 1.5.5
Stable tag: 2.5.09

== Released under the GPL license ==
http://www.opensource.org/licenses/gpl-license.php

== Description ==
BuddyStream is a BuddyPress plugin that will synchronize all of your favorite Social Networks to the BuddyPress activity stream.

The plugin is easy to set-up, operate, and for your members to use.
Each Social Network has its own admin panel where you can see which users are using the network, view cool statistics, and manage the advanced filtering settings.

Networks that the free plugin currently supports:
- Twitter
- Flickr
- Youtube
- Last.fm

Requirements.
- PHP 5.2.1+
- CURL
- JSON
- CRONJOB access

You may find FREE translations on: http://www.buddystream.net.
For support and other feature request, please contact us on our website.

== Installation ==
1. Upload this plugin to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Setup the BuddyStream plugin in the admin sidebar.
4. Done!

== Screenshots ==

== ChangeLog ==

= 2.5.09 =
* Removed dashboard widget.
* Refactored the filters they are now stable.
* Added css classes to widget for custom styling.
* Fixed cron service license check.
* Import counters calculations are now correct.
* Colorbox integration improvement.

= 2.5.08 =
* Removed usage of simpleXml load from file for serveral imports.
* Fixed dashboard javascript errors.
* Fixed Flickr not importing photos with no titles
* Fixed issue with imports not importing all items due failure seconday_item_id check (now made unique per user).
* Fixed post to Facebook page(s) button shown when user did revoked authorization.
* Added configurable widget to get your users connecting there social accounts faster.

= 2.5.07 =
* Fixed Facebook video imports.
* Added core functionality to check existing content.
* Removed since => till filter so all items get imported from Facebook.

= 2.5.06 =
* Fixed imports not working due configuration error.

= 2.5.05 = 
* Added Facebook pages select on user settings (user can now choose which pages to sync.)
* Added Facebook albums select on user settings (user can now choose which albums to sync.)
* Added check so non-public items won't be imported anymore. (privacy)
* Fixed Social albums not hidding when albums feature is turned off.

= 2.5.04 =
* Fixed LinkedIn import due change LinkedIn API.
* Fixed Google+ import.
* Fixed general settings page nog saving
* Added turn all on/off buttons on general settings page.
* Added feature to move social albums under profile navigation instead of top-level.

= 2.5.03 =
* Fixed loading issue with .DS_Store file.
* Fixed version tab error.
* Fixed Facebook imports.
* Fixed Twitter username cut-off in items (fix, works only for new items).

= 2.5.02 =
* Added general setting page to turn on/off some features.
* Added extra check to make sure a activity item is not empty.
* Improved ShareBox loading and showing.

* Fixed Twitter usernames in backend.
* Fixed Facebook importing items from friends.
* Fixed Vimeo, Twitter, Facebook import
* Fixed filters
* Fixed loading of the ShareBox

= 2.5.01 =
* Fixed double declared class in certain cases.
* Fixed soundcloud set-up description

= 2.5 =
* Added Vimeo imports.
* Added Google+ imports.
* Added quickstats widget for admin dashboard.
* Added tour option for WordPress 3.3 and higher.
* Added new share buttons and counters.
* Added more core functionality.
* Added Facebook pages sync.
* Added social albums, one page to see all album (cleaner menu's)
* Added social network settings page, one page for all network settings (cleaner menu's)
* Added admin notices when networks are turned on but not yet configured.
* Added buttons to turn all networks on/off with one button.
* Added Sharebox (one pop up box for sharing activity items)

* Fixed problems with max item imports.
* Fixed problems with filters.
* Fixed user album pages.
* Fixed counters (now not counting the added hashtags of BuddyStream)

* Removed separate share buttons (replaced by sharebox) 
* Removed Zend Framework and made it really lightweight.
* Cleaned up activity items styling.
* Ported all networks to new OAuth library.
* Changed the user setting pages for better usability.
* Improved admin css.
* Replaced lightbox library.

* And more we can't remember.

= 2.1.7.3 =
* Fixed conflicts with other social plugins.

= 2.1.7.2 =
* Fixed Twitter importing issues.
* Removed some left behind debugging code.

= 2.1.7.1 =
* Fixed white screen on new and upgrading install.

= 2.1.7 = 
* Added own OAuth library.
* Ported Twitter to the new OAuth library.
* Removed Google Buzz (no longer supported by google)
* Now forum posts are posted complete on Facebook.

= 2.1.6 =
* Fixed double Tweets.
* Fixed check for other double items.
* Fixed all usser setting pages and album to display on BuddyPress 1.5
* Fixed hiding of items on the sitewide activitystream (please re-save to enable it again)

= 2.1.5 = 
* Changed networks checks now working with WordPress core.
* Better error message in the error log for Twitter.
* Added fix for BuddyPress 1.5 so it works again.

= 2.1.4 = 
* Fixed strpos error

= 2.1.3 = 
* Changed the way how we check the license key, now uses WordPress core functionality.
* Fixed some undefined variables on the admin menu.
* Fixed Load javascript after page is ready loading on Powercentral.
* Fixed db_version() for older upgrades.
* Removed upgrade.php (no longer needed).
* Fixed undefined message on Soundcloud.
* Fixed blog creation failure on multisite.
* Small integration of Activity Plus links and images.

= 2.1.2 =
* Fixed db_version() error on clean install.
* Fixed incorrect shortlink on forum topics
* Fixed blanc cronjob settings page, due incorrect php starting tag.

= 2.1.1 =
* Fixed css issues
* Added extra check in core for importing items
* Added more error tracking for twitter
* Fixed the blank cronjob page.
* Fixed bad ecoding errors on rss feed created by the imports.
* Fixed max import filter for Flickr
* Fixed user-rights problem for showing albums other then your own.
* Fixed deleted activity item is imported again
* Fixed compatibility for BuddyPress 1.5
* Fixed network timeout check (Flickr import failure)
* Updated Twitter oAuth code, no longer using Zend_Twitter_Service


= 2.1 =
* Fixed custom settings slug on user pages.
* Fixed missing endtag for Facebook like button.
* Fixed fullsize images for Facebook photos and links.
* Fixed double images on Facebook items.
* Fixed translations improved.
* Fixed Soundcloud authorization improved.
* Fixed timeout on cronjob settings page (white page).

* Updated Soundcloud library.
* Updated the Dashboard page.

* Added External links now open in a new window.
* Added Flickr album on profile page.
* Added Youtube album on profile page.
* Added Facebook album on profile page.
* Added Soundcloud album on profile page.
* Added Linkedin synchronization.

= 2.0.5 = 
* Fixed rss feed.
* Fixed Flickr settings page and import.
* Fixed some texts on the setting pages.
* Fixed import issues with double activity items and empty activity items.
* Fixed Facebook links import (linkshare).
* Fixed default value of importing facebook for users. (now default on)

= 2.0.4 = 
* Fixed not importing Facebook items when stream is readonly for friends.

= 2.0.3 =
* Translations not loading when locale was set but language file did not exist. (now loading English translation)
* Fixed options to hide items sitewide.
* Fixed few html errors.
* When Twitter keys are invalid or mis-configured don't show error but enter error in log with re-enter keys request.
* Posting to group forums (with BuddyStream enabled) are working again.

= 2.0.2 =
* Fixed Youtube import limit.
* Fixed athorisation collision with Soundcloud and Facebook
* Added remove settings link of user setting pages.

= 2.0.1 =
* Fixed some php errors.
* Fixed Twitter auth. link
* Added space after network icon

= 2.0 =
* Versions have now also butterfly names, version 2.0 is called Goliath (The Goliath Birdwing).
* Added menu support for WordPress 3.1
* Added support for extentions.
* Import procedure is now extention aware.
* Import has a fail save when a network is offline.
* Compatibility with newest versions of WordPress and BuddyPress.
* Cleaned up the admin pages and improved the interaction.
* Removed Facebook from lite version.
* Fixed javascript conflicts.
* Fixed prettyphoto conflict.
* Added support for twitpic.
* Added support for yfrog.
* Added powercentral for enabling and disabling extentions.
* Added log page for BuddyStream.
* Added option for Twitter share button on activity stream.
* Added option for Facebook like button on activity stream.
* Added Rss extention so users can add their rss blogs into their activity stream.

= 1.0.3.2 =
* Import issues on some installs sorted out.
* Fixed issue that import was saving files on the server.
* Updated the library to latest version.
* Fixed bcpow() function error for older php versions.
* Fixed posting forum topics and reply's to twitter and facebook.

= 1.0.3.1 =
* Fixed wp-load error for import
* Fixed stats for facebook (not showing the users)
* Changed some text for the facebook integration

= 1.0.3 =
* Performance import by cronjob added!
* Removed background import (only created heavy server load)
* Fixed settings pages when defined a custom slug (BP_SETTINGS_SLUG)
* Fixed twitter auth errors.
* Fixed html errors (labels for radio buttons)
* Security patch for non logged in users.
* Replaced php short tags for full php tags.
* Replaced some global variables for functions (faster)
* Fixed white pages
* Fixed support link in dashboard
* Fixed session errors
* Fixed facebook importing for some installs
* Added upgrade procedure


= 1.0.2.1 =
* Fixed session errors
* Fixed double imports from last.fm

= 1.0.2 =
* Fixed Facebook importing.
* Fixed url to Twitter application creation.
* Last.FM, limit import Per/User Per/Day - not working as implied.
* Url to Twitter app ceration wrong
* Choosing hide tweets does NOT hide the Tweets in the activity stream
* Apostrophes When Using 'To Twitter' Button
* Twitter Auth problems
* Member's Activity Stream for friends, groups, @mentions broken
* Escaping of single/double quotes
* Added tabs in admin
* Added dashboard to BuddyStream with some cool pie charts

= 1.0.1 =
* Initial release of BuddyStream