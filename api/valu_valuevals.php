<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$valu_data = json_decode(file_get_contents('php://input'),true);
foreach ($valu_data as $key => $value) {
  if ($key == 'user_id' && $key == 'col_id' && $key == 'row_id' && $key == 'value') {
    $$key = (int) $value;
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", value: ".$value.", demo: ".$demo."\n", 3, "error_log"); 
if ($value == "") { $value = 0; }
if ($user_id > 0 && $col_id > 0 && $row_id > 0 && $value >= 0 && is_numeric($value) && $demo == 'FALSE') {
  $valuationSQL = "UPDATE `valu_vals` SET `value` = ".$value." WHERE `valu_vals`.`col_id` = ".$col_id." AND  `valu_vals`.`row_id` = ".$row_id." AND `valu_vals`.`user_id` = ".$user_id;
  $valuationvals = $wpdb->get_results($valuationSQL);
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error - valuevals: ".$value); 
}
echo json_encode($arry);