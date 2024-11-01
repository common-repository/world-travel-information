<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
/**
 * Plugin Name:       World Travel Information
 * Plugin URI:        https://pintrail.co/plugins/world-travel-information
 * Description:       World Travel Information plugin lets you display the latest information and requirements to travel anywhere in the world. We aggregate data from various sources around the web and bring travelers with information like pandemic travel restrictions, visa, and permit information, etc. Our plugin is built for anyone who has a travel related blogs and websites. 
 * Version:           1.0.0
 * Requires at least: 5.4
 * Requires PHP:      5.4
 * Author:            AmazingDigital
 * Author URI:        https://amazingdigital.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       travel_info
 * Domain Path:       /languages
 */

if ( !class_exists( 'Tinformation_info' ) ) {

    class Tinformation_info {

        function __construct() {
            
            $this->define_constants();
            
            $this->required_files();

            add_action( 'admin_menu', array( $this, 'ti_admin_menu' ) );

            add_shortcode('Travel-info',  array($this,'travel_info_shot') );

            add_action('wp_enqueue_scripts', array($this,'css_layouts') );

        }


        function travel_info_shot( $atts, $content = null ) {
        
            extract(shortcode_atts(array(
                'country' => 'us',
                'layout' => 'default',
                'popup' => 'none',
                'sidebar' => 'no',

            ), $atts));
            
            $Apis_calls = new Travel_apis();

            $user_country = $Apis_calls->get_user_country();

            $layouts = new Travel_layouts($sidebar);

            $data = $Apis_calls->get_travel_restriction_details($country, $user_country);
            
            $layout = $layouts->layout_select($data, $user_country, $layout, $popup, $sidebar);
            
            return $layout;
        }

        function define_constants() {

            defined( 'TINFO_PATH' ) or define( 'TINFO_PATH', plugin_dir_path( __FILE__ ) );
            defined( 'TINFO_URL' ) or define( 'TINFO_URL', plugin_dir_url( __FILE__ ) );
            defined( 'TINFO_VERSION' ) or define( 'TINFO_VERSION', '1.0.0' );
        }

       

        function required_files(){

            include( TINFO_PATH . 'admin/ApisCalls/Apis.php');
            include( TINFO_PATH . 'includes/class/Layouts.php');
            include( TINFO_PATH . 'includes/views/designs/default.php');

        }

        function ti_admin_menu(){

            add_menu_page( 'Travel Information', 'Travel Info', 'manage_options', 'ti-info', array( $this, 'ti_homepage' ), 'dashicons-share' );
            
            // add_submenu_page('ti-info', 'Setting', 'Setting Page', 'manage_options', 'setting', array( $this, 'ti_settings_page' ), 1);
        }

        function ti_settings_page(){

            include( TINFO_PATH . 'admin/setting.php');
        }

        function ti_homepage(){

            include( TINFO_PATH . 'admin/list-all-shortcodes.php');
        }


        function css_layouts() {

            wp_register_style( 'post', TINFO_URL . 'assets/css/post.css');

            wp_register_style( 'sidebar', TINFO_URL . 'assets/css/sidebar.css');

        }
    }

    new Tinformation_info();
}
