<?php
/**
 * The header for Astra Child Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if ( apply_filters( 'astra_header_profile_gmpg_link', true ) ) {
	?>
	 <link rel="profile" href="https://gmpg.org/xfn/11">
	 <?php
}
?>
<?php wp_head(); ?>
<?php astra_head_bottom(); ?>
<?php
$localpath = ($_SERVER['HTTP_HOST'] == "localhost") ? "/www.theexitstrategydashboard.com" : "";
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<?php
}

// stylesheets
$styledirectory = $localpath.'/wp-content/themes/Astra-Child-Theme';
if ($_SERVER['REQUEST_URI'] == $localpath."/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/") {
  // dashboard
	echo "<link rel='stylesheet' href='".$styledirectory."/css/dash.css'>";
}
if (strstr($_SERVER['REQUEST_URI'], "chapters")) {
  // chapters
	echo "<link rel='stylesheet' href='".$styledirectory."/css/chaps.css'>";
	echo "<link rel='stylesheet' href='".$styledirectory."/css/tasks.css'>";
	echo "<link rel='stylesheet' href='".$styledirectory."/css/buttons.css'>";
}
if ($_SERVER['REQUEST_URI'] == $localpath."/task-mgnt/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/task-mgnt-demo/") {
  // task tmnt
	echo "<link rel='stylesheet' href='".$styledirectory."/css/tasks.css'>";
	echo "<link rel='stylesheet' href='".$styledirectory."/css/buttons.css'>";
}
if ($_SERVER['REQUEST_URI'] == $localpath."/assignments/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/assignments-demo/") {
  // assignments
	echo "<link rel='stylesheet' href='".$styledirectory."/css/assign.css'>";
	echo "<link rel='stylesheet' href='".$styledirectory."/css/buttons.css'>";
}
if ($_SERVER['REQUEST_URI'] == $localpath."/valuation/" || $_SERVER['REQUEST_URI'] == $localpath."/demo/valuation-demo/") {
  // valuation
	echo "<link rel='stylesheet' href='".$styledirectory."/css/valu.css'>";
}
echo "<link rel='stylesheet' href='".$styledirectory."/style.css'>";
?>

</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
<?php astra_body_top(); ?>
<?php wp_body_open(); ?>

<a
	class="skip-link screen-reader-text"
	href="#content"
	role="link"
	title="<?php echo esc_attr( astra_default_strings( 'string-header-skip-link', false ) ); ?>">
		<?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<div
<?php
	echo astra_attr(
		'site',
		array(
			'id'    => 'page',
			'class' => 'hfeed site',
		)
	);
	?>
>
	<?php
	astra_header_before();

	astra_header();

	astra_header_after();

	astra_content_before();
	?>
	<div id="content" class="site-content">
		<div class="ast-container">
		<?php astra_content_top(); ?>