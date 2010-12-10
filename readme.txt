=== Buddystream ===
Contributors: Blackphantom
Tags: Buddypress, Twitter, Facebook, Flickr, Tweetstream, Facestream, Buddystream
Direct Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TKBY4JM6WDSD2
Requires at least: WP 2.9.1, BuddyPress 1.2.3
Tested up to: WP 3.0.3, BuddyPress 1.2.6
Stable tag: 1.0.3.1

== Released under the GPL license ==
http://www.opensource.org/licenses/gpl-license.php

== Description ==
BuddyStream is a BuddyPress plugin that will synchronize all of your favorite Social Networks to the BuddyPress activity stream.

The plugin is easy to set-up, operate, and for your members to use.
Each Social Network has its own admin panel where you can see which users are using the network, view cool statistics, and manage the advanced filtering settings.

Networks that the plugin currently supports:
- Twitter
- Facebook
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

The BuddyStream plugin has full localisation support.

== SPECIAL THANKS ==
A special "Thank You" to all the poeple who donated to the support of BuddyStream and to those that have translated the plugin!  We encourage any of you that find this plugin to be an inseparable part of your BuddyStream user experience to donate to the continued support of BuddyStream.  Find out more about donating to this amazing plugin here:
http://buddystream.net/funding/

== Installation ==
1. Upload this plugin to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Setup the BuddyStream plugin in the admin sidebar.
4. Done!

Requirements.
- PHP 5.2.1+
- CURL
- JSON
- CRONJOB access

== Screenshots ==

== ChangeLog ==

= 1.0.3.1 =
* Fixed wp-load error for import
* Fixed stats for facebook (not showing the users)
* Changed some text for the facebook intergration

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
* Fixed session errrors
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
* Initial release of Buddystream