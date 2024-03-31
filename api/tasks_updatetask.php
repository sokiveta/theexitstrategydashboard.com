<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}

$task_info = json_decode(file_get_contents('php://input'),true);
foreach ($task_info as $key => $value) {
  // error_log($key.": ".$value."\n", 3, "error_log");
  $$key = addslashes($value);
  if ($key == 'user_id' || $key == 'tasksId' || $key == 'employee_id') {
    $$key = (int) $value;
  } elseif ($key == 'task_edate') {
    $$key = date("Y-m-d", strtotime($value));
  } else {
    $$key = addslashes($value);
  } 
} 

// error_log("user_id: ".$user_id.", task_id: ".$tasksId.", demo: ".$demo."\n", 3, "error_log");
if ($user_id > 0 && $tasksId > 0 && $demo == 'FALSE') {   

  if ($task_status == 'checked') {
    $statSQL = "UPDATE `tasks` SET `status_id`=5 WHERE `task_id`=".$tasksId." AND `user_id`=".$user_id;
  } else {
    $statSQL = "UPDATE `tasks` SET `status_id`=3 WHERE `task_id`=".$tasksId." AND `user_id`=".$user_id;
  }
  
  // error_log("statSQL: ".$statSQL."\n", 3, "error_log");
  $statResults = $wpdb->get_results($statSQL);
  $taskSQL = "UPDATE `tasks` SET 
    `task_edate`='".$task_edate."', 
    `task_description`='".$task_description."', 
    `employee_id`='".$employee_id."', 
    `task_location`='".$task_location."'
    WHERE `task_id`=".$tasksId." AND `user_id`=".$user_id;
  
  // error_log("taskSQL: ".$taskSQL."\n", 3, "error_log");
  $taskResults = $wpdb->get_results($taskSQL);
  $arry = array (
    "Message" => "Success",
    "user_id" => $user_id,
    "task_id" => $tasksId,
    "task_edate" => $task_edate,
    "task_description" => $task_description,
    "employee_id" => $employee_id,
    "task_location" => $task_location
  );

} else {
  $arry = array ("Message" => "Error");
}
echo json_encode($arry);