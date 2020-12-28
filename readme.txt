=== Tribe Common ===

== Changelog ==

= [4.12.15] TBD =

* Tweak - Add the `tribe_customizer_print_styles_action` to allow filtering the action the Customizer will use to print inline styles. [TEC-3686]
* Tweak - Allow disabling and enabling logging functionality by calling hte `tribe( 'log' )->disable()` and `tribe( 'log' )->enable()` methods on the Log service provider.
* Tweak - Update di52 containers to latest version for compatibility with WPStaging Pro. [BTRIA-650]

= [4.12.14] 2020-12-02 =

* Fix - Correctly handle mutltiple calls to the Repository `by` or `where` method that would cause issues in some Views [ECP-357]
* Fix - Do not try to store overly large values in transients when not using external object cache. [TEC-3615]
* Fix - Improve the Rewrite component to correctly parse and handle URLs containing accented chars. [TEC-3608]
* Tweak - Add the `Tribe__Utils__Array::merge_recursive_query_vars` method to correctly recursively merge nested arrays in the format used by `WP_Query` [ECP-357]
* Language - 0 new strings added, 109 updated, 1 fuzzied, and 0 obsoleted

= [4.12.13.1] 2020-11-20 =

* Fix - Prevent `tribe_get_first_ever_installed_version()` from having to spawn an instance of the Main class for version history.

= [4.12.13] 2020-11-19 =

* Tweak - Allow deletion of non persistent keys from Tribe__Cache handling. [ET-917]
* Fix - Prevent items without children to be marked as groups in SelectWoo UI. [CE-106]
* Fix - Update the MomentJS version to 2.19.3 for the `tribe-moment` asset. [BTRIA-574]
* Language - 0 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

= [4.12.12.1] 2020-11-19 =

* Tweak - Update version of Freemius to the latest version 2.4.1 [TEC-3668]
* Tweak - Include a new Notice style for Banners [TCMN-90]

= [4.12.12] 2020-10-22 =

* Tweak - Add the `tribe_suspending_filter` function to run a callback detaching and reattaching a filter. [TEC-3587]
* Fix - Correctly register and handle Block Editor translations. [ECP-458]
* Fix - Update our use of Monolog logger to avoid issues when the plugins are used together with the WooCommerce Bookings plugin. [TEC-3638]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.12.11] 2020-10-19 =

* Fix - Dropdown AJAX search for taxonomy terms properly using SelectWoo search formatting, used in Community Events tags and Event categories. [CE-96]
* Language - 0 new strings added, 7 updated, 1 fuzzied, and 0 obsoleted

= [4.12.10] 2020-09-28 =

* Tweak - Adjust SelectWoo dropdown container attachment to include search and minimum results for search. [FBAR-139]
* Tweak - Move border style button styles to border-small and add various border button styles that match the solid button style. [FBAR-143]
* Tweak - Add the common views folder to the `Tribe__Template` lookup folders, the folder will be searched for matching template files only if no plugin-provided template was found. [FBAR-148]
* Tweak - Add the `tribe_template_common_path` filter to allow controlling the path of the template file provided by common. [FBAR-148]
* Tweak - Add the `tribe_without_filters` function to run a callback or closure suspending a set of filters and actions. [TEC-3579]
* Tweak - Added hover and focus colors, update default colors to make them accessible. [FBAR-165]
* Fix - Prevent `register_rest_route` from throwing notices related to `permission_callback` (props @hanswitteprins)
* Language - 0 new strings added, 2 updated, 1 fuzzied, and 0 obsoleted

= [4.12.9] 2020-09-21 =

* Tweak - Added Support for overriding individual arguments while registering group assets using `tribe_assets`. [TCMN-88]
* Tweak - Introduce the `tribe_doing_shortcode()` template tag to check if one of our shortcodes is being done. [ET-904]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.12.8] 2020-08-26 =

* Fix - Added IE11 compatibility for the toggles styles using `tribe-common-form-control-toggle` CSS class. [ET-865]
* Tweak - Improve regular expressions used to parse UTC timezones by removing non-required grouping and characters. [TCMN-68]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.12.7] 2020-08-24 =

* Tweak - Allow SelectWoo dropdown to be attached to the container via the `data-attach-container` attribute. [FBAR-129]
* Tweak - Adjust the border radius of the form checkbox styles. [FBAR-126]
* Tweak - Adjust the layout styles for tribe common checkboxes and radios. [FBAR-126] [FBAR-127]
* Fix - Correctly handle array format query arguments while generating clean, or canonical, URLs; this solves some issues with Filter Bar and Views v2 where filters would be dropped when changing Views, paginating or using the datepicker. [FBAR-74, FBAR-85, FBAR-86]
* Language - 3 new strings added, 30 updated, 3 fuzzied, and 1 obsoleted

= [4.12.6.1] 2020-08-17 =

* Fix - Pass extra props down to Modal component to allow addition of extra properties. [GTRIA-275]

= [4.12.6] 2020-07-27 =

* Feature - Added the `tribe_normalize_orderby` function to parse and build WP_Query `orderby` in a normalized format. [TEC-3548]
* Feature - Added the `pluck`, `pluck_field`, `pluck_taxonomy` and `pluck_combine` methods to the `Tribe__Utils__Post_Collection` class to allow  more flexible result handling when dealing with ORM result sets. [TEC-3548]
* Tweak - Adjust verbosity level to report connection issues with Promoter [PRMTR-404]
* Tweak - Modify default parameters on `tribe_register_rest_route` for `permission_callback` to prevent notices on WordPress 5.5.
* Tweak - Add the `tribe_asset_print_group` function to allow printing scripts or styles managed by the `tribe_assets` function in the page HTML. [ECP-374, ECP-376]
* Tweak - Add the `Tribe__Customizer::get_styles_scripts` method to allow getting the Theme Customizer scripts or styles managed managed by the plugins. [ECP-374, ECP-376]
* Tweak - Adjust verbosity level to report connection issues with Promoter. [PRMTR-404]
* Tweak - Include Virtual Events on Help Page sidebar widget [TEC-3547]
* Tweak - Update process to generate Promoter keys. [TCMN-85]
* Tweak - Register Promoter key as part of the WP Settings API. [TCMN-85]
* Tweak - Adjust level of access (protected to public) in 'Tribe__Promoter__Connector' class for external use of connector calls. [TCMN-82]
* Fix - Correct issue with Body_Classes removing classes added by other plugins. [TEC-3537]
* Fix - Set proper timezone on block editor when creating a new event. [TEC-3543]
* Fix - Properly enqueue the customizer styles to allow overriding of theme styles. [TEC-3531]
* Fix - Allow customizer styles to be applied on shortcode events views via the use of the filter `tribe_customizer_shortcode_should_print`. [ECP-450]
* Language = 1 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

