<?php /*

  This file is part of a child theme called Astra Child Theme.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read
  https://developer.wordpress.org/themes/advanced-topics/child-themes/

*/

// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)

function your_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style,
      get_template_directory_uri() . '/style.css');

    wp_enqueue_style( 'child-style',
      get_stylesheet_directory_uri() . '/style.css',
      array($parent_style),
      wp_get_theme()->get('Version')
    );

}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

add_action("after_setup_theme", function() {
  remove_filter( 'the_content', 'wpautop' );
}, 10);

/*  Add your own functions below this line ======================================== */
// error_log("Error message\n", 3, "php_error_log");
// getcwd()

// Redirect to homepage after login 
function my_login_redirect( $redirect_to ) {
	$redirect_to = home_url(); // Use home_url( '/my-page-slug/' ); to use a specific slug/URL on the site.
	return $redirect_to;
}
add_filter( 'login_redirect', 'my_login_redirect', 999 );

$localpath = ($_SERVER['HTTP_HOST'] == "localhost") ? "/www.theexitstrategydashboard.com" : "";

$current_user = wp_get_current_user();

$styledirectory = $localpath.'/wp-content/themes/Astra-Child-Theme';

if ($_SERVER['REQUEST_URI'] == $localpath."/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/") {
  // dashboard
  include("functions/dash.php");
}

if (strstr($_SERVER['REQUEST_URI'], "chapters") || $_SERVER['REQUEST_URI'] == $localpath."/demo/chapters-demo/") {
  // main Chapters page button list
  include("functions/chapters.php");
}

if (strstr($_SERVER['REQUEST_URI'], "chapters") || $_SERVER['REQUEST_URI'] == $localpath."/task-mgnt/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/task-mgnt-demo/") {
    // chapters task tmnt
  include("functions/buttons.php");
  // include("functions/chaps.php");
  include("functions/tasks.php");
}

if ($_SERVER['REQUEST_URI'] == $localpath."/assignments/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/assignments-demo/") {
  // assignments
  include("functions/buttons.php");
  include("functions/assign.php");
}

if ($_SERVER['REQUEST_URI'] == $localpath."/valuation/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/valuation-demo/") {
  // valuation
  include("functions/valu.php");
}