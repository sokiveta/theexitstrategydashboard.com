<?php
$task_chapters = $wpdb->get_results("SELECT * FROM `task_chapters`");
?>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp } = Vue;
createApp({
  data() {
    return {
	  chaps: [
		<?php
		foreach ( $task_chapters as $task_chapter ) {
		  $chapter_permalink = (strstr($_SERVER['REQUEST_URI'], "/demo/")) ? "general-information-demo" : $task_chapter->chapter_permalink;
		  $chapter_number = ($task_chapter->id != 19) ? $task_chapter->chapter_number.". " : "";
		  echo "{ id: ".$task_chapter->id.",  chapter: '".$chapter_permalink."',  name: '".$chapter_number.$task_chapter->chapter_title."' },";
		}
		?>
	  ]
	};
  }
}).mount("#app");
</script>