= [4.12.5] 2020-06-24 =

* Feature - Added the `Tribe\Traits\With_Db_Lock` trait to provide methods useful to acquire and release database locks.
* Feature - Added the `tribe_db_lock_use_msyql_functions` filter to control whether Database locks should be managed using MySQL functions (default, compatible with MySQL 5.6+) or SQL queries.
* Tweak - Added case for manual control of field in dependency JS.
* Tweak - Add filter `tribe_promoter_max_retries_on_failure` to set the maximum number of attempts to notify promoter of a change on the WordPress installation, default to 3.
* Tweak - Register logs when notifications to Promoter failed and retry to notify until the limit of `tribe_promoter_max_retries_on_failure` is reached per notification.
* Fix - Backwards compatibility for `tribe_upload_image` allow to use the function on version of WordPress before `5.2.x`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.12.4] 2020-06-22 =

* Feature - Added the `Tribe\Traits\With_Meta_Updates_Handling` trait to provide methods useful in handling with meta.
* Fix - Prevent `$legacy_hook_name` and `$hook_name` template Actions and Filters to be fired if they are the same, preventing duplicated hook calls.
* Language - 10 new strings added, 27 updated, 1 fuzzied, and 2 obsoleted

= [4.12.3.1] 2020-06-09 =

* Security - Remove deprecated usage of escapeMarkup in Select2 (props to miha.jirov for reporting this).

= [4.12.3] 2020-05-27 =

* Fix - When using Block Editor we ensure that `apply_filters` for `the_content` on `tribe_get_the_content`, the lack of that filter prevented blocks from rendering. [TEC-3456]
* Tweak - Added the `bulk_edit` and `inline_save` locations to the Context. [VE-8]
* Language - 99 new strings added, 14 updated, 1 fuzzied, and 17 obsoleted

= [4.12.2] 2020-05-20 =

* Feature - Added array utility methods: `parse_associative_array_alias` to build an array with canonical keys while taking alias keys into account and `filter_to_flat_scalar_associative_array` to help do so. Useful for aliasing shortcode arguments, for example.
* Feature - Added `tribe_extension_is_disallowed` filter for Modern Tribe's core plugins to deactivate an extension whose functionality has become duplicative or conflicting.
* Language - 1 new strings added, 1 updated, 1 fuzzied, and 0 obsoleted

= [4.12.1] 2020-05-11 =

* Feature - Added a helper method `Tribe__Plugins::is_active( 'slug' )` to check if a given plugin is active.
* Feature - Add entry points through filters to be able to add content after the opening html tag or before the closing html tag. [TCMN-65]
* Tweak - Extended support for namespaced classes in the Autoloader.
* Tweak - Make Customizer stylesheet enqueue filterable via `tribe_customizer_inline_stylesheets`. [TEC-3401]
* Tweak - Normalize namespaced prefixes with trailing backslash when registering them in the Autoloader. [VE-14]
* Language - 1 new strings added, 15 updated, 1 fuzzied, and 0 obsoleted

= [4.12.0] 2020-04-23 =

* Feature - Management of Shortcodes now are fully controlled by Common Manager classes [TCMN-56]
* Fix - Prevent Blocks editor from throwing browser alert when leaving the page without any changes applied to the edited post.
* Fix - Clear the views HTML cache on language settings changes to ensure we don't mix up translated strings. [TEC-3326]
* Fix - Blocks editor CSS compatibility with WordPress 5.4 with new module classes: `.block-editor-inner-blocks`
* Fix - Add style override for <ul> in Divi due to theme use of IDs. [TEC-3235]
* Fix - Change text domain loading to occur on 'init' hook instead of 'plugins_loaded'. Added new `tribe_load_text_domains` action hook for our other plugins to use for their own text domain loading on 'init' as well. [TCMN-58]
* Fix - Change curly quotes to straight quotes in some HTML markup when doing 'tribe_required_label' for Modal dialogs.
* Tweak - Added a method that returns whether the events are being served through Blocks or the Classical Editor. [ETP-234]
* Tweak - Added homepage settings to system information.
* Tweak - Add the `tribe_template_done` filter to be able to disable a template before rendering. [TEC-3385]
* Tweak - Improved on meta data handling of for Blocks editor.
* Tweak - Deprecate Select2 3.5.4 in favor of SelectWoo
* Language - 0 new strings added, 38 updated, 2 fuzzied, and 1 obsoleted

= [4.11.5.1] 2020-03-23 =

* Fix - Assets class modification to prevent JavaScript and CSS failing to load when `SCRIPT_DEBUG=true` [TCMN-52]

= [4.11.5] 2020-03-23 =

* Tweak - Added context to the country and the state of Georgia to allow separate translation [BTRIA-63]
* Tweak - Allow uploads of images with a large size and images with no extension provided from the URL, as the extension from the URL was used to define the type of the file to be uploaded and when the extension was not present on the URL the file was considered invalid. [TCMN-46]
* Tweak - Expired transient garbage collector will only run once per request and when needed [TCMN-38]
* Language - 2 new strings added, 0 updated, 1 fuzzied, and 1 obsoleted

= [4.11.4] 2020-03-18 =

* Fix - Increase range of actions that trigger changes on Promoter with a `WP_Post` instance or using an ID. [TCMN-47]

= [4.11.3] 2020-02-26 =

