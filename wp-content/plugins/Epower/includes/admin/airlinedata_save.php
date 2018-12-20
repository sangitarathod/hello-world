<?php
if(isset($_POST['btn_airlinedata']){
	$airline_code=$_POST['airline_code'];
	$airline_icon=$_POST['airline_icon'];
	$airline_data = array($airline_code  => $airline_icon
                    );


if(get_option('airline_data') === FALSE){
    add_option('airline_data',  $airline_data );
}else{
    update_option('airline_data', $airline_data );
}

}



?>
