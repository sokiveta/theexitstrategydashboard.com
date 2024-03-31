<?php
// Assignments
add_shortcode('assignments', 'assignments');
function assignments () {
  global $wpdb;
  global $current_user;
  global $localpath;  
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }	
  $assignments = "
  <div id='addnewform' style='display: none;'>
    <h4>Add New Person</h4>
    <input type='text' name='employee_name' id='new_employee_name' placeholder='Name' /><br />
    <input type='text' name='employee_position' id='new_employee_position' placeholder='Position' /><br />
    <input type='text' name='employee_email' id='new_employee_email' placeholder='Email' /><br />
    <input type='text' name='employee_phone' id='new_employee_phone' placeholder='Phone' /><br />
    <button id='cancelnew'>Cancel</button> <button id='savenew'>Save</button>
  </div>

  <div class='flexbox-container'>";
  $usersql = "SELECT * FROM `task_employees` WHERE employee_status=1 AND user_id = ".$user_id." ORDER BY employee_name";
  $uservals = $wpdb->get_results($usersql);
  foreach ( $uservals as $userval ) {
	  
    $taskssql = "SELECT * FROM `tasks` WHERE user_id = ".$user_id." AND employee_id = ".$userval->employee_id." ORDER BY status_id";
    $tasks = $wpdb->get_results($taskssql);
    $assignments .= "<div class='flexbox-item'>";

      $assignments .= "<div id='box_".$userval->employee_id."'>";
        $assignments .= "<a href='#' class='teamedit'><img src='".$localpath."/wp-content/uploads/2023/11/team-icon.png' style='margin-right: 10px;' id='iconedit_".$userval->employee_id."' />";
        $assignments .= "<strong><span id='nameedit_".$userval->employee_id."'>".$userval->employee_name."</span></strong></a><br />";
        $assignments .= ($userval->employee_position != "") ? $userval->employee_position."<br />" : "";
        $assignments .= ($userval->employee_email != "") ? "<a href='mailto:".$userval->employee_email."'>".$userval->employee_email."</a><br />" : "";
        $assignments .= ($userval->employee_phone != "") ? "<a href='tel:".$userval->employee_phone."'>".$userval->employee_phone."</a><br />": "";

        $assignments .= "<button id='taskp_".$userval->employee_id."' class='taskp assignedtasks'><img src='".$localpath."/wp-content/uploads/2023/11/blue-clipboard-10x12-1.png' id='taskicon_".$userval->employee_id."'> <span id='tasklink_".$userval->employee_id."'>View Assigned Tasks</span></a></button>";

        $assignments .= "</div>";

        $assignments .= "<div id='edit_".$userval->employee_id."' style='display: none;'>";
        $assignments .= "<strong>Edit Success Team Member</strong><br />";
        $assignments .= "<input type='text' class='employee_edit' id='employee_name_".$userval->employee_id."' name='employee_name' placeholder='Name' value='".$userval->employee_name."' /><br />";
        $assignments .= "<input type='text' class='employee_edit' id='employee_position_".$userval->employee_id."' name='employee_position' placeholder='Company Position' value='".$userval->employee_position."' /><br />";
        $assignments .= "<input type='text' class='employee_edit' id='employee_email_".$userval->employee_id."' name='employee_email' placeholder='Email' value='".$userval->employee_email."' /><br />";
        $assignments .= "<input type='text' class='employee_edit' id='employee_phone_".$userval->employee_id."' name='employee_phone' placeholder='Phone' value='".$userval->employee_phone."' /><br />";
        $assignments .= "&nbsp; <label><input type='checkbox' name='employee_remove' id='employee_remove_".$userval->employee_id."' value='remove_".$userval->employee_id."' /> Remove ".$userval->employee_name."</label><br /><br />";
        $assignments .= "<button class='canceledit'  id='cancel_".$userval->employee_id."'>Cancel</button> <button class='saveedit' id='save_".$userval->employee_id."'>Save</button>";
      $assignments .= "</div>";

      $assignments .= "<div id='tlist_".$userval->employee_id."' style='display: none;'>";

      $assignments .= "<button id='taskp_".$userval->employee_id."' class='taskp assignedtasks taskpclose' style='overflow: hidden;'><img src='".$localpath."/wp-content/uploads/2023/11/x13.png' id='taskicon_".$userval->employee_id."'> <span id='tasklink_".$userval->employee_id."'> Close Assigned Tasks</span></button> ";

      if (count($tasks) > 0){

        foreach ( $tasks as $task ) {
          $assignments.= "<div class='tasklist'>";
          $assignments.= "<input type='checkbox' id='editchckbx_".$task->task_id."' class='editchckbx' /> <strong>[".$task->task_id."] Ch.".$task->chapter_id." ".$task->task_title."</strong>";
	  
			if ($task->task_edate != "0000-00-00") {
				$taskedate = strtotime($task->task_edate);
			} else {
				$taskedate = time();
			}
	  
			$todaydate = time();
			$pastdue_message = "";
			if ($task->status_id != "") {
	  			$status_id = (int) $task->status_id;
			} else {
	  			$status_id = 1;
			}
			if ($status_id < 5) {
				if ($todaydate > $taskedate) {
					// past due
					$pastdue_message = "Past ";
					$pastdue_style = " assigntaskpastdue";
				} else {
					// not past due
					$pastdue_style = " assigntaskcurrent";
				}
				if ($task->task_edate != "0000-00-00") {
					$due_date_text = $pastdue_message."Due: ".date("M j, Y", $taskedate);
				} else {
					$due_date_text = "No due date set";
				}
			} else {
				$due_date_text = "Completed";
				$pastdue_style = " assigntaskdone";
			}

          $assignments.= "<div class='duedate".$pastdue_style."'><strong>Status:</strong> ";
          $assignments.= $due_date_text;
          $assignments.= "</div>";
          $assignments.= "<div class='assigntaskdesc'><strong>Description:</strong> " .$task->task_description."</div>";
          $assignments.= "</div>";
        }
      } else {
        $assignments.="<p>No tasks.</p>";
      }

      $assignments .= "</div>";

      $assignments .= "</div>";

  }
  $assignments .= "</div>";

  return $assignments;
}