* Fix - JavaScript error in tribe dialog when there are no dialogs. Change fallback from object to array. [TCMN-34]
* Fix - Fix display of Dialogs in Safari 12 mobile. [ETP-155]
* Fix - Bring back the dialog icons. [ETP-155]
* Tweak - Add theme compatibility for the tribe dialog [ETP-156]

* Tweak - Add check if in `the_content` filter to prevent it from being called again. [ECP-345]

= [4.11.2.1] 2020-02-25 =

* Fix - Plugin dependency registration with `Plugin_Register` will not prevent loading of all plugins in list if the last loaded fails. [TCMN-41]

= [4.11.2] 2020-02-19 =

* Tweak - Add the `tribe_cache` function as proxy to `tribe( 'cache' )` [TEC-3241]
* Tweak - Add the a JSON-LD data dedicated Debug Bar panel [TEC-3241]
* Tweak - Add the `post_tag` location to the context [TEC-3241]
* Tweak - Add some visibility-related methods to the `Tribe__Admin__Notices` class [TEC-2994]
* Tweak - Include `Rewrites::is_plain_permalink()` with proper caching [TEC-3120]
* Tweak - Included two new locations for `tribe_context()`: `plain_permalink` and `permalink_structure` [TEC-3120]
* Tweak - Update version of Freemius internally to 2.3.2 [TEC-3171]
* Fix - Prevent warning on when saving empty slug for Tribe Setting Fields.
* Fix - Set a default value for the datepicker format option to avoid issues in some settings combinations, thanks @helgatheviking. [TEC-3229]
* Language - 1 new strings added, 35 updated, 1 fuzzied, and 0 obsoleted

= [4.11.1] 2020-02-12 =

* Fix - Fix style overrides for new view shortcodes for Genesis theme. [ECP-316]
* Fix - Fix style overrides for new view shortcodes for Enfold theme. [ECP-315]
* Tweak - Update `adjustStart()` function in moment utils to allow start and end time to be the same. [TEC-3009]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.11.0.1] 2020-02-05 =

* Tweak - Add filtered method to Date Utils for fetching the datepickerFormat. [TEC-3229]
* Fix - Fatal in Context when global query object is not set. [TEC-3228]

= [4.11.0] 2020-01-27 =

* Feature - Inclusion of `Date_I18n_Immutable` and `Date_I18n` as WP friendly options to `DateTimeImmutable` and `DateTime` respectively.
* Tweak - Caching of Tribe Options in memory to improve performance.
* Tweak - Set the default datepicker (compact) format to MM/D/YYYY [136789]
* Tweak - Add the `Tribe\Traits\Cache_User::reset_caches` method to clear cache entries [138357]
* Fix - Template class now will properly create file name for the hooks when in a different namespace.
* Fix - Template class now will properly determine the Theme folder when dealing with a different namespace.
* Language - 0 new strings added, 8 updated, 1 fuzzied, and 0 obsoleted

= [4.10.3] 2019-12-19 =

* Feature - Add Repository filter `where_meta_related_by_meta` for getting a post by the meta value an associated post. [133333]
* Fix - Correct missing block when switching from blocks to classic editor. [131493]

= [4.10.2] 2019-12-10 =

* Tweak - Add the `Tribe__Cache::warmup_post_caches` method to warmup the post caches for a set of posts [136624]
* Tweak - Add the `tribe_cache_warmup_post_cache_limit` filter to allow filtering the LIMIT of those warmup fetches [136624]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.10.1] 2019-12-10 =

* Fix - Updated the .pot file as it was outdated when shipping Tribe Common 4.10
* Language - 8 new strings added, 0 updated, 0 fuzzied, and 0 obsoleted

= [4.10] 2019-11-20 =

* Feature - Add new tribe-dialog object. Implements mt-a11y-dialog as `tribe-dialog` (or `tribe('dialog.view')`) as an extension of `Tribe_Template`. [129434]
* Feature - New dialogs can be created with a simple call to `tribe( 'dialog.view' )->render_dialog( $args )` in php. [129434]
* Feature - The tribe-dialog object sets up all necessary javascript and HTML via passed parameters. [129434]
* Feature - Add a basic dialog, modal, confirmation dialog, and alert as templates. [129434]
* Feature - Add methods `render_modal()`, `render_confirm()` and `render_alert()` to streamline common dialog types in Dialog View class. [129434]
* Feature - Add `tribe_installed_before`, `tribe_installed_after` and `tribe_installed_on` to test the install version against a passed version. Requires the plugin have the `VERSION` constant and `$version_history_slug` property set. `$version_history_slug` is a new property being added specifically for these functions. [133048]
* Tweak - Added filters: `tribe_dialog_args`, `tribe_dialog_template`, `tribe_dialog_html`, `tribe_dialog_script_args`, `tribe_dialog_script_html`
* Tweak - Added actions: `tribe_dialog_additional_scripts`, `tribe_dialog_additional_scripts_`, `tribe_dialog_additional_scripts_`, `tribe_dialog_register`, `tribe_dialog_hooks`, `tribe_dialog_assets_registered`
* Tweak - Changed views: `dialog/alert`, `dialog/button`, `dialog/confirm`, `dialog/dialog`, `dialog/modal`, `tooltip/tooltip`

= [4.9.23] 2019-11-20 =

* Tweak - Add the `tribe_get_query_var` function [137262]
* Tweak - Add `tribe_get_the_content()` and `tribe_the_content()` for PHP 7.2 compatibility with WordPress 5.2
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

= [4.9.22.1] 2019-11-18 =

* Fix - Pass the event to the onRequestClose handlers for the admin modal. [137394]

= [4.9.22] 2019-11-13 =

* Fix - Add some sanity checks to `is_editing_post` to ensure we don't show PHP error notices in some edge cases [122334]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.9.21] 2019-11-12 =

* Tweak - Added additional datepicker formats for simpler selection [116086, 126472, 117909]
* Tweak - Updated the Repository implementation to handle more complex `orderby` constructs [133303]
* Tweak - Added the `Tribe__Date_Utils::get_week_start_end` method [133303]

= [4.9.20] 2019-10-16 =

