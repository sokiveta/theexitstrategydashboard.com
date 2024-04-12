<?php
// Chapters Pages and Task MGNT
function tasks_table ($task, $type) {
  global $wpdb;
  global $current_user;
  global $localpath;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  } 
  $employee_ids = $wpdb->get_results("SELECT * FROM `task_employees` WHERE user_id=".$user_id." AND employee_status=1");
  foreach ( $employee_ids as $employeeid ) {
    $employee_name[$employeeid->employee_id] = $employeeid->employee_name;
  }
    
  $task_chapters = $wpdb->get_results("SELECT * FROM `task_chapters`");
  foreach ( $task_chapters as $task_chapter ) {
    $chapter_name[$task_chapter->chapter_number] = $task_chapter->chapter_title;
  }

  $tasklist = "<div class='taskcard' id='taskcontainer_".$task->task_id."'>";

  if ($type == "completed" || $task->status_id == '5') {
    $duedate = "Completed";
    // $chckbx  = "<br />";
    $checkedcompleted = "checked";
  } else {
    $duedate = ($task->task_edate != "0000-00-00") ? "Due: ".date("m/d/Y", strtotime($task->task_edate)) : "No due date set";
    $checkedcompleted = " ";
  }

  $tasklist.= "<div class='task-container task-date-".$type."'>
    <div class='task-id'><label><input type='checkbox' id='editchckbx_".$task->task_id."' class='editchckbx' /> <strong>".$task->chapter_id.".".$task->chapter_count." - ".$task->task_title."</strong></label></div> 
    <div class='task-due'><strong>".$duedate."</strong></div>";
  $tasklist.= "</div>";

  $tasklist.= "<div class='task-desc'>";
    if ($_SERVER['REQUEST_URI'] == $localpath."/task-mgnt/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/task-mgnt-demo/") {
      $tasklist.= "<nobr>(Ch.".$task->chapter_id.": ".$chapter_name[$task->chapter_id].")</nobr><br />";
    }
    $tasklist.= $task->task_description;
  $tasklist.= "</div>";

  $tasklist.= "<div class='task-loca'>";  
    $employee_assigned = ($task->employee_id > 0) ? $employee_name[$task->employee_id] : "no one";
    $tasklist.= "Assigned to ".$employee_assigned."<br /><br />";
    $tasklist.= "Location: ";
    $tasklist.= ($task->task_location == "") ? "No location set<br />" : "<a href='".$task->task_location."' target='_blank'>".$task->task_location."</a>";
  $tasklist.= "</div>";

  $tasklist.= "<div class='edittaskbtndiv'>
    <button class='edittaskbtn' id='edit_".$task->task_id."'>Edit</button>
  </div>";

  $tasklist.= "</div>";

  // EDIT
  $editduedate = ($task->task_edate != "0000-00-00") ? $task->task_edate : ""; // date("m/d/Y", strtotime($task->task_edate))
  $tasklist.= "<div class='task-container task-edit' id='taskedit_".$task->task_id."' style='display: none;'>";
  $tasklist.= "<strong>Edit Task: ".$task->task_id."</strong><br />
  Subject: ".$task->task_title."<br />
  Chapter: ".$chapter_name[$task->chapter_id]."<br />
  <br />
  Due Date: <input type='date' class='edittaskedate' id='edate_".$task->task_id."' value='".$editduedate."' /><br />
  <br />
  Description: <br />
  <textarea rows='5' id='taskdesc_".$task->task_id."'>".$task->task_description."</textarea><br />
  <br />
  Assigned to: <br />
  <select id='empid_".$task->task_id."'>
  <option value=''>Select One</option>";
  foreach ( $employee_ids as $employeeid ) {
    $tasklist.="<option value='".$employeeid->employee_id."'";
    $tasklist.= ($employeeid->employee_id == $task->employee_id) ? " selected" : "";
    $tasklist.=">".$employeeid->employee_name."</option>";
  }
  $taskloca = ($task->task_location == "") ? "No location set" : $task->task_location;
  $tasklist.= "</select><br />
  <!-- <a href='#'>Add new person to Success Team</a><br /> -->
  <br />
  Location in Pre-Data Room or Data Room (URL, location description, etc):<br />
  <input type='text' id='tlocation_".$task->task_id."' value='".$taskloca."' style='width: 90%;' /><br />
  <br />
  <label><input type='checkbox' id='teditcomp_".$task->task_id."' class='chckbxedit' ".$checkedcompleted." /> Completed?</label><br />  
  <div class='editbuttons'>
    <button class='canceledit' id='taskeditcancel_".$task->task_id."'>Cancel</button>
    <button class='saveedit' id='taskeditsave_".$task->task_id."'>Save</button>
  </div>
  <br />
  </div>";

  return $tasklist;
}

