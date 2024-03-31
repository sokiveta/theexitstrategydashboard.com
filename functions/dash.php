<?php
// Dashboard
add_shortcode('bargraphdiff', 'bar_graph_diff');
function bar_graph_diff ($atts) {

  $atts = shortcode_atts( array(
      'userid' => ''
  ), $atts, 'bargraphdiff' );

  global $wpdb;
  global $current_user;

  $difference = "";
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $id = "3";
  } else {
    $id = $current_user->ID;
  }
  $compvaluesql = "SELECT difference FROM `valu_cols` WHERE user_id = ".$id ." ORDER BY date DESC LIMIT 1";
  $companyvals = $wpdb->get_results($compvaluesql);
  foreach ( $companyvals as $companyval ) {
    $difference = $companyval->difference;
  }
  if ($difference) {
    return "<div style='margin-top: 25px;'>Your company's value has increased $".number_format($difference)."</div>";
  } else {
    return "<div style='margin-top: 25px;'>Company Value</div>";
  }
}

add_shortcode('progresschart','progress_chart');
function progress_chart ($atts) {

  $atts = shortcode_atts( array(
      'userid' => ''
  ), $atts, 'progresschart' );

  global $wpdb;
  global $current_user;
  global $localpath;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $id = "3";
  } else {
    $id = $current_user->ID;
  }
  $chaptersSQL = "SELECT * FROM `task_chapters`";
  $chaptervals = $wpdb->get_results($chaptersSQL);
  $chapters = array();
  $chapter_numbers = array();
  $col_val_total = array();
  $chapterNumbs = array();
  foreach ($chaptervals as $chapterval) {
    $chapters[$chapterval->chapter_number] = $chapterval->chapter_title;
	  $chapterNumbs[$chapterval->chapter_number] = $chapterval->chapter_permalink;
    $chapter_numbers[] = $chapterval->chapter_number;
    $chapter_row[$chapterval->chapter_number]['green'] = 0;
    $chapter_row[$chapterval->chapter_number]['yellow'] = 0;
    $chapter_row[$chapterval->chapter_number]['red'] = 0;
    $chapter_row[$chapterval->chapter_number]['total'] = 0;
  }

  $tasksSQL = "SELECT task_id, chapter_id, status_id, task_edate FROM tasks WHERE user_id = ".$id;
//$tasksSQL = "SELECT task_status.task_id, tasks.chapter_id, task_status.status_id, tasks.task_edate FROM tasks LEFT JOIN task_status ON tasks.task_id=task_status.task_id WHERE task_status.user_id = ".$id;
  $tasksvals = $wpdb->get_results($tasksSQL);

  $total_tasks = 0;
  $total_completed_tasks = 0;
  $chapter_task['completed_tasks'] = 0;
  $chapter_task['incomplete_tasks'] = 0;
  $chapter_task['overdue_tasks'] = 0;
  $today = date('Y-m-d');
  if ($wpdb->num_rows > 0) {
	  foreach ($tasksvals as $tasksval) {
		// count up total tasks
		if ($tasksval->task_id) {
		  $total_tasks++;
		}
		// count up status for each chapter
		  // 1 = Inactive
		  // 3 = Started
		  // 5 = Completed
		$chapter_row[$tasksval->chapter_id]['total']++;
		if ($tasksval->status_id == '5') {
		  $chapter_row[$tasksval->chapter_id]['green']++;
		  $total_completed_tasks++;
		} elseif ($tasksval->status_id == '3' || $tasksval->status_id == '1') {
		  if (strtotime($today) > strtotime($tasksval->task_edate) && $tasksval->task_edate != "0000-00-00"){
			$chapter_row[$tasksval->chapter_id]['red']++;
		  } else {
			$chapter_row[$tasksval->chapter_id]['yellow']++;
		  }
		}
	  }
  }

  $chaptergreen = array();
  $chapteryellow = array();
  $chapterred = array();
  foreach ($chapter_numbers as $chapter_number) {
    $chaptergreen[$chapter_number] = $chapter_row[$chapter_number]['green'];
    $chapteryellow[$chapter_number] = $chapter_row[$chapter_number]['yellow'];
    $chapterred[$chapter_number] = $chapter_row[$chapter_number]['red'];
  }
  $pchart = "<div id='progresschart'>"; // #519cd5
  foreach ($chapters as $key => $value) {
    $pchart.= "<div class='progress'>";
	$chapter_permalink = (strstr($_SERVER['REQUEST_URI'], "/demo/")) ? $localpath."/demo/chapters-demo/general-information-demo/" : $localpath."/chapters/".$chapterNumbs[$key]."/";
    $pchart.= "<a href='".$chapter_permalink."' class='chapterdashas'><div class='chapterdashbars'>";
    if ($key != 26) {
      // don't show chapter number for Miscellaneous
      $pchart.= $key.". ";
    }
    $pchart.= $value."</div></a>";
    $pchart.= "<div class='percent'>";
    $pchart.= $total_tasks > 0 && $chapter_row[$key]['total'] > 0 ? (round($chapter_row[$key]['green'] / $chapter_row[$key]['total'], 2) * 100) : '0';
    $pchart.= "%</div>";
    for ($g = 0; $g < $chaptergreen[$key]; $g++) {
      $pchart.= "<div class='progress_dot green_dot'></div>";
    }
    for ($y = 0; $y < $chapteryellow[$key]; $y++) {
      $pchart.= "<div class='progress_dot yellow_dot'></div>";
    }
    for ($r = 0; $r < $chapterred[$key]; $r++) {
      $pchart.= "<div class='progress_dot red_dot'></div>";
    }
    $pchart.= "</div>";
  }
  $pchart.= "<div style='margin-top: 25px;'><h4>Total Progress</h4></div>";
  if ($total_tasks > 0) {
    $total_percentage = round($total_completed_tasks / $total_tasks, 2) * 100;
  } else {
    $total_percentage = 0;
  }
  if ($total_percentage > 0) { $total_percentage = $total_percentage."%"; }
  $pchart.= "
  <div class='w3-light-grey w3-round' style='margin-top: 25px;'>
    <div class='w3-container w3-round w3-green' style='width:".$total_percentage."'>".$total_percentage."</div>
  </div>
  ";
  $pchart.= "</div>";
  return $pchart;
}

