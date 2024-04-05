<?php
// for the main Chapters page
add_shortcode('chapterpage', 'chapter_page');

function chapter_page () {
  global $wpdb;
  $task_chapters = $wpdb->get_results("SELECT * FROM `task_chapters`");
  $chapter_buttons = "";
  foreach ( $task_chapters as $task_chapter ) {
    $chapter_permalink = (strstr($_SERVER['REQUEST_URI'], "/demo/")) ? "general-information-demo" : $task_chapter->chapter_permalink;
    $chapter_number = ($task_chapter->id != 19) ? $task_chapter->chapter_number.". " : "";

    $chapter_buttons .= "<a class='chapteras' href='".$chapter_permalink."'><div class='chapterbars'>".$chapter_number.$task_chapter->chapter_title."</div></a>";

  }
  return $chapter_buttons;
}