* Tweak - added the `tribe_sanitize_deep` function to sanitize and validate input values [134427]
* Tweak - use the `tribe_sanitize_deep` function to sanitize the values returned by the `tribe_get_request_var` function [134427]
* Tweak - Rename "Datepicker Date Format" to "Compact Date Format" [134526]
* Tweak - Adjust Promoter loading order to increase compatibility with plugins that use authentication early in the process [134862]
* Tweak - Add support for Authentication using a Header when using Promoter [133922]
* Language - 2 new strings added, 21 updated, 1 fuzzied, and 2 obsoleted

= [4.9.19] 2019-10-14 =

* Feature - Added new `tribe_strpos()` function that helps prevent fatal errors when hosting environments do not have support for multibyte functionality [135202]
* Language - 1 new strings added, 32 updated, 1 fuzzied, and 1 obsoleted

= [4.9.18] 2019-09-25 =

* Tweak - Added a missing space to the plugin list in the system information [134364]
* Fix - Use the correct name for North Macedonia
* Language - 1 new strings added, 32 updated, 1 fuzzied, and 1 obsoleted

= [4.9.17] 2019-09-16 =

* Tweak - Changed the 'url' validation error text to just say it needs to be valid, not that it has to be a valid *absolute* URL [72214]
* Tweak - Smarter plugin dependency checking with more accurate admin notices if not all requirements are satisfied [131080]
* Tweak - `tribe_get_request_var()` now includes explicit check against $_REQUEST [132248]
* Fix - Enqueue Thickbox script on all admin pages when needed [131080]
* Language - 2 new strings added, 48 updated, 1 fuzzied, and 2 obsoleted

= [4.9.16] 2019-09-04 =

* Tweak - Added the Monolog logging library as alternative logging backend [120785]
* Tweak - Hook Monolog logger on `tribe_log` action [120785]
* Tweak - Add redirection of `tribe( 'logger' )->log()` calls to the Monolog logger using the `tribe_log_use_action_logger` filter [120785]
* Fix - Handling of featured image setting [127132]
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

= [4.9.15.1] 2019-08-27 =

* Fix - Resolve JS console warnings from tooltip.js by adding missing `tribe` var when the var is not setup on the current page already [133207]

= [4.9.15] 2019-08-22 =

* Tweak - Add IDs to radio fields so we can target them with tribe-dependency [131428]
* Fix - Fixed alignment of description text for checkbox and radio fields in admin settings screens [131353]
* Language - 0 new strings added, 73 updated, 1 fuzzied, and 0 obsoleted

= [4.9.14] 2019-08-19 =

* Tweak - Update Lodash version on Block editor to prevent any possibility of a security issue with the package. From v4.17.11 to v4.17.15 [131421]
* Fix - Prevent mascot image to get blown up out of proportions to a larger size on buggy CSS loading. [131910]
* Language - 0 new strings added, 66 updated, 1 fuzzied, and 4 obsoleted

= [4.9.13] 2019-07-25 =

* Tweak - Update Freemius library to `2.3.0` [130281]
* Fix - Location filtering for Context class moved out of construct, resolving lots of navigation problems across The Events Calendar [130754]
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

= [4.9.12] 2019-07-03 =

* Feature - Include `tribe_classes()` and `tribe_get_classes()` for HTML class attribute handling in a similar way as the JS `classNames()`
* Tweak - Include proper documentation of why the plugin has been deactivated and a knowledgebase article about how to downgrade [129726]
* Tweak - When trying to update The Events Calendar with an incompatible version of an Addon that is expired, it will stop the upgrade [129727]
* Tweak - Add filter `tribe_is_classic_editor_plugin_active` to change the output if the classic editor is active or not [121267]
* Tweak - Create a new key if `AUTH_KEY` is not defined or is empty and add a new filter `tribe_promoter_secret_key` to filter the result [127183]
* Tweak - Divide the `tribe-common.js` file to prevent that file from being bloated with external dependencies. [129526]
* Tweak - Make sure `UTC-0` is converted back to `UTC` instead of `UTC-01` [129240]
* Tweak - Add new function `tribe_register_rest_route` Wrapper around `register_rest_route` to filter the arguments when a new REST endpoint is created [129517]
* Tweak - Add new method `Tribe__Cost_Utils::parse_separators` to infer decimal and thousands separators from a value that might have been formatted in a local different from the current one [98061]
* Fix - Prevent Clipboard Javascript from loading all over the place on `/wp-admin/` [129526]
* Fix - PHP 5.6 compatibility for `trait Cache_User` by using WP action `shutdown` instead of `__destruct` on our `WP_Rewrite` [129860]
* Language - 4 new strings added, 66 updated, 1 fuzzied, and 0 obsoleted

= [4.9.11.2] 2019-06-20 =

* Fix - Add Promoter PCSS file so that the proper CSS will be generated on package build [129584]

= [4.9.11.1] 2019-06-13 =

* Fix - Resolve fatal errors with references directly to The Events Calendar class constants [129107]

= [4.9.11] 2019-06-05 =

* Tweak - Add ability to prevent duplicate JOINs by allowing an optionally supplied ID per join [128202]
* Tweak - Added the `Tribe__Template::get_local_values` and `Tribe__Template::get_global_values` methods.
* Tweak - Added the `Tribe__Rewrite::get_canonical_url` and `Tribe__Rewrite::parse_request` methods.
* Language - 0 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

= [4.9.10] 2019-05-23 =

* Tweak - Add ability to prevent duplicate JOINs by allowing an optionally supplied ID per join [128202]
* Tweak - Add ability to turn on/off no_found_rows logic for queries [128202]
* Fix - Resolve issues with pagination in REST API by making the query cache more comprehensive [127710]

= [4.9.9] 2019-05-16 =

* Tweak - Reduced file size by removing .po files and directing anyone creating or editing local translations to translations.theeventscalendar.com
* Tweak - Optimize the autoloader function to eliminate duplicate path checkings.
* Fix - Fixed incorrect position of arg in filter_var function of email validation in Validate.php (props @dharmin) [125915]

= [4.9.8] 2019-05-14 =

* Feature - Add new `tooltip.view` PHP class to render new tooltips that utilize the existing `tribe-tooltip` CSS class for universal utility [120856]
* Tweak - Added filters: `tribe_context_locations`, `tribe_tooltip_template`, `tribe_tooltip_html`
* Tweak - Changed views: `tooltip/tooltip`

