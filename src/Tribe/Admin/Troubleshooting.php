<?php

/**
 * Admin Troubleshooting for TEC plugins.
 *
 * @since TBD
 *
 * @package Tribe\Admin
 */

namespace Tribe\Admin;
use \Tribe__Settings;
use \Tribe__Main;
use \Tribe__Admin__Helpers;
use \Tribe__Timezones as Timezones;
use \Tribe__Events__Google__Maps_API_Key;

/**
 * Class Admin Troubleshooting.
 *
 * @since TBD
 *
 * @package Tribe\Admin
 */
class Troubleshooting {
    /**
     * Slug of the WP admin menu item
     *
     * @since TBD
     *
     * @var string
     */
    const MENU_SLUG = 'tribe-troubleshooting';

    /**
     * The slug for the new admin page
     *
     * @var string
     */
    private $admin_page = null;

    /**
     * Class constructor
     */
    public function hook() {
        add_action( 'admin_menu', [ $this, 'add_menu_page' ], 90 );
        add_action( 'wp_before_admin_bar_render', [ $this, 'add_toolbar_item' ], 20 );
    }

    /**
     * Adds the page to the admin menu
     */
    public function add_menu_page() {
        if ( ! Tribe__Settings::instance()->should_setup_pages() ) {
            return;
        }

        $page_title = esc_html__( 'Troubleshooting', 'tribe-common' );
        $menu_title = esc_html__( 'Troubleshooting', 'tribe-common' );
        $capability = apply_filters( 'tribe_events_troubleshooting_capability', 'install_plugins' );

        $where = Tribe__Settings::instance()->get_parent_slug();

        $this->admin_page = add_submenu_page(
            $where,
            $page_title,
            $menu_title,
            $capability,
            self::MENU_SLUG,
            [
                $this,
                'do_menu_page',
            ]
        );
    }

    /**
     * Adds a link to the the WP admin bar
     */
    public function add_toolbar_item() {
        $capability = apply_filters( 'tribe_events_troubleshooting_capability', 'install_plugins' );

        // prevent users who cannot install plugins from seeing addons link
        if ( current_user_can( $capability ) ) {
            global $wp_admin_bar;

            $wp_admin_bar->add_menu( [
                'id'     => 'tribe-events-troubleshooting',
                'title'  => esc_html__( 'Event Add-Ons', 'tribe-common' ),
                'href'   => Tribe__Settings::instance()->get_url( [ 'page' => self::MENU_SLUG ] ),
                'parent' => 'tribe-events-settings-group',
            ] );
        }
    }

    /**
     * Checks if the current page is the troubleshooting page
     *
     * @since TBD
     * 
     * @var string
     *
     * @return bool
     */
    public function is_current_page() {
        if ( ! Tribe__Settings::instance()->should_setup_pages() || ! did_action( 'admin_menu' ) ) {
            return false;
        }

        if ( is_null( $this->admin_page ) ) {
            _doing_it_wrong(
                __FUNCTION__,
                'Function was called before it is possible to accurately determine what the current page is.',
                '4.5.6'
            );
            return false;
        }

        return Tribe__Admin__Helpers::instance()->is_screen( $this->admin_page );
    }

    /**
     * Renders the Troubleshooting page
     * 
     * @since TBD
     * 
     * @var string
     */
    public function do_menu_page() {
        $main = Tribe__Main::instance();
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/troubleshooting.php';
    }

    /**
     * Checks if there are any active issues
     * 
     * @since TBD
     * 
     * @var string
     */
    public function is_any_issue_active() {
        $issues = $this->get_issues_found();
        $active_issues = wp_list_pluck( $issues, 'active' );
        return in_array( true, $active_issues );
    }

    /**
     * Checks if any active TEC plugins require an update
     * 
     * @since TBD
     * 
     * @var string
     */
    public function is_any_tec_plugin_out_of_date() {
        $current = get_site_transient( 'update_plugins' );
        $plugins = [];
        if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
            $plugins[] = TRIBE_EVENTS_FILE;
        }
        if ( defined( 'EVENTS_CALENDAR_PRO_FILE' ) ) {
            $plugins[] = EVENTS_CALENDAR_PRO_FILE;
        }
        if ( defined( 'EVENT_TICKETS_PLUS_FILE' ) ) {
            $plugins[] = EVENT_TICKETS_PLUS_FILE;
        }
        if ( defined( 'EVENTS_VIRTUAL_FILE' ) ) {
            $plugins[] = EVENTS_VIRTUAL_FILE;
        }
        if ( defined( 'EVENT_TICKETS_MAIN_PLUGIN_FILE' ) ) {
            $plugins[] = EVENT_TICKETS_MAIN_PLUGIN_FILE;
        }
        if ( defined( 'TRIBE_EVENTS_FILTERBAR_FILE' ) ) {
            $plugins[] = TRIBE_EVENTS_FILTERBAR_FILE;
        }
        if ( defined( 'EVENTS_COMMUNITY_TICKETS_FILE' ) ) {
            $plugins[] = EVENTS_COMMUNITY_TICKETS_FILE;
        }
        if ( defined( 'EVENTS_COMMUNITY_FILE' ) ) {
            $plugins[] = EVENTS_COMMUNITY_FILE;
        }
        if ( defined( 'EVENTBRITE_PLUGIN_FILE' ) ) {
            $plugins[] = EVENTBRITE_PLUGIN_FILE;
        }
        if ( defined( 'TRIBE_APM_FILE' ) ) {
            $plugins[] = TRIBE_APM_FILE;
        }
        if ( defined( 'IMAGE_WIDGET_PLUS_DIR' ) ) {
            $plugins[] = IMAGE_WIDGET_PLUS_DIR;
        }
        $plugins = array_map( static function( $file ) {
            $file = \str_replace( WP_PLUGIN_DIR . '/', '', $file );
            return $file;
        }, $plugins );
        
