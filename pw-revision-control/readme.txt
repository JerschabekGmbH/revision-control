=== PW Revision Control ===
Contributors: Jerschabek GmbH
Donate link: https://www.jerschabek-gmbh.de
Tags: Revisions, Posts
Requires at least: 4.0
Tested up to: 6.2
Stable tag: trunk
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress saves revisions every time a post is saved. Easily control the number of saved revisions.

== Description ==

WordPress saves revisions every time a post is saved. This plugin adds a simple options page, where you can enter a limit of saved revisions.

There is no need to add the constant WP_POST_REVISIONS to the wp-config.php

## Features

* Allows to control the number of revisions


== Installation ==

This section describes how to install the plugin and get it working.

## Manual Installation

1. Upload the plugin files to the `/wp-content/plugins/revision-control` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==


== How to update the plugin ==

1. Go to the github respository and edit the version numbers
2. Edit the version line in the main plugin comment: /pw-revision-control/pw-revision-control.php
3. Edit the version line in the updater class: /pw-revision-control/class-update-checker.php
4. Then create a .zip of the new plugin and add it to Github as a new release
5. Update the self-host plugin manifest info.json with the new version and link to the new zip on Github

= Where can I configure the settings? =

Go to Settings -> Revision Control and add the number of Revisions.

= Why does the plugin not work? =

Please check if revisions are defined in the wp-config.php and remove it


== Screenshots ==


== Changelog ==

0.1.0 Initial Release

0.2.0 Implement Update Service

1.0 Minor Fixes. Updater uses guthub releases


== Upgrade Notice ==