= [4.9.7] 2019-05-02 =

* Fix - Fixed cron to handle EA featured image processing while importing [124019]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.9.6.2] 2019-04-26 =

* Fix - Prevent Composer autoloader from throwing Fatal due to unexistent `setClassMapAuthoritative()` method [126590]

= [4.9.6.1] 2019-04-25 =

* Fix - Switch from using `any` to `[ 'publish', 'private' ]` for `post_status` on any Object Relational Mapping queries [126377]
* Fix - Resolve ORM Decorator issues that could cause fatal errors when calling methods not defined in the extending class

= [4.9.6] 2019-04-23 =

* Tweak - Ability to use ->where_multi() in Tribe_Repository objects to search for text matches on multiple fields (supports post fields, terms, and meta values) [125878]
* Tweak - Allow for external modal control for modal button component [123818]
* Tweak - Keep track of whether the current request was authorized by the Promoter connector [117668]
* Tweak - Added filters: `tribe_common_log_to_wpcli`, `tribe_promoter_authorized_redirect_url`
* Tweak - Changed views: `promoter/auth`
* Language - 0 new strings added, 17 updated, 1 fuzzied, and 1 obsoleted

= [4.9.5] 2019-04-17 =

* Feature - Include Freemius integration on our Common Libraries to enable information collection opt-in for some new users
* Tweak - Improve Object Relation Mapping base repository and filter classes to support usage of events
* Tweak - Modify `Date_Utils.php` and include another way of building DateTime object with Timezone `build_date_object`
* Tweak - Include Modern Tribe Context panel in the Debug Bar plugin
* Tweak - Include the `tribe_image_uploader_local_urls` filter in Image Uploader class
* Tweak - Include `tribe_process_allow_nopriv_handling` for non-logged users to improve control when async requests fire
* Tweak - Fork `WP_Background_Process` to `Tribe__Process__Handler` to allow for better internal maintenance by our team
* Tweak - Include more Array handling methods: `recursive_ksort`, `add_prefixed_keys_to`, `flatten`, `filter_prefixed`, `add_unprefixed_keys_to`
* Fix - Adjust `Tribe__Admin__Helpers::is_screen()` to avoid false positives and flag the events menu Tags page as a Tribe screen [107413]
* Fix - Improve the handling asynchronous requests for our Process Handler
* Fix - Correct problems with image asynchronous processing of thumbnail images
* Fix - Confirm that multisite background processing saves options and progress to the correct table in the database
* Language - 8 new strings added, 25 updated, 1 fuzzied, and 0 obsoleted

= [4.9.4] 2019-04-01 =

* Tweak - Keep track of whether the current request was authorized by the Promoter connector [117668]
* Tweak - Adjust `determine_current_user` priority used to identify Promoter user on calls to the REST API [124302]

= [4.9.3.2] 2019-03-14 =

* Fix - Resolve issues where some CSS files were not properly packaged with previous release

= [4.9.3.1] 2019-03-06 =

* Feature - Attach the post ID to Promoter calls and remove hook from all post saves [123732]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.9.3] 2019-03-04 =

* Fix - Make sure we pass and get the parameter when using cron jobs to import images on Event Aggregator [119269]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.9.2] 2019-02-26 =

* Feature - Add Promoter access from the WP Admin Bar
* Fix - Update the order of loading of providers to ensure correct execution for Promoter
* Tweak - Added Promoter to the App Shop [122550]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.9.1] 2019-02-14 =

* Feature - date and timezone building and validation methods to the `Tribe__Date_Utils` and `Tribe__Timezones` classes [116356, 115579]
* Feature - the `tribe_is_regex` and `tribe_unfenced_regex` functions [115582]
* Feature - Add new action `tribe_editor_register_blocks` used to register Event blocks via `common`
* Fix - Make sure assets are injected before is too late
* Fix - Fix an issue where feature detection of async-process support would fire too many requests [118876]
* Fix - Interface and Abstracts for REST base structures are now PHP 5.2 compatible
* Fix - Prevent to trigger error when using `array_combine` with empty arrays
* Fix - Improve conditionals on `Tribe__Timezones::generate_timezone_string_from_utc_offset` to return only string timezones [120647]
* Language - 0 new strings added, 13 updated, 1 fuzzied, and 0 obsoleted

= [4.9.0.1] 2019-02-07 =

* Fix - Modify extension dependency checking with new system to determine if it can load [122368]

= [4.9] 2019-02-05 =

* Feature - Add system to check plugin versions to inform you to update and prevent site breaking errors [116841]
* Tweak - Added support for Promoter licenses [120320]
* Tweak - Added filters: `tribe_register_{$main_class}_plugin_version`, `tribe_register_{$main_class}_plugin_dependencies`
* Tweak - Added actions: `tribe_plugins_loaded `
* Tweak - Changed views: `promoter/auth`
* Language - 3 new strings added, 10 updated, 1 fuzzied, and 1 obsoleted

= [4.8.5] 2019-01-21 =

* Fix - Updated translation strings from the Gutenberg extension merge [118656]
* Add - Added `strip_dynamic_blocks` method in `Tribe__Editor__Utils` [118679]
* Add - Added `exclude_tribe_blocks` method in `Tribe__Editor__Utils` [118679]
* Tweak - Allow better control of when we are in Classic editor with a new filter `tribe_editor_classic_is_active` [120137]
* Tweak - Adjusted content in the admin welcome page that users are brought to upon newly activating Event Tickets or The Events Calendar [117795]
* Language - 0 new strings added, 9 updated, 1 fuzzied, and 1 obsoleted

= [4.8.4] 2019-01-15 =

* Add - Added new filter `tribe_asset_data_add_object_{$object_name}` to allow integrations to customize the object data and add additional properties [119760]

= [4.8.3] 2018-12-19 =

* Tweak - Refreshing the Welcome page for The Events Calendar and Event Tickets [117795]
* Fix - Prevent admin tooltips to that full page width on Blocks Editor [118883]
* Fix - Datepicker code will now use the correct datetime format [117428]

