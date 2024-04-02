<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$valu_data = json_decode(file_get_contents('php://input'),true);
foreach ($valu_data as $key => $value) {
  if ($key == 'user_id' && $key == 'id') {
    $$key = (int) $value;
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", difference: ".$difference.", demo: ".$demo."\n", 3, "error_log"); 
if ($user_id > 0 && $id > 0 && $label != "" && $demo == 'FALSE') {
  $valuationSQL = "UPDATE `valu_rows` SET `label` = '".$label."' WHERE `valu_rows`.`id` = ".$id." AND `valu_rows`.`user_id` = ".$user_id;
  $valuationvals = $wpdb->get_results($valuationSQL);
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error - label: ".$label); 
}
echo json_encode($arry);