// Add New Task
add_shortcode('newtaskbox', 'new_task_box');
function new_task_box ($atts) {
  global $wpdb;
  global $current_user;  
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  

  $employee_ids = $wpdb->get_results("SELECT employee_id, employee_name  FROM `task_employees` WHERE user_id=".$user_id." AND employee_status=1");
  $employee_select = "<strong>Assigned To:</strong> <select id='newtaskemployee'>
  <option value=''>Select One</option>";
  foreach ( $employee_ids as $employeeid ) {
    $employee_select.="<option value='".$employeeid->employee_id."'>".$employeeid->employee_name."</option>";
  }
  $employee_select.= "</select><br /><br />";

  if ($atts['chapter_id'] != "") {
    $chapter_select = "<strong>Chapter ".$atts['chapter_id'].": ".$atts['chapter_name']."</strong><br />
    <input type='hidden' id='newtaskchapter' value='".$atts['chapter_id']."' />";
  } else {
    $chapter_ids = $wpdb->get_results("SELECT chapter_number, chapter_title  FROM `task_chapters`");
    $chapter_select = "<strong>Chapter</strong> <select id='newtaskchapter'>
    <option value=''>Select One</option>";
    foreach ( $chapter_ids as $chapterid ) {
      $chapter_select.="<option value='".$chapterid->chapter_number."'>".$chapterid->chapter_title."</option>";
    }
    $chapter_select.= "</select><br /><br />";
  }

  $newtaskbox = "<div id='addnewtaskbox' style='display: none;'>
    <div class='grid-container'>
      <div class='tasks_table_head' id='tasks_new'>New Task</div>
      <div class='tasks_table'>
        ".$chapter_select."
        <br />
        <strong>Task Subject:</strong> <input type='text' name='tasksubject' id='newtasksubject' /><br />
        <br />
        <strong>Task Due Date:</strong> <input type='date' name='taskdate' id='newtaskdate' /><br />
        <br />
        <strong>Task Description:</strong><br />
        <textarea id='newtaskdescription'></textarea><br />
        <br />
        ".$employee_select."
        <strong>Location</strong> in Pre-Data Room or Data Room (URL, location description, etc):<br /><input type='text' name='tasklocation' id='newtasklocation' style='width: 90%;' /><br />
        <br />        
        <div class='editbuttons'>
          <button class='cancelnew' id='cancelnewtaskbtn'>Cancel</button>    
          <button class='savenew' id='savenewtaskbtn'>Save</button>
        </div>
      </div>
    </div>    
  </div>";
  return $newtaskbox;
}

// All tasks
add_shortcode('tasksall', 'tasks_all');
function tasks_all ($atts) {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $all_task_table = "";
  $tasksql = "SELECT * FROM tasks WHERE user_id = ".$user_id." AND status_id!=6 AND chapter_id=".$atts['chapter']." ORDER BY chapter_count ASC";
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $all_task_table .= tasks_table($task, "all");
  }
  return $all_task_table;
}

// Incomplete tasks
add_shortcode('tasksincomplete', 'tasks_incomplete');
function tasks_incomplete ($atts) {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $overdue_date = date('Y-m-d');
  $incomplete_task_table = "";
  $tasksql = "SELECT * FROM tasks
  WHERE user_id = ".$user_id." AND status_id!=5 AND status_id!=6 AND chapter_id=".$atts['chapter']." AND
  (task_edate >= '".$overdue_date."' OR task_edate = '0000-00-00') ORDER BY task_edate ASC"; 
  // $tasksql = "SELECT tasks.* FROM tasks LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE tasks.user_id = ".$current_user->ID." AND task_status.status_id!=5 AND task_status.status_id!=6 AND tasks.chapter_id=".$atts['chapter']." AND (tasks.task_edate >= '".$overdue_date."' OR tasks.task_edate = '0000-00-00') ORDER BY tasks.task_edate ASC"; // AND task_status.status_id!=1 
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $incomplete_task_table .= tasks_table($task, "incomplete");
  }
  return $incomplete_task_table;
}