= [4.8.2] 2018-12-13 =

* Feature - Add new action `tribe_editor_register_blocks` used to register Event blocks via `common`
* Fix - Make sure assets are injected before is too late
* Fix - Fix an issue where feature detection of async-process support would fire too many requests [118876]
* Fix - Interface and Abstracts for REST base structures are now PHP 5.2 compatible
* Fix - Ensure admin CSS is enqueued any time a notice is displayed atop an admin page [119452]
* Fix - Prevent to trigger error when using `array_combine` with empty arrays
* Fix - Compatiblity with classic editor plugin [119426]
* Tweak - Add functions to remove inner blocks [119426]

= [4.8.1] 2018-12-05 =

* Fix - speed up and improve robustness of the asynchronous process feature detection code [118934]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.8.0.1] 2018-11-30 =

* Fix - Added safety measure to reduce risk of a fatal error when examining list of network-activated plugins [115826]
* Fix - Corrected a usage of array syntax within the PUE client, in order to ensure compatibility with PHP 5.2.4 (our thanks to @megabit81 for promptly flagging this issue!) [119073]
* Language - 0 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

= [4.8] 2018-11-29 =

* Add - Added `tribe_cache_expiration` filter that allows plugins to use persistent caching based on cache key [117158]
* Fix - The invalid license key notice won't be displayed for Products with empty license keys [115562]
* Language - 9 new strings added, 7 updated, 1 fuzzied, and 0 obsoleted

= [4.7.23.1] 2018-11-21 =

* Fixed - Use of the `wp_doing_cron` function that would break compatibility with sites not on WordPress version 4.8 or later [118627]

= [4.7.23] 2018-11-13 =

* Add - Added `Tribe__Admin__Notice__Marketing` class for bespoke marketing admin notices [114903]
* Add - Added `TRIBE_HIDE_MARKETING_NOTICES` constant that, if defined to `true` in your site's `wp-config.php` file, will hide all marketing admin notices [114903]
* Fix - Fixed the setting-up of strings in the Tribe Bar datepicker to ensure they're translatable into languages other than English [115286]
* Language - 1 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

= [4.7.22] 2018-10-22 =

* Fix - Update `Tribe__Admin__Help_Page::is_current_page()` to return true when viewing the help page from the network settings [109563]
* Language - 3 new strings added, 35 updated, 3 fuzzied, and 1 obsoleted

= [4.7.21] 2018-10-03 =

* Fix - Only load Customizer CSS when loading main stylesheets or widget stylesheets of PRO [112127]
* Fix - Restore functionality of admin notices that display when a license key is invalid (thanks to @tyrann0us on GitHub for submitting the fix!) [113660]
* Fix - Update our mascot terminology to the preferred verbiage [114426]
* Fix - Handle the upload of images with more complex URLs [114201]
* Tweak - Added the `tribe_global_id_valid_types` action to allow new EA origins [114652]
* Tweak - Added the `tribe_global_id_type_origins` action to allow new EA origins [114652]

= [4.7.20] 2018-09-12 =
* Add - Added is_string_or_empty, is_image_or_empty, is_url_or_empty variations for REST API validation of values that are allowed to be set as empty [108834]
* Add - Introduce folder lookup for `Tribe__Template` to allow usage on Themes [112478]
* Fix - When option to avoid creating duplicate Organizers/Venues is enabled, we now exclude trash and autodraft posts when looking up potential duplicates [113882]
* Fix - Allow settings to restrict to only one country [106974]
* Tweak - Removed filters: `tribe_template_base_path`
* Tweak - Added new filters: `tribe_template_before_include`, `tribe_template_after_include`, `tribe_template_html`, `tribe_template_path_list`, `tribe_template_public_path`, `tribe_template_public_namespace`, `tribe_template_plugin_path`

= [4.7.19] 2018-08-22 =
* Fix - Add the following datepicker formats to the validation script: YYYY.MM.DD, MM.DD.YYYY, DD.MM.YYYY [102815]
* Add - Added the `Tribe__Process__Queue::delete_all_queues` method [111856]
* Tweak - updated some foundation code for the Tickets REST API [108021]
* Tweak - Event Aggregator Add-On text due to the removal of Facebook Imports [111729]

= [4.7.18] 2018-08-01 =
* Fix - Add `target="_blank"` to repository links in the Help Page [107974]
* Fix - Change 3rd parameter to be relative path to plugin language files instead of the mofile for load_plugin_textdomain(), thanks to jmortell [63144]
* Tweak - Deprecate the usage of old asset loading methods [40267]

= [4.7.17] 2018-07-10 =
* Add - Method to sanitize a multidimensional array [106000]
* Add - New is_not_null and is_null methods for Tribe__Validator__Base [109482]
* Tweak - Added new filter `tribe_plugins_get_list` to give an opportunity to modify the list of tribe plugins [69581]

= [4.7.16] 2018-06-20 =
* Fix - Fixed a PHP warning related to the RSS feed in the Help page [108398]
* Tweak - Add notices related to PHP minimum versions [107852]

= [4.7.15] 2018-06-04 =
* Add - Method to parse the Global ID string [104379]
* Add - Load tribe-common script to prevent undefined function errors with tribe-dropdowns [107610]

= [4.7.14] 2018-05-29 =

* Fix - Adjust the `Tribe__PUE__Checker` $stats creation regarding WordPress multisite installs [84231]
* Fix - Hide any errors generated by servers that don't support `set_time_limit()` [64183]

= [4.7.13] 2018-05-16 =

* Fix - Prevent PHP 5.2 error on new Queuing Proccess `T_PAAMAYIM_NEKUDOTAYIM` [106696]
* Fix - Modify some language and typos

= [4.7.12] 2018-05-09 =

* Fix - Updated datatables.js to its most recent version to prevent conflicts [102465]
* Tweak - Added the `Tribe__Process__Queue` class to handle background processing operations
* Tweak - Changed 'forums' for 'help desk' in the Help content [104561]
* Tweak - Updated datatables.js to most recent version, to prevent conflicts [102465]
* Tweak - Add `tribe_set_time_limit()` wrapper function to prevent errors from `set_time_limit()` [64183]
* Tweak - Changed 'forums' to 'help desk' throughout the content in the "Help" tab [104561]
* Language - 3 new strings added, 84 updated, 3 fuzzied, and 3 obsoleted

