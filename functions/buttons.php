<?php
// BUTTONS for checked tasks
add_shortcode('taskseditall', 'tasks_editall');
function tasks_editall () {
  global $wpdb;
  global $current_user;  
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $user_id = "3"; 
	$user_email = "webmaster@b2bcfo.com";
  } else {
    $user_id = $current_user->ID; 
	$user_email = $current_user->user_email; 
  }  
  $employee_ids = $wpdb->get_results("SELECT * FROM `task_employees` WHERE user_id=".$user_id." AND employee_status=1");

  $editall = "<div id='checkedtaskbtns'>";
  $editall.= "Checked tasks: 
      <nobr>
      <button class='saveeditallbtn checkedtaskbtn'>Mark Complete</button> 
      <button class='dateeditallbtn checkedtaskbtn'>Due Date</button>
      <button class='assigneditallbtn checkedtaskbtn'>Assign</button>
      <button class='sendeditallbtn checkedtaskbtn'>Send</button>
      <button class='removeeditallbtn checkedtaskbtn'>Remove</button>
      </nobr>
  ";
  $editall.= "</div>";
  
  $editall.= "<div id='checkedtaskselect' style='display: none;'>
    <select id='personpulldown' class='personselect'>
    <option value=''>Select a team member</option>";
    foreach ( $employee_ids as $employeeid ) {
      $editall.="<option value='".$employeeid->employee_id."'>".$employeeid->employee_name."</option>";
    }  
    $editall.= "</select>
  </div>"; 

  $editall.= "<div id='checkedtasksend' class='emailform' style='display: none;'>
    <p>Send the selected tasks in an email to the following email address. You may also include a message along with it. The email will be sent from ".$user_email."</p>
    <input type='text' class='emailval' id='report_to' placeholder='Email address' /><br />
    <input type='text' class='emailval' id='report_cc' placeholder='CC email address' /><br />
    <input type='text' class='emailval' id='report_bcc' placeholder='BCC email address' /><br />
    &nbsp; Additional message:<br />
    <textarea class='emailval' id='report_m'></textarea>
    <button class='emailcancel' id='emailcancel'>Cancel</button>
    <button class='emailsend' id='emailsend'>Send</button>    
  </div>"; 
  
  $editall.= "<div id='checkedtasksdate' style='display: none;'>
    <input type='date' id='duedateforall' class='alldatepicker' placeholder='Select Due Date' />
  </div>"; 
  
  return $editall;
}