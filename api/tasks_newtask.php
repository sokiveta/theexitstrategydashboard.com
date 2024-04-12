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
  if ($key == 'user_id' || $key == 'chapter_id' || $key == 'employee_id') {
    $$key = (int) $value;
  } elseif ($key == 'task_edate') {
    $$key = date("Y-m-d", strtotime($value));
  } else {
    $$key = addslashes($value);
  } 
} 



$today = date("Y-m-d");
if ($user_id > 0 && $demo == 'FALSE' && $chapter_id > 0) {  

  $chapter_taskssql = "SELECT count(*) AS count FROM `tasks` WHERE `chapter_id`=".$chapter_id." AND `user_id`=".$user_id;
  $chapter_tasks = $wpdb->get_results($chapter_taskssql);
  $chapter_count = (int) $chapter_tasks[0]->count + 1;

  $taskSQL = "INSERT INTO `tasks` (
    `task_title`,
    `task_description`,
    `task_location`,
    `task_cdate`,
    `task_sdate`,
    `task_edate`,
    `task_date_edited`,
    `user_id`,
    `chapter_id`,
    `chapter_count`,
    `employee_id`,
    `task_assigned_date`,
    `status_id`
  ) VALUES (
    '".$task_title."',
    '".$task_description."',
    '".$task_location."',
    '".$today."',
    '".$today."',
    '".$task_edate."',
    '0000-00-00',
    '".$user_id."',
    '".$chapter_id."',
    '".$chapter_count."',
    '".$employee_id."',
    '".$today."',
    '3'
  )";
  $wpdb->query($taskSQL);
  
  $arry = array (
    "Message" => "Success",
    "user_id" => $user_id,
    "task_title" => $task_title,
    "task_description" => $task_description,
    "task_location" => $task_location,
    "task_cdate" => $today,
    "task_sdate" => $today,
    "task_edate" => $task_edate,
    "task_date_edited" => '0000-00-00',
    "user_id" => $user_id,
    "chapter_id" => $chapter_id,
    "employee_id" => $employee_id,
    "task_assigned_date" => $today,
    "status_id" => '3'
  );

} else {
  $arry = array ("Message" => "Error");
}
echo json_encode($arry);