= [4.7.11] 2018-04-18 =

* Fix - Restore "type" attribute to some inline `<script>` tags to ensure proper character encoding in Customizer-generated CSS [103167]
* Tweak - Allow to register the same ID of a post if has multiple types for JSON-LD `<script>` tag [94989]
* Tweak - Added the `a5hleyrich/wp-background-processing` package and the asynchronous process handling base [102323]
* Tweak - Added the `Tribe__Process__Post_Thumbnail_Setter` class to handle post thumbnail download and creation in an asynchronous manner [102323]
* Tweak - Deprecated the `Tribe__Main::doing_ajax()` method and moved it to the `Tribe__Context::doing_ajax()` method [102323]
* Tweak - Modified the `select2` implementation to work with the `maximumSelectionSize` argument via data attribute. [103577]
* Tweak - Add new filters: `tribe_countries` and `tribe_us_states` to allow easier extensibility on the names used for each country [79880]
* Fix - Updated Timezones::abbr() with additional support for timezone strings not covered by PHP date format "T" [102705]

= [4.7.10] 2018-03-28 =

* Tweak - Adjusted app shop text in relation to Modern Tribe's ticketing solutions [101655]
* Tweak - Added wrapper function around use of `tribe_events_get_the_excerpt` for safety [95034]

= [4.7.9] 2018-03-12 =

* Tweak - Added the a `tribe_currency_cost` filtering for Currency control for Prices and Costs

= [4.7.8] 2018-03-06 =

* Feature - Added new `tribe_get_global_query_object()` template tag for accessing the $wp_query global without triggering errors if other software has directly manipulated the global [100199]
* Fix - Remove unnecessary timezone-abbreviation caching approach to improve accuracy of timezone abbreviations and better reflect DST changes [97344]
* Fix - Make sure JSON strings are always a single line of text [99089]

= [4.7.7.1] 2018-02-16 =

* Fix - Rollback changes introduced in version 4.7.7 to allow month view to render correctly.

= [4.7.7] 2018-02-14 =

* Fix - Fixed the behavior of the `tribe_format_currency` function not to overwrite explicit parameters [96777]
* Fix - Modified timezone handling in relation to events, in order to avoid DST changes upon conversion to UTC [69784]
* Tweak - Improved the performance of dropdown and recurrent events by using caching on objects (our thanks to Gilles in the forums for flagging this problem) [81993]
* Tweak - Reduced the risk of conflicts when lodash and underscore are used on the same site [92205]
* Tweak - Added the `tribe_transient_notice` and `tribe_transient_notice_remove` functions to easily create and remove fire-and-forget admin notices
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.6] 2018-01-23 =

* Fix - Make sure to apply `$settings` to each section with the initial values in the customizer [96821]
* Tweak - Include permalink structure into the report for support [68687]
* Tweak - Added `not_empty()` validation method to the `Tribe__Validate` class for more options while validating date formats [94725]
* Tweak - Update label on report for support to avoid confusions [68687]
* Tweak - Deprecated the unused $timezone parameter in the `tribe_get_start_date()` and `tribe_get_end_date()` template tags [73400]

= [4.7.5] 2018-01-10 =

* Fix - Added safety check to avoid errors surrounding the use of count() (our thanks to daftdog for highlighting this issue) [95527]
* Fix - Improved file logger to gracefully handle further file system restrictions (our thanks to Richard Palmer for highlighting further issues here) [96747]

= [4.7.4] 2017-12-18 =

* Fix - Fixed Event Cost field causing an error if it did not contain any numeric characters [95400]
* Fix - Fixed the color of the license key validation messages [91890]
* Fix - Added a safety check to avoid errors in the theme customizer when the search parameter is empty (props @afragen)
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

= [4.7.3] 2017-12-07 =

* Tweak - Tweaked Tribe Datepicker to prevent conflicts with third-party styles [94161]

= [4.7.2] 2017-11-21 =

* Feature - Added Template class which adds a few layers of filtering to any template file included
* Tweak - Included `tribe_callback_return` for static returns for Hooks
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.1] 2017-11-16 =

* Fix - Added support for translatable placeholder text when dropdown selectors are waiting on results being returned via ajax [84926]
* Fix - Implemented an additional file permissions check within default error logger (our thanks to Oscar for highlighting this) [73551]
* Tweak - Added new `tribe_is_site_using_24_hour_time()` function to easily check if the site is using a 24-hour time format [78621]
* Tweak - Ensure the "Debug Mode" helper text in the Events Settings screen displays all of the time (it previously would vanish with certain permalinks settings) [92315]
* Tweak - Allow for non-Latin characters to be used as the Events URL slug and the Single Event URL slug (thanks @daviddweb for originally reporting this) [61880]
* Tweak - Removed restrictions imposed on taxonomy queries by Tribe__Ajax__Dropdown (our thanks to Ian in the forums for flagging this issue) [91762]
* Tweak - Fixed the definition of Tribe__Rewrite::get_bases() to address some PHP strict notices its previous definition triggered [91828]
* Language - 0 new strings added, 16 updated, 1 fuzzied, and 0 obsoleted

= [4.7] 2017-11-09 =

* Feature - Included a new Validation.js for Forms and Fields
* Feature - Included a Camelcase Utils for JavaScript
* Tweak - Added Groups functionality for Tribe Assets class
* Tweak - Improve Dependency.js with better Documentation
* Tweak - Timepicker.js is now part of Common instead of The Events Calendar
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

= [4.6.3] 2017-11-02 =

