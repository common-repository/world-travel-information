<?php
defined('ABSPATH') or die('No script kiddies please!!');
    
    $Apis_calls = new Travel_apis();

    $all_countries =  $Apis_calls->country_list();

    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

    if(!class_exists('Travel_apis_tables')){

        class Travel_apis_tables extends WP_List_table{

            function __construct()
            {
                $this->screen = get_current_screen();
                echo '<h1 class="wp-heading-inline">All Shortcodes</h1>';
                $this->prepare_items();
                echo '<form method = "POST" name = "frm_search_country" action="' . $_SERVER['PHP_SELF'] . '?page=ti-info"? >';
                $this->search_box('Search Country', 'Search_country_name');
                echo '</form>';
               
                $this->display();
            }

            public function prepare_items(){

                $orderby = isset($_GET['orderby']) ? trim(sanitize_text_field($_GET['orderby'])) : "";

                $order = isset($_GET['order']) ? trim(sanitize_text_field($_GET['order'])) : "";

                $search_term = isset($_POST['s']) ? trim(sanitize_text_field($_POST['s'])) : "";

                $datas = $this->get_countrys( $orderby, $order, $search_term);               

                $per_page = 10;

                $current_page = $this->get_pagenum();

                $total_items = count( $datas );

                $this->set_pagination_args(array(
                    'total_items'   => $total_items,
                    'per_page'  => $per_page,
                ));

                $this->items = array_slice($datas, (($current_page - 1 ) * $per_page), $per_page);

                $columns = $this->get_columns();

                $hidden = array();

                $shortable = $this->get_shortable_columns();

                $this->_column_headers = array($columns, $hidden, $shortable);
            }

            public function get_columns(){

                $columns = array(
                    'name' => 'Name Of Country',
                    'code' => 'Country Code',
                    'shortcode' => 'ShortCode for post/page',
                    'shortcode_sidebar' => 'ShortCode for sidebars'

                );

                return $columns;
            }

            public function column_default($item, $column_name){

                switch ($column_name) {
                    case 'name':
                    case 'code':
                    case 'shortcode':
                    case 'shortcode_sidebar':


                        return $item[$column_name];
        
                    default:
                        return "No data available";
                        
                }
            }

            public function get_shortable_columns(){

                return array(   
                            'name' => array('name', false), 
                            // 'code' => array('code', false)
                        );
            }

            private function get_countrys($orderby = '', $order = '', $search_term = ''){

                $Apis_calls = new Travel_apis();

                $all_countries =  $Apis_calls->country_list();

                if(!empty($search_term)){

                    $filter_country =  $all_countries;
                    $all_countries = array();

                    foreach ($filter_country as $key ) {
                       
                        if (strripos($key['name'], $search_term) !== false ) {
                            
                            $all_countries [] = array(
                                                        'code' => $key['code'],
                                                        'name' => $key['name'],
                                                        'shortcode' => $key['shortcode'],  
                                                    );
                        }
                    }
                        
                }else{

                    if( $orderby == 'name' && $order == 'asc'){
                    
                        $all_countries = $this->array_sort($all_countries, 'name', SORT_ASC);
                
                    }elseif( $orderby == 'name' && $order == 'desc'){
                    
                        $all_countries = $this->array_sort($all_countries, 'name', SORT_DESC);
                    }
                }

                return $all_countries;
            }

            private function array_sort($array, $on, $order=SORT_ASC)
            {
                $new_array = array();
                $sortable_array = array();

                if (count($array) > 0) {
                    foreach ($array as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                if ($k2 == $on) {
                                    $sortable_array[$k] = $v2;
                                }
                            }
                        } else {
                            $sortable_array[$k] = $v;
                        }
                    }

                    switch ($order) {
                        case SORT_ASC:
                            asort($sortable_array);
                        break;
                        case SORT_DESC:
                            arsort($sortable_array);
                        break;
                    }

                    foreach ($sortable_array as $k => $v) {
                        $new_array[$k] = $array[$k];
                    }
                }

                return $new_array;
            }
        }

        new Travel_apis_tables();
    }

