<?php
// Chapter Pages
add_shortcode('chapterlist', 'chapter_list');
function chapter_list () {
  global $wpdb;
  $chapterPages = explode("/", rtrim($_SERVER['REQUEST_URI'], "/"));
  $chapterPage  = end($chapterPages);
  $chapterlist = "<strong>Chapter Checklists:</strong><ul class='chapterlist'>";
  $chaptervals = $wpdb->get_results("SELECT * FROM `task_chapters`");
  foreach ($chaptervals as $chapterval) {	
	if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
		$chapterlist .= "<li> <a href='#'>";
		if ($chapterval->chapter_number != 26) {
			$chapterlist .= $chapterval->chapter_number.". ";
		}
		$chapterlist .= $chapterval->chapter_title."</a></li>";
	} else {
		if ($chapterPage == $chapterval->chapter_permalink) {			
			$chapterlist .= "<li> ";
			if ($chapterval->chapter_number != 26) {
				$chapterlist .= $chapterval->chapter_number.". ";
			}
			$chapterlist .= $chapterval->chapter_title."</li>";			
		} else {				
			$chapterlist .= "<li> <a href='./".$chapterval->chapter_permalink."'>";
			if ($chapterval->chapter_number != 26) {
				$chapterlist .= $chapterval->chapter_number.". ";
			}
			$chapterlist .= $chapterval->chapter_title."</a></li>";
		}
	}
  }
  $chapterlist .= "</ul>";
  return $chapterlist;
}