* Fix - Added some more specification to our jquery-ui-datepicker CSS to limit conflicts with other plugins and themes [90577]
* Fix - Fixed compatibility issue with Internet Explorer 10 & 11 when selecting a venue from the dropdown (thanks (@acumenconsulting for reporting this) [72924]
* Fix - Improved process for sharing JSON data in the admin environment so that it also works within the theme customizer screen [72127]
* Tweak - Obfuscated the API key for the google_maps_js_api_key field in the "System Information" screen [89795]
* Tweak - Updated the list of countries used in the country dropdown [75769]
* Tweak - Added additional timezone handling facilities [78233]
* Language - 7 new strings added, 292 updated, 18 fuzzied, and 3 obsoleted

= [4.6.2] 2017-10-18 =

* Fix - Restored functionality to the "currency position" options in Events Settings, and in the per-event cost settings (props @schola and many others!) [89918]
* Fix - Added safety checks to reduce the potential for errors stemming from our logging facilities (shout out to Brandon Stiner and Russell Todd for highlighting some remaining issues here) [90436, 90544]
* Fix - Added checks to avoid the generation of warnings when rendering the customizer CSS template (props: @aristath) [91070]
* Fix - Added safety checks to the Tribe__Post_Transient class to avoid errors when an array is expected but not available [91258]
* Tweak - Improved strategy for filtering of JSON LD data (our thanks to Mathew in the forums for flagging this issue) [89801]
* Tweak - Added new tribe_is_wpml_active() function for unified method of checking (as its name implies) if WPML is active [82286]
* Tweak - Removed call to deprecated screen_icon() function [90985]

= [4.6.1] 2017-10-04 =

* Fix - Fixed issues with the jQuery Timepicker vendor script conflicting with other plugins' similar scripts (props: @hcny et al.) [74644]
* Fix - Added support within Tribe__Assets for when someone filters plugins_url() (Thank you @boonebgorges for the pull request!) [89228]
* Fix - Improved performance of retrieving the country and US States lists [68472]
* Tweak - Limited the loading of several Tribe Common scripts and stylesheets to only load where needed within the wp-admin (props: @traildamage ) [75031]
* Tweak - Removed explicit width styles from app shop "buy now" buttons to better accommodate longer language strings (thanks @abrain on GitHub for submitting this fix!) [88868]
* Tweak - Implemented a re-initializing of Select2 inputs on use of a browser's "Back" button to prevent some UI bugs, e.g. with such inputs' placeholder attributes not being populated (props @uwefunk!) [74553]
* Language - Improvement to composition of various strings, to aid translatability (props: @ramiy) [88982]
* Language - 3 new strings added, 331 updated, 1 fuzzied, and 2 obsoleted

= [4.6] 2017-09-25 =

* Feature - Add support for create, update, and delete REST endpoints
* Language - 1 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

= [4.5.13] 2017-09-20 =

* Feature - Remove 'France, Metropolitan' option from country list to prevent issues with Google Maps API (thanks @varesanodotfr for pointing this out) [78023]
* Fix - Prevents breakages resulting from deprecated filter hooks
* Tweak - Added an id attribute to dropdowns generated by the Fields API [spotfix]
* Fix - Prevents resetting selected Datatables rows when changing pages (thanks @templesinai for reporting) [88437]

= [4.5.12] 2017-09-06 =

* Fix - Added check to see if log directory is readable before listing logs within it (thank you @rodrigochallengeday-org and @richmondmom for reporting this) [86091]
* Tweak - Datatables Head and Foot checkboxes will not select all items, only the current page [77395]
* Tweak - Added method into Date Utils class to allow us to easily convert all datepicker formats into the default one [77819]
* Tweak - Added a filter to customize the list of states in the USA that are available to drop-downs when creating or editing venues.
* Language - 3 new strings added, 46 updated, 1 fuzzied, and 4 obsoleted

= [4.5.11] 2017-08-24 =

* Fix - Ensure valid license keys save as expected [84966]
* Tweak - Removing WP Plugin API result adjustments

= [4.5.10.1] 2017-08-16 =

* Fix - Fixed issue with JS/CSS files not loading when WordPress URL is HTTPS but Site URL is not (our thanks to @carcal1 for first reporting this) [85017]

= [4.5.10] 2017-08-09 =

* Fix - Added support to tribe_asset() for non-default plugin directions/usage from within the mu-plugin directory (our thanks to @squirrelandnnuts for reporting this) [82809]
* Fix - Made JSON LD permalinks overridable by all post types, so they can be filtered [76411]
* Tweak - Improve integration with the plugins API/add new plugins screen (our thanks to David Sharpe for highlighting this) [82223]
* Tweak - Improve the Select2 search experience (props to @fabianmarz) [84496]
* Language - 0 new strings added, 312 updated, 1 fuzzied, and 0 obsoleted

= [4.5.9] 2017-07-26 =

* Fix - Avoid accidental overwrite of options when settings are saved in a multisite context [79728]
* Fix - Provide a well sorted list of countries even when this list is translated (our thanks to Johannes in the forums for highlighting this) [69550]
* Tweak - Cleanup logic responsible for handling the default country option and remove confusing translation calls (our thanks to Oliver for flagging this!) [72113]
* Tweak - Added period "." separator to datepicker formats [65282]
* Tweak - Avoid noise relating to PUE checks during WP CLI requests

= [4.5.8] 2017-07-13 =

* Fix - Fixes to the plugin upgrade notice parser including support for environments where the data stream wrapper is unavailable [69486]
* Fix - Ensure the multichoice settings configured to allow no selection work as expected [73183]
* Fix - Enqueue expired notice and CSS on every admin page [81714]
* Tweak - Add helper to retrieve anonymous objects using the class name, hook and callback priority [74938]
* Tweak - Allow dependency.js to handle radio buttons. ensure that they are linked correctly. [82510]
* Fix - Allow passing multiple localize-scripts to tribe-assets. Don't output a localized scrip more than once. [81644]

= [4.5.7] 2017-06-28 =

* Fix - Made the App Shop and help pages work on Windows. [77975]
* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Fix - Avoid notices during plugin update and installation checks [80492]
* Fix - Ensure an empty dateTimeSeparator option value doesn't break tribe_get_start_date output anymore. [65286]
* Tweak - Improve navigation to elements inside admin pages (don't let the admin toolbar obscure things) [41829]
* Tweak - Textual corrections (with thanks to @garrett-eclipse) [77196]

= [4.5.6] 2017-06-22 =

* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted [tribe-common]
