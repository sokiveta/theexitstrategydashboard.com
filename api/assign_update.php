<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$user_info = json_decode(file_get_contents('php://input'),true);  
foreach ($user_info as $key => $value) {
  // error_log($key.": ".$value."\n", 3, "error_log");
  $$key = ($key == 'user_id' || $key == 'employee_id' || $key == 'employee_status') ? (int) $value : addslashes($value);
}
$today = date('Y-m-d');
if ($user_id > 0 && $employee_id > 0 && $employee_name != "" && $demo == 'FALSE') {   
  $sql = "UPDATE `task_employees` SET 
  `employee_name` = '".$employee_name."',  
  `employee_position` = '".$employee_position."',  
  `employee_email` = '".$employee_email."',  
  `employee_phone` = '".$employee_phone."',  
  `employee_status` = ".$employee_status." 
  WHERE `employee_id` = ".$employee_id." AND `user_id` = ".$user_id;
  // error_log("sql: ".$sql."\n", 3, "error_log");
  $update = $wpdb->get_results($sql);
  $row_id = $wpdb->insert_id;
  $arry = array (
    "Message"           => "Success",
    "user_id"           => $user_id,
    "employee_status"   => $employee_status, 
    "employee_id"       => $employee_id,
    "employee_name"     => $employee_name,
    "employee_email"    => $employee_email,
    "employee_position" => $employee_position,
    "employee_phone"    => $employee_phone,
    "today"             => $today
  );
} else {
  $arry = array ("Message" => "Error");
}
echo json_encode($arry);