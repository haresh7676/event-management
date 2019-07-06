=== WP Event Manager - Emails ===


Contributors:  WP Event Manager
Requires at least: 4.1
Tested up to: 4.9.7
Stable tag: 1.2
Copyright: 2017 WP Event Manager
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Integrates the custom email templates for the wp event manager when user will submit new event listing.

== Description ==

In order to create our own mail system, we will be doing four things. First, we will create a nice email template to use. We will then modify the mailer function so that it uses our new custom template. We will then modify the actual text of some of the built-in emails. Then we will proceed to hook our own emails into different events in order to send some custom emails.

When a user registers on your WordPress site or you create a user manually in wp-admin an email is sent automatically. In many cases this email will not be adequate; you may want to add a link, customize some of the copy, or create an HTML email with images. The solution is to change the WordPress default implementation of the wp_new_user_notification() function. wp_new_user_notification() is a pluggable function which means you’ll need to create a plugin in order to redefine it.


== Frequently Asked Questions ==

* Where can I use this? 

You can use this plugin with GAM Event Manager.

= Support Policy =

I will happily patch any confirmed bugs with this plugin, however, I will not offer support for:

1. Customisations of this plugin or any plugins it relies upon
2. Conflicts with "premium" themes from ThemeForest and similar marketplaces (due to bad practice and not being readily available to test)
3. CSS Styling (this is customisation work)

If you need help with customisation you will need to find and hire a developer or you can hire our developers or team for making the changes.

== Installation ==

To install this plugin, please refer to the guide here: [http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)

== Changelog ==

= 1.2 =

* Fixed - Update restriction removed.

= 1.1 =

* Fixed -  Minify js and css
* Fixed -  Auto updater problem.
* Added -  Pending,Publish,Expired Event status email notfication.
* Added - Admin side template settings for pending,publish and expire event.

= 1.0 =
* First release.