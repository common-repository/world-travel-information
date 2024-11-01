<?php
defined('ABSPATH') or die('No script kiddies please!!');

	if(!class_exists('Travel_apis')){
		/**
		 * 
		 */
		class Travel_apis 
		{
			
			function __construct()
			{
			
			}

	        function get_user_ip(){

			    // Get real visitor IP behind CloudFlare network
			    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			    }
			    $client  = @$_SERVER['HTTP_CLIENT_IP'];
			    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			    $remote  = $_SERVER['REMOTE_ADDR'];

			    if(filter_var($client, FILTER_VALIDATE_IP))
			    {
			        $ip = $client;
			    }
			    elseif(filter_var($forward, FILTER_VALIDATE_IP))
			    {
			        $ip = $forward;
			    }
			    else
			    {
			        $ip = $remote;
			    }

			    return $ip;
			}

	        function get_user_country(){

				$user_ip = $this->get_user_ip();

				$ipdat = @json_decode(file_get_contents(
				    "http://www.geoplugin.net/json.gp?ip=" . $user_ip));

				return $ipdat;
			}

			function get_travel_restriction_details( $country_code, $user_country){
 				
				$country_name = str_replace(" ","-",(strtolower($this->get_country_name( $country_code ))));

				if(!empty($user_country->geoplugin_countryCode)){
				 	$origin_code = strtolower($user_country->geoplugin_countryCode);
					
				}else{
					$origin_code = 'us';

				}

 				$url = "https://www.kayak.com/charm/horizon/uiapi/seo/marketing/travelrestrictions/CountryTravelRestrictionsAction";

			    $args = array(
			        
			        'headers' => array( "Content-type" => "application/json"),
			        'body' => array(	"countryName" => $country_name,
			    						"originCode" => $origin_code)
			    );
			    
			    $response = wp_remote_get( $url, $args );

			    $body = wp_remote_retrieve_body( $response );
			    
			    $data = json_decode($body);

			    return $data;
			
			}	

			function country_list(){
				
    			$all_countries = json_decode($this->country_json(), true);
				
				return $all_countries;
			}

			function get_country_name( $country_code ){

				$country_name = "united states";

				$all_countries = json_decode($this->country_json(), true);

				foreach ($all_countries as $key ) {
					if($key['code'] == $country_code ){
						$country_name = $key['name'];
					}
				}
				return sanitize_text_field($country_name);
			}

			function country_json(){

				return  file_get_contents(TINFO_PATH.'admin/json/countries.json');
			}
	        
	    }
		new Travel_apis();
	}