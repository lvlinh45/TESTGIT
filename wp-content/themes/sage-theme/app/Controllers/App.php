<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use App\Services\Queries;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        $title = "";
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            $title = get_the_archive_title();
        }
        if (is_search()) {
            $title = sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            $title = __('Not Found', 'sage');
        }
        if (empty($title)) {
            $title = get_the_title();
        }
        return $title;
    }

    public static function getLogo()
    {
        $logo = get_field('ns_header_logo', ACF_OPTION);
        $url = ($logo && $logo['url']) ? $logo['url'] : TEMPLATE_ASSETS_URL . '/images/logo.svg';
        $alt = ($logo && $logo['alt']) ? $logo['alt'] : 'logo';
        $href = home_url();
        return compact('url', 'alt', 'href');
    }

    public static function getFooterAddress()
    {
        return get_field('ns_footer_address', ACF_OPTION);
    }

    public static function getCopyRight()
    {
        return get_field('ns_copyright', ACF_OPTION);
    }

    public static function getTrackingCode($position = '')
    {
        if ($position) {
            switch ($position) {
                case 'in_head':
                    $code = get_field('ns_tracking_head', ACF_OPTION);
                    break;
                case 'after_open_body':
                    $code = get_field('ns_tracking_after_body', ACF_OPTION);
                    break;
                case 'before_close_body':
                    $code = get_field('ns_tracking_before_body', ACF_OPTION);
                    break;
                default:
                    $code = '';
                    break;
            }
            return $code;
        }
        return '';
    }

    public static function getFavicon()
    {
        $favicon = get_field('ns_favicon', ACF_OPTION);
        return $favicon ? $favicon : '';
    }

    public static function getAppleIcon()
    {
        $icon = get_field('ns_apple_touch_icon', ACF_OPTION);
        return $icon ? $icon : '';
    }
    /**
     * Get content 404.
     *
     * @return string
     */
    public static function getContent404()
    {
        return Queries::getOptionField('ns_404_page_content');
    }

    /**
     * Get Main Menu
     */
    public static function getMainNav()
    {
        $location = 'primary_navigation';
        if (has_nav_menu($location)) {
            return wp_nav_menu(array(
                THEME_LOCATION => $location,
                CONTAINER => false,
                DEPTH => 2,
                WALKER => new \App\Services\Nav\C8ThemeHeaderMenu(),
                'menu_class' => 'main-menu-ul navbar-nav list-none flex mb-0 p-0 text-white flex-col text-inherit
                lg:flex-row lg:justify-end',
                ECHO_TEXT => false,
            ));
        } else {
            return '';
        }
    }
    /**
     * Get Footer Menu
     */
    public static function getFooterNav()
    {
        $location = 'footer_navigation';
        if (has_nav_menu($location)) {
            return wp_nav_menu(array(
                THEME_LOCATION => $location,
                ITEMS_WRAP => '%3$s',
                CONTAINER => false,
                DEPTH => 2,
                WALKER => new \App\Services\Nav\C8ThemeFooterMenu(),
                ECHO_TEXT => false,
            ));
        } else {
            return '';
        }
    }
}
