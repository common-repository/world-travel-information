<?php 
defined('ABSPATH') or die('No script kiddies please!!');

    if(!class_exists('travel_designs')){
        /**
         * 
         */
        class travel_designs 
        {
            
            function design_one($data, $user_country){

                if(!isset($data->arrivalTestStatus->status)){ 

                    if(empty($data->arrivalTestStatus)){ 
                        $requird =  "not"; 
                    }else{
                        $requird =  "";
                    }

                    $arrivaltest = 'Most Visitors from ' . sanitize_text_field($user_country->geoplugin_countryName) . ' are '
                    . $requird . ' required to present a negative COVID-19 PCR test or antigen result upon entering '. sanitize_text_field($data->countryName) .'.';

                } else{

                    $arrivaltest = 'Most Visitors from the '. $user_country->geoplugin_countryName .' must present a negative RT-PCR (NAAT) test taken '. $data->arrivalTestStatus->messageId .' hours before departing to '. $data->countryName .'.';
                } 

                if(empty($data->arrivalQuarantineStatus)){

                    $qurantine_status = 'will not required for quarantine.';
                
                }else{
                   
                    $qurantine_status = 'will required for ' . $data->arrivalQuarantineStatus->messageId . ' days';
                }

                if(!isset($data->returnTestStatus->status)){

                    if(empty($data->returnTestStatus)){ 
                        
                        $test_status= "not";
                    }else{
                        $test_status= "";
                    }

                    $return_test = 'Travelers returning from '. $data->countryName .' are
                    '. $test_status .' required to present a negative COVID-19
                        PCR test or antigen result upon entering '. $user_country->geoplugin_countryName .'.';
                } else{ 

                    $return_test = 'Travelers returning from '. $data->countryName .' must present a negative RT-PCR (NAAT) test taken '. $data->returnTestStatus->messageId .' hours before departing to '. $user_country->geoplugin_countryName .'.';
                } 


                if(empty($data->returnQuarantineStatus)){

                    $return_qurantine_status = 'Visitors returning from ' . $data->countryName  . ' are not required to quarantine upon entering the ' . $data->countryName . '.';

                    
                }else{
                    $return_qurantine_status = 'Visitors returning from ' . $data->countryName . ' will need to quarantine for '. $data->returnQuarantineStatus->messageId . ' days upon entering ' . $user_country->geoplugin_countryName . '.';
                }

                if(!empty($data->maskStatus)){ 

                    $maskStatus =   '<div class="transport">
                                        <span><img class="icon" src="'. TINFO_URL . 'assets/images/transport.svg" />'. $data->maskStatus->status .' in public spaces.</span>
                                    </div>';

                }else{ 

                    $maskStatus =   '<div class="transport">
                                        <span><img class="icon" src="'. TINFO_URL . 'assets/images/transport.svg" />Mask Not Required.</span>
                                    </div>';
                }      

                if(!is_null($data->restaurantStatus)){ 
                                                
                    $restaurantStatus = '<div class="resturant">
                                            <span><img class="icon" src="'. TINFO_URL . 'assets/images/resturant.svg" />'. $data->restaurantStatus .'</span>
                                        </div>';

                }else{
                    $restaurantStatus = '';
                }

                if(!is_null($data->barStatus)){ 

                    $barStatus =    '<div calss="bar">
                                        <span><img class="icon" src="'. TINFO_URL . 'assets/images/bar.svg" /> '. $data->barStatus .' </span>
                                    </div>';
                
                }else{
                     $barStatus = "";
                }


                $design =   '<section class="ti_section">
                                <div class="ti_container">

                                    <div class="ti_header">
                                        <span class="ti_restrictions_text">'. $data->countryName .' Travel Restrictions</span>
                                        <span class="ti_restrictions">'. $data->borderStatus .'</span>
                                    </div>

                                    <span class="ti_description">
                                        Traveling to <strong>'. $data->countryName .'</strong> from <strong>'. $user_country->geoplugin_countryName .'</strong>? Keep these COVID-19 guidelines in mind.
                                    </span>

                                    <div class="ti_number_description">

                                        <div class="ti_number_one">
                                            
                                            <div class="vaccine_info">
                                                <img class="vaccine" src="' . TINFO_URL . 'assets/images/one.svg" />
                                                <span class="icon_text"> '. $data->currentWeekVaccinatedPercent .'% people are Vaccinated </span>
                                            </div>
                                            
                                            <div class="virus_info">
                                            <img class="virus" src="'. TINFO_URL . 'assets/images/two.svg" />
                                            <span class="icon_text"> '. number_format((float)$data->activeCases, 2, ".", "") .' of 100000 people are COVID-19
                                                positive</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="ti_travel_description">

                                        <div class="ti_travel_to">

                                            <span class="title">Travel to '. $data->countryName .'</span>

                                            <br><span class="description">Travelling from '. $user_country->geoplugin_countryName .' to
                                                '. $data->countryName .'</span>

                                            <br><span class="details">
                                                '. $arrivaltest .' </span>

                                            <br><br><span class="details">
                                                Visitors from the '. $qurantine_status .' United States will need to quarantine for days upon
                                                entering Albania</span>
                                        </div>

                                        <div class="ti_travel_return">

                                            <span class="title">Return travel</span>

                                            <br><span class="description">Returning to the '. $data->countryName .' from
                                                '. $user_country->geoplugin_countryName .'</span>

                                            <br><span class="details">'. $return_test .'

                                                <br><br><span class="details">
                                                   '. $return_qurantine_status .'
                                                </span>
                                        </div>

                                    </div>

                                    <div class="ti_other_restrictions">
                                        <span class="title">Other restrictions:</span>
                                        <div class="details">
                                            
                                            '. $maskStatus .'  
                                            '. $restaurantStatus .'
                                            '.  $barStatus .'

                                        </div>
                                    </div>
                                </div>
                            </section>

                            ';

                return $design;
            }

        }
        
    }


