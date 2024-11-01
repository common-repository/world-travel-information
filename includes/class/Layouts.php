<?php
defined('ABSPATH') or die('No script kiddies please!!');

	if(!class_exists('Travel_layouts')){
		/**
		 * 
		 */
		class Travel_layouts 
		{
			
			function layout_select($data, $user_country, $layout, $popup, $sidebar){

				if($layout == "default"){
            		
            		$layouts = $this->layout_one($data, $user_country, $sidebar);

            		return $layouts;
            	}

			}

			function layout_one( $data, $user_country, $sidebar){

				if($sidebar == 'no'){

            		wp_enqueue_style( 'post' );

				}else{

            		wp_enqueue_style( 'sidebar' );
				}

				$designs = new travel_designs();

				$layout = $designs->design_one($data, $user_country);

				return $layout;
			}

	    }
		
	}