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
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", task_id: ".$task_ids.", demo: ".$demo."\n", 3, "error_log"); 
if ($user_id > 0 && is_array($task_ids) && $demo == 'FALSE') {
  $headers = [];
  $headers[] = 'Content-Type: text/html; charset=UTF-8';
  if ($email_from != "") { $headers[] = "From: ".$email_from; }
  if ($cc != "") { $headers[] = "Cc: ".$cc; }
  if ($bcc != "") { $headers[] = "Bcc: ".$bcc; }
  $employee_ids = $wpdb->get_results("SELECT * FROM `task_employees` WHERE user_id=".$current_user->ID." AND employee_status=1");
  foreach ( $employee_ids as $employeeid ) {
    $employee_name[$employeeid->employee_id] = $employeeid->employee_name;
  }
  $task_chapters = $wpdb->get_results("SELECT * FROM `task_chapters`");
  foreach ( $task_chapters as $task_chapter ) {
    $chapter_name[$task_chapter->chapter_number] = $task_chapter->chapter_title;
  }
  // loop through checked boxes, get task Id for each
  $selectedtasks = "";
  // $checkedtasks  = $_POST['checkedtasks'];
  $ctlength = count($task_ids);
  for ($i = 0; $i < $ctlength; $i++) {
    $selectedtasks.="`task_id`=".$task_ids[$i];
    if ($i != $ctlength -1) {
      $selectedtasks.=" OR ";
    }
  }
  // query all tasks in that array
  $taskssql = "SELECT * FROM `tasks` WHERE `user_id` = ".$user_id." AND (".$selectedtasks.")";
  $tasks = $wpdb->get_results($taskssql);
  if (count($tasks) > 0) {
    foreach ( $tasks as $task ) {      
      $message.= "<div style='margin: 10px;'><strong>[".$task->chapter_id.".".$task->chapter_count."] Task: ".$task->task_title."</strong><br />";
      $message.= "Status: "; 
      if ($task->task_edate != "0000-00-00") { 
        $message.= "Due: ".date("M j, Y", strtotime($task->task_edate));
      } else { 
        $message.= "No due date set"; 
      }
      $message.= "<br />";
      $message.= "Assigned to " .$employee_name[$task->employee_id];
      $message.= "<br />";
      $message.= "Description: " .$task->task_description;
      $message.= "</div>";
    }
  }
  wp_mail( $to, $subject, $message, $headers ); 
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error"); 
}
echo json_encode($arry); 