        foreach ( $plugins as $file ) {
            if ( ! isset( $current->response[ $file ] ) ) {
                continue;
            }
            $response = $current->response[ $file ];
            if ( ! empty( $response->new_version ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if any of the issues defined are active
     * 
     * @since TBD
     * 
     * @var string
     */
    public function is_active_issue( $slug ) {
        if ( 'timezone' === $slug ) {
            return Timezones::is_utc_offset( Timezones::wp_timezone_string() );
        }
        if ( 'install-max' === $slug ) {
            // Tribe__PUE__Checker::validate_key()
        }
        if ( 'geolocation' === $slug && class_exists( 'Tribe__Events__Google__Maps_API_Key' ) ) {
            $key = \tribe_get_option( 'google_maps_js_api_key', false );
            return empty( $key ) || Tribe__Events__Google__Maps_API_Key::$default_api_key === $key ;
        }
        if ( 'out-of-date' === $slug ) {
            return $this->is_any_tec_plugin_out_of_date();
        }
        return false;
    }

    /**
     * Displays issues found in the UI
     * 
     * @since TBD
     * 
     * @var string
     */
    public function get_issues_found() {
        $issues_found = apply_filters( 'tec_help_troubleshooting_issues_found', [
            [
                'title' => __( 'Site time zone uses UTC', 'tribe-common' ),
                'description' => __( 'When using The Events Calendar, we highly recommend that you use a geographic timezone such as "America/Los_Angeles" and avoid using a UTC timezone offset such as “UTC+9”. Choosing a UTC timezone for your site or individual events may cause problems when importing events or with Daylight Saving Time. Go to your the General WordPress settings to adjust your site timezone.', 'tribe-common' ),
                'more_info' => 'http://evnt.is/1ad3',
                'resolve_text' => __( 'Adjust your timezone', 'tribe-common' ),
                'fix' => '/wp-admin/options-general.php',
                'active' => $this->is_active_issue( 'timezone' ),
            ],
            [
                'title' => __( 'Install max has been reached', 'tribe-common' ),
                'description' => __( 'License keys can only be used on a limited number of sites, which varies depending on your license level. You\'ll need to remove the license from one or more other site\'s in order to use it on this one.', 'tribe-common' ),
                'more_info' => 'https://evnt.is/1aqz',
                'resolve_text' => __( 'Manage your licenses', 'tribe-common' ),
                'fix' => 'https://evnt.is/1aq-',
                'active' => $this->is_active_issue( 'install-max' ),
            ],
            [
                'title' => __( 'Default Google Maps API key', 'tribe-common' ),
                'description' => __( 'The Events Calendar comes with an API key for basic maps functionality. If you’d like to use more advanced features like custom map pins, dynamic map loads, or Events Calendar Pro\'s Location Search and advanced Map View, you’ll need to get your own Google Maps API key and add it to Events > Settings > APIs', 'tribe-common' ),
                'more_info' => '=https://evnt.is/1aqx',
                'resolve_text' => __( 'Enter a custom API key', 'tribe-common' ),
                'fix' => '/wp-admin/edit.php?page=tribe-common&tab=addons&post_type=tribe_events',
                'active' => $this->is_active_issue( 'geolocation' ),
            ],
            [
                'title' => __( 'Plugin(s) are out of date', 'tribe-common' ),
                'description' => __( 'It\'s important to use the most recent versions of our plugins so that you have access to the latest features, bug fixes, and security updates. Plugin functionality can be comprimised if your site is running outdated or mis-matched versions.', 'tribe-common' ),
                'more_info' => 'https://evnt.is/1aqy',
                'resolve_text' => __( 'Check for updates', 'tribe-common' ),
                'fix' => '/wp-admin/update-core.php',
                'active' => $this->is_active_issue( 'out-of-date' ),
            ],
        ] );

        return $issues_found;
    }

    /**
     * Defines common troubleshooting issues and displays them in the UI
     * 
     * @since TBD
     * 
     * @var string
     */
    public function get_common_issues() {
    $commonIssues = apply_filters( 'tec_help_troubleshooting_issues', [
        [
            'issue' => __( 'Common Error Messages', 'tribe-common' ),
            'solution' => __( 'Here’s an overview of %s and what they mean.', 'tribe-common' ),
            'link' => 'https://evnt.is/1as0',
            'link_label' => 'common error messages',
        ],
        [
            'issue' => __( 'My calendar doesn’t look right.', 'tribe-common' ),
            'solution' => __( 'This can happen when other plugins try to improve performance. %s.' ),
            'link' => 'https://theeventscalendar.com/knowledgebase/k/troubleshooting-the-most-common-installation-issues/#layout-issue',
            'link_label' => 'More info',
        ],
        [
            'issue' => __( 'I installed the calendar and it crashed my site.', 'tribe-common' ),
            'solution' => __( '%s and other common installation issues.', 'tribe-common' ),
            'link' => 'https://theeventscalendar.com/knowledgebase/k/troubleshooting-the-most-common-installation-issues/#fatal-errors',
            'link_label' => 'Find solutions to this',
        ],
        [
            'issue' => __( 'I keep getting “Page Not Found” on events.', 'tribe-common' ),
            'solution' => __( 'There are a few %s to resolve and prevent 404 errors.', 'tribe-common' ),
            'link' => 'https://evnt.is/1as2',
            'link_label' => 'things you can do',
        ],
    ] );

        return $commonIssues;
    }
}