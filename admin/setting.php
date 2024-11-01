<?php
defined('ABSPATH') or die('No script kiddies please!!');
    
    if(!class_exists('Travel_settings')){

        class Travel_settings {

            function __construct()
            {
                echo esc_html('<h1 class="wp-heading-inline">All Shortcodes</h1>');
               
            }
        }

        new Travel_settings();
    }

