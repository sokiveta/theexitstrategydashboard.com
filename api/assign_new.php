<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$user_info = json_decode(file_get_contents('php://input'),true);  
foreach ($user_info as $key => $value) {
  // error_log($key.": ".$value."\n", 3, "error_log");
  $$key = ($key == 'user_id') ? (int) $value : addslashes($value);
}
$today = date('Y-m-d');
if ($user_id > 0 && $employee_name != "" && $demo == 'FALSE') {    
  $sql = "INSERT INTO `task_employees` (
  `employee_name`,`employee_email`,`employee_position`,`employee_address`,`employee_phone`,`employee_mobile`,`employee_status`,`employee_date_added`,`employee_date_edited`,`user_id`,`department_id`
  ) VALUES (
  '".$employee_name."','".$employee_email."','".$employee_position."','','".$employee_phone."','',1,'".$today."','".$today."','".$user_id."',1
  )";
  $insert = $wpdb->get_results($sql);
  $row_id = $wpdb->insert_id;
  $arry = array (
    "Message" => "Success",
    "user_id" => $user_id,
    "employee_name" => $employee_name,
    "employee_email" => $employee_email,
    "employee_position" => $employee_position,
    "employee_phone" => $employee_phone,
    "today" => $today
  );
} else {
  $arry = array ("Message" => "Error");
}
echo json_encode($arry);