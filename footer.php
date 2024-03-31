<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php 
	astra_content_after();
		
	astra_footer_before();
		
	astra_footer();
		
	astra_footer_after(); 
?>
	</div><!-- #page -->
<?php 
	astra_body_bottom();    
	wp_footer(); 
?>

<?php
// javascript includes
$current_user = wp_get_current_user();
$email_from = $current_user->user_email;
$count = 5;
$localpath = ($_SERVER['HTTP_HOST'] == "localhost") ? "/www.theexitstrategydashboard.com" : "";
$dir = $localpath."/wp-content/themes/Astra-Child-Theme"; 
$user_id = (strstr($_SERVER['REQUEST_URI'], "/demo/")) ? "3" : $current_user->ID; 
$demo = (strstr($_SERVER['REQUEST_URI'], "/demo/")) ? 'TRUE' : 'FALSE'; 
?>
<script>
const user_id = '<?=$user_id?>';
const demo = '<?=$demo?>';
</script>
<?php
if ($_SERVER['REQUEST_URI'] == $localpath."/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/") {
  // dashboard
  include get_stylesheet_directory().'/js/dash.php';
}
if (strstr($_SERVER['REQUEST_URI'], "chapters")) {
  // chapters
  include get_stylesheet_directory().'/js/chaps.php';
  include get_stylesheet_directory().'/js/tasks.php';
  include get_stylesheet_directory().'/js/buttons.php';
}
if ($_SERVER['REQUEST_URI'] == $localpath."/task-mgnt/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/task-mgnt-demo/") {
  // task tmnt
  include get_stylesheet_directory().'/js/tasks.php';
  include get_stylesheet_directory().'/js/buttons.php';
}
if ($_SERVER['REQUEST_URI'] == $localpath."/assignments/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/assignments-demo/") {
  // assignments
  include get_stylesheet_directory().'/js/assign.php';
  include get_stylesheet_directory().'/js/buttons.php';
}
if ($_SERVER['REQUEST_URI'] == $localpath."/valuation/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/valuation-demo/") {
  // valuation
  include get_stylesheet_directory().'/js/valu.php';
}

if (strstr($_SERVER['REQUEST_URI'], "chapters") || 
$_SERVER['REQUEST_URI'] == $localpath."/" || 
$_SERVER['REQUEST_URI'] == $localpath."/demo/" || 
$_SERVER['REQUEST_URI'] == $localpath."/task-mgnt/" || 
$_SERVER['REQUEST_URI'] == $localpath."/demo/task-mgnt-demo/" || 
$_SERVER['REQUEST_URI'] == $localpath."/assignments/" || 
$_SERVER['REQUEST_URI'] == $localpath."/demo/assignments-demo/" || 
$_SERVER['REQUEST_URI'] == $localpath."/valuation/" || 
$_SERVER['REQUEST_URI'] == $localpath."/demo/valuation-demo/") {
?>

<div id='newSaved'  class='savedDiv'>Saved</div>
<div id='editSaved' class='savedDiv'>Edits Saved</div>
<div id='emailSent' class='savedDiv'>Email Sent</div>

<script>
// display success messages
SuccessMessage = function(id) {
  idHash = "#"+id;
  const savedDiv = document.getElementById(id);
  savedDiv.style.display='block';
  setTimeout(function() {
      $(idHash).fadeOut('slow');
  }, 700);
}
</script>

<?php
}
?>
	</body>
</html>