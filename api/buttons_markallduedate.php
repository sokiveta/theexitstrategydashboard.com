<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$button_info = json_decode(file_get_contents('php://input'),true);
foreach ($button_info as $key => $value) {
  if ($key == 'user_id') {
    $$key = (int) $value;
  } elseif ($key == 'duedate') {
    $$key = date("Y-m-d", strtotime($value));
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", task_id: ".$tasksId.", demo: ".$demo."\n", 3, "error_log");
if ($user_id > 0 && is_array($task_ids) && $demo == 'FALSE') {
  foreach ($task_ids as $task_id) {              
    $taskSQL = "UPDATE `tasks` SET `task_edate`='".$duedate."' WHERE `task_id`=".$task_id." AND `user_id`=".$user_id;
    error_log("taskSQL: ".$taskSQL."\n", 3, "error_log");
    $taskResults = $wpdb->get_results($taskSQL);
  }
  $arry = array ("Message" => "Success");
} else {
  $arry = array ("Message" => "Error");
}
echo json_encode($arry);