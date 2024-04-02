<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
	require_once('C:\xampp\htdocs\www.theexitstrategydashboard.com\wp-load.php');
} else {
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
}
$valu_data = json_decode(file_get_contents('php://input'),true);
foreach ($valu_data as $key => $value) {
  if ($key == 'user_id') {
    $$key = (int) $value;
  } else {
    $$key = $value;
  }
}
// error_log("user_id: ".$user_id.", difference: ".$difference.", demo: ".$demo."\n", 3, "error_log"); 
if ($user_id > 0 && $type != '' && $label != "" && $demo == 'FALSE') {
  $valuationSQL = "INSERT INTO `valu_rows` (`user_id`,`type`,`label`) VALUES (".$user_id.",'".$type."','".$label."')";
  $wpdb->query($valuationSQL);
  $row_id = $wpdb->insert_id;
  // Insert 5 new records into `valu_vals` with user_id and row_id with value=0;
  if ($row_id > 0) {
    $insertSQL = "INSERT INTO `valu_vals` (`id`, `user_id`, `col_id`, `row_id`, `value`) VALUES
    (NULL, ".$user_id.", 1, ".$row_id.", 0),
    (NULL, ".$user_id.", 2, ".$row_id.", 0),
    (NULL, ".$user_id.", 3, ".$row_id.", 0),
    (NULL, ".$user_id.", 4, ".$row_id.", 0),
    (NULL, ".$user_id.", 5, ".$row_id.", 0)";
    $wpdb->query($insertSQL);
    echo "Success: new row added (".$label.")";
  } else {
    echo "ERROR -- row_id: ".$row_id;
  }
  $arry = array ("Message" => "Success"); 
} else {
  $arry = array ("Message" => "Error - row_id: ".$row_id); 
}
echo json_encode($arry);
