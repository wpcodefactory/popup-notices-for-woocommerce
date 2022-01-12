=== Pop-up Notices for WooCommerce ===
Contributors: karzin
Tags: popup,notices,woocommerce,notice,modal
Requires at least: 4.4
Tested up to: 5.8
Stable tag: 1.3.7
Requires PHP: 5.6.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Turn your WooCommerce Notices into Popups

== Description ==

Notices are important messages WooCommerce displays on your store for customers, like:

* Product has been added to cart
* Field Name is a required field
* Have a coupon?
* And so on...

And sometimes, depending on the theme, they get so discreet customers don't see it or there are cases where they are just too ugly.

**Pop-up Notices for WooCommercee** adds WooCommerce Notices into beautiful Popups that will be noticed and appreciated.

### &#127942; Premium Version ###
Do you like the free version of this plugin?
Did you know We also have a [Premium Version](https://wpfactory.com/item/popup-notices-for-woocommerce/)?

Check some of its features for now:

* **Style options**
Style the Pop-up the way you want with tons of options using the Customizer, including Icons from FontAwesome


* **Customize Messages**
Customize WooCommerce messages modifying or adding more content after or before them


* **Prevent page scrolling**
Disable page scrolling on AJAX notices


* **Ignore multiple messages**
Ignore particular notices you don't want to display inside the Pop-up


* **Avoid repeated messages**
Use Cookies to avoid messages that keep being displayed constantly. Set a expiration time for them and they will be only displayed again after the time expires


* **Restrictive Loading**
Load the plugin at some specific moment or place, like only on Cart or Checkout


* **Auto-Close**
Auto-close the popup after x seconds


* **Audio**
Play any sound you want when the Pop-up opens or when it closes


* **Support**

== Frequently Asked Questions ==

= How can I contribute? Is there a github repository? =
If you are interested in contributing - head over to the [Popup Notices for WooCommerce plugin GitHub Repository](https://github.com/thanks-to-it/popup-notices-for-woocommerce) to find out how you can pitch in.

= Micromodal Credits =

* [Indrashish Ghosh](https://twitter.com/_ighosh)
* [Kalpesh Singh](https://twitter.com/knowkalpesh)

= Micromodal License =

MIT License

Copyright (c) 2017 Indrashish Ghosh

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

== Installation ==

1. Upload the entire 'popup-notices-for-woocommerce' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Start by visiting plugin settings at WooCommerce > Settings > Popup Notices.

== Screenshots ==

1. An example of a WooCommerce Notice message on the Popup
2. An example of WooCommerce Notice errors on the Popup
3. An example of a WooCommerce Notice info on the Popup

== Changelog ==

= 1.3.7 - 12/01/2022 =
* Move "Notice hiding" to free version.
* WC tested up to: 6.0.

= 1.3.6 - 14/12/2021 =
* Add option to enable the plugin by device type.
* Add compatibility section.
* WC tested up to: 5.9.

= 1.3.5 - 13/10/2021 =
* Dev - Improve `is_plugin_active()` function.

= 1.3.4 - 13/10/2021 =
* WC tested up to: 5.8.

= 1.3.3 - 04/08/2021 =
* Fix - Free and pro version can't be active at the same time.

= 1.3.2 - 04/08/2021 =
* Update promoting notice.
* Add "AJAX add to cart notice" options.
* Add autoloader.
* WC tested up to: 5.5.
* Tested up to: 5.8.

= 1.3.1 - 26/05/2021 =
* Fix free version notice promoting Pro version.

= 1.3.0 - 25/05/2021 =
* Fix Error: Class "ThanksToIT\PNWC\Core" not found.

= 1.2.9 - 25/05/2021 =
* Change composer setup.

= 1.2.8 - 24/05/2021 =
* Update package.json and gulpfile.js setup.
* Add `overflow-y:auto` to `.ttt-pnwc-wrapper`.
* WC tested up to: 5.3.
* Tested up to: 5.7.
* Change admin notices.
* Update deploy script.

= 1.2.7 - 09/02/2021 =
* Create option to prevent closing if clicking outside the popup.

= 1.2.6 - 15/01/2021 =
* Update POT file

= 1.2.5 - 12/01/2021 =
* Create Search method option for Ignore messages section.
* Fix raw_values options.
* Create an option to choose how to load the micromodal js.
* WC tested up to: 4.9
* Tested up to: 5.6

= 1.2.4 - 04/11/2020 =
* Improve composer autoload call.
* Fix minified js.
* Fix empty customized message.
* WC tested up to: 4.6

= 1.2.3 - 13/10/2020 =
* Tested up to WP 5.5
* WC tested up to: 4.5
* Add Auto-close > Notice types option as pro feature.

= 1.2.2 - 18/06/2020 =
* Fix 'Close on Click Inside' option on links like 'showcoupon' or 'showlogin'

= 1.2.1 - 17/06/2020 =
* Add 'Close on Click Inside' option
* WC tested up to: 4.2

= 1.2.0 - 23/05/2020 =
* WC tested up to: 4.1
* Add premium option to auto-close popup
* Improve interface on admin settings regarding premium version
* Fix Message Origin option from Cookie feature
* Improve notice messages on admin

= 1.1.9 - 17/04/2020 =
* Tested up to WP 5.4
* WC tested up to: 4.0

= 1.1.8 - 26/11/2019 =
* WC tested up to: 3.8
* Tested up to: 5.3

= 1.1.7 - 21/08/2019 =
* Change the way the Notice Hiding option works
* Improve raw_values option
* Improve empty messages validation
* Add Conditional Options
* WC tested up to: 3.7

= 1.1.6 - 16/07/2019 =
* Enqueue micromodal with absolute https protocol

= 1.1.5 - 14/06/2019 =
* Add "Restrictive Loading" premium option to load the plugin at some specific moment or place

= 1.1.4 - 21/05/2019 =
* Add premium option to prevent WooCommerce Scrolling
* WordPress Tested up to: 5.2
* WC tested up to: 3.6

= 1.1.3 - 08/04/2019 =
* Fix close button position on Edge and Safari
* Check plugins array on updated_plugin rule
* Tested up to: 5.1

= 1.1.2 - 11/02/2019 =
* Improve Ignored Messages field
* Add sounds section on settings
* Improve premium notices on admin
* Add default ignored messages preventing empty popups
* Improve readme

= 1.1.1 - 05/02/2019 =
* Fix alignment on small screen
* Add option to ignore messages
* Improve settings page

= 1.1.0 - 28/01/2019 =
* Fix close button z-index
* Add option for customizing the modal template
* Add `ttt_pnwc_modal_template` filter
* Improve responsive CSS by hiding icons on small devices
* Add container for messages
* Add options for Font Awesome
* Improve button style inside message
* Add cookie options

= 1.0.9 - 23/12/2018 =
* Update WooCommerce 'Tested up to' 3.5
* Update WordPress 'Tested up to' 5.0

= 1.0.8 - 06/11/2018 =
* Improve translation strings

= 1.0.7 - 19/10/2018 =
* Avoid duplicated messages

= 1.0.6 - 17/10/2018 =
* Fix missing coupon error message

= 1.0.5 - 02/10/2018 =
* Force close button style preventing theme's overriding

= 1.0.4 - 22/09/2018 =
* Handle translation
* Set Ajax Popup as enabled by default
* Fix duplicated notice

= 1.0.3 - 10/09/2018 =
* Include Micromodal credits and License

= 1.0.2 - 09/09/2018 =
* Add 'AJAX popup' option on free plugin
* Improve plugin description
* Change notice background
* Change close button hover

= 1.0.1 - 02/09/2018 =
* Fix plugin slug and text domain
* Add settings page
* Add error notices option
* Add success notices option
* Add info notices option
* Improve plugin description

= 1.0.0 - 25/08/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
* Initial Release.