// Completed tasks
add_shortcode('taskscompleted', 'tasks_completed');
function tasks_completed ($atts) {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $completed_task_table = "";
  $tasksql = "SELECT * FROM tasks
  WHERE user_id = ".$user_id." AND status_id=5 AND chapter_id=".$atts['chapter']."
  ORDER BY task_edate ASC";
  // $tasksql = "SELECT tasks.* FROM tasks LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE tasks.user_id = ".$current_user->ID." AND task_status.status_id=5 AND tasks.chapter_id=".$atts['chapter']." ORDER BY tasks.task_edate ASC";
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $completed_task_table .= tasks_table($task, "completed");
  }
  return $completed_task_table;
}

// Overdue tasks
add_shortcode('tasksoverdue', 'tasks_overdue');
function tasks_overdue ($atts) {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $overdue_task_table = "";
  $overdue_date = date('Y-m-d');
  $tasksql = "SELECT * FROM tasks
  WHERE user_id = ".$user_id." AND status_id!=5 AND status_id!=6 AND
  task_edate < '".$overdue_date."' AND task_edate != '0000-00-00' "; 
  // $tasksql = "SELECT tasks.* FROM tasks LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE tasks.user_id = ".$current_user->ID." AND task_status.status_id!=5 AND task_status.status_id!=6 AND tasks.task_edate < '".$overdue_date."' AND tasks.task_edate != '0000-00-00' "; // AND task_status.status_id!=1
  if (isset($atts['chapter']) && $atts['chapter'] > 0) {
    $tasksql .= " AND chapter_id=".$atts['chapter']." ";
  }
  $tasksql .= " ORDER BY task_edate ASC";
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $overdue_task_table .= tasks_table($task, "overdue");
  }
  return $overdue_task_table;
}

// Seven Days
add_shortcode('taskssevendays', 'tasks_sevendays');
function tasks_sevendays () {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $today_date = date('Y-m-d');
  $sevendays_task_table = "";
  $insevendays_date = date('Y-m-d', strtotime('+7 days'));
  $tasksql = "SELECT * FROM `tasks`
  WHERE user_id = ".$user_id." AND status_id!=5 AND status_id!=6 AND
  task_edate <= '".$insevendays_date."' AND task_edate > '".$today_date."' AND task_edate != '0000-00-00'
  ORDER BY task_edate ASC";
  // $tasksql = "SELECT tasks.* FROM `tasks`LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE tasks.user_id = ".$current_user->ID." AND task_status.status_id!=5 AND task_status.status_id!=6 AND tasks.task_edate <= '".$insevendays_date."' AND tasks.task_edate > '".$today_date."' AND tasks.task_edate != '0000-00-00' ORDER BY tasks.task_edate ASC";
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $sevendays_task_table .= tasks_table($task, "sevendays");
  }
  return $sevendays_task_table;
}

// Thirty Days
add_shortcode('tasksthirtydays', 'tasks_thirtydays');
function tasks_thirtydays () {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
  } else {
    $user_id = $current_user->ID; 
  }  
  $insevendays_date = date('Y-m-d', strtotime('+7 days'));
  $inthirtydays_date = date('Y-m-d', strtotime('+30 days'));
  $thirtydays_task_table = "";
  $tasksql = "SELECT * FROM `tasks`
  WHERE user_id = ".$user_id." AND status_id!=5 AND status_id!=6 AND
  task_edate <= '".$inthirtydays_date."' AND task_edate > '".$insevendays_date."' AND task_edate != '0000-00-00'
  ORDER BY task_edate ASC";
  // $tasksql = "SELECT tasks.* FROM `tasks` LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE tasks.user_id = ".$current_user->ID." AND task_status.status_id!=5 AND task_status.status_id!=6 AND tasks.task_edate <= '".$inthirtydays_date."' AND tasks.task_edate > '".$insevendays_date."' AND tasks.task_edate != '0000-00-00' ORDER BY tasks.task_edate ASC";
  $tasks = $wpdb->get_results($tasksql);
  foreach ( $tasks as $task ) {
    $thirtydays_task_table .= tasks_table($task, "thirtydays");
  }
  return $thirtydays_task_table;
}