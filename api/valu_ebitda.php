<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$valu_data = json_decode(file_get_contents('php://input'),true);
foreach ($valu_data as $key => $value) {
  if ($key == 'user_id' && $key == 'col' && $key == 'ebitda') {
    $$key = (int) $value;
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", ebitda: ".$ebitda.", demo: ".$demo."\n", 3, "error_log"); 
if ($user_id > 0 && $col > 0 && $ebitda >= 0 && $demo == 'FALSE') {
  $valuationSQL = "UPDATE `valu_cols` SET `ebitda` = ".$ebitda." WHERE `valu_cols`.`col_id` = ".$col." AND `valu_cols`.`user_id` = ".$user_id;
  $valuationvals = $wpdb->get_results($valuationSQL);
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error - ebitda: ".$ebitda); 
}
echo json_encode($arry); 