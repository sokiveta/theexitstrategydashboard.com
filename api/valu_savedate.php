<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$valu_data = json_decode(file_get_contents('php://input'),true);
foreach ($valu_data as $key => $value) {
  if ($key == 'user_id' && $key == 'col') {
    $$key = (int) $value;
  } elseif ($key == 'date') {
    $$key = date("Y-m-d", strtotime($value));
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", difference: ".$difference.", demo: ".$demo."\n", 3, "error_log"); 
if ($user_id > 0 && $col > 0 && $date != "" && $demo == 'FALSE') {
  $valuationSQL = "UPDATE `valu_cols` SET `date` = '".$date."' WHERE `valu_cols`.`col_id` = ".$col." AND `valu_cols`.`user_id` = ".$user_id;
  $valuationvals = $wpdb->get_results($valuationSQL);
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error - savedate: ".$date); 
}
echo json_encode($arry);