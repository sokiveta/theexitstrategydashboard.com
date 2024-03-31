<?php
// Valuation
add_shortcode('valuationtable','valuation_table');
function valuation_table () {
  global $wpdb;
  global $current_user;
  if (strstr($_SERVER['REQUEST_URI'], "/demo/")) {
    $id = "3"; 
  } else {
    $id = $current_user->ID; 
  }
  $footnote = "";

  // valu_cols
  $valuationSQL = "SELECT * FROM `valu_cols` WHERE user_id = ".$id." AND active = 1 LIMIT 5";
  $valuationvals = $wpdb->get_results($valuationSQL);
  $valu_cols = array();
  // How many columns: 5
  $count = count($valuationvals);
  $i = 1;
  foreach ($valuationvals as $valuationval) {
    $valu_cols[$i]['id'] = $valuationval->id;
    $valu_cols[$i]['date'] =  $valuationval->date;
    $valu_cols[$i]['ebitda'] = $valuationval->ebitda;
    $valu_cols[$i]['multiplier'] = $valuationval->multiplier;
    // $valu_cols[$i]['estimated_value'] = $valuationval->estimated_value;
    // $valu_cols[$i]['difference'] = $valuationval->difference;
    $valu_cols[$i]['display'] = $valuationval->display;
    $i++;
  }

  $valuation = "";
  $valuation .= "<div class='item empty'></div>";

  // loop through dates
  for ($a=1; $a <= $count; $a++) {
	if ($valu_cols[$a]['date']) {
		$col_date_value = date('m/d/Y', strtotime($valu_cols[$a]['date']));
	} else {
		$col_date_value = "";
	}
    $valuation.= "
    <div class='item item-date c'>
      <div class='d".$a."'>
        <i class='fa fa-minus-square' style='float: left;' id='column_hide_".$a."'></i>
        <input id='date_".$valu_cols[$a]['id']."' class='val' type='text' value='".$col_date_value."' />
      </div>
      <div class='e e".$a."'>
        <i class='fa fa-plus-square' style='float: left;' id='column_show_".$a."'></i>
      </div>
    </div>";
  }

  $valuation.= "<div class='item c0 rowheading'>EBITDA</div>";

  // loop through ebitda
  for ($b=1; $b <= $count; $b++) {
    $valuation.= "<div class='item c'><div class='d".$b."'>
    <input id='ebitda_".$b."' type='text' class='val valinput'
      value='$".number_format($valu_cols[$b]['ebitda'], 2)."'
    />
    </div></div>";
	// $valu_cols[$b]['id']
    // $valu_cols[$b]['ebitda']
    $col_add_total[$b] = 0;
    $col_ded_total[$b] = 0;
    $col_adj_total[$b] = 0;
    $col_val_total[$b] = 0;
  }

  $valuation.= "<div class='item-span rowheading'>Additions:</div>";
  // loop through each addition
  $valuSQL = "SELECT valu_rows.id AS row_id, valu_vals.id as vals_id, valu_vals.col_id, valu_vals.value, valu_rows.type, valu_rows.label FROM `valu_rows` LEFT JOIN `valu_vals` ON valu_rows.id=valu_vals.row_id WHERE valu_rows.user_id = ".$id." AND valu_rows.active = 1 ORDER BY valu_rows.type, valu_rows.id, valu_vals.col_id"; // valu_rows.order,
  $valuvals = $wpdb->get_results($valuSQL);
  $valu_rows_a = array();
  $valu_rows_d = array();
  $rowidarray = array();
  $rowid = 0;

  foreach ($valuvals as $valuval) {

    if ($rowid != $valuval->row_id) {
      $rowidarray[] = $valuval->row_id;
      $rowid = $valuval->row_id;
    }

    if ($valuval->type == 'a') {
      $valu_rows_a[$valuval->row_id][0] = $valuval->label;
      $valu_rows_a[$valuval->row_id][$valuval->col_id] = $valuval->value;
    } elseif ($valuval->type == 'd') {
      $valu_rows_d[$valuval->row_id][0] = $valuval->label;
      $valu_rows_d[$valuval->row_id][$valuval->col_id] = $valuval->value;
    }

  }

  foreach ($rowidarray as $row_id) {
    for ($v=0; $v <= $count; $v++) {
      if ($v == 0) {
        if (isset($valu_rows_a[$row_id][0])) {
          $valuation.= "<div class='item c c0 description' id='text_0_".$row_id."'>".$valu_rows_a[$row_id][0]."</div>";
          $valuation.= "<div id='edittext_0_".$row_id."' class='item c c0 edittext'>
            <textarea id='areatext_0_".$row_id."' type='text' class='tex'>".$valu_rows_a[$row_id][0]."</textarea>
            <input type='button' value='Cancel' class='canceltext' id='ctext_0_".$row_id."' />
            <input type='button' value='Remove' class='removetext' id='rtext_0_".$row_id."' />
            <input type='button' value='Save'   class='savetext'   id='stext_0_".$row_id."' />
          </div>";

        } else {
          error_log(":ERROR - Line 105: row_id: ".$row_id." or v: ".$v." \n", 3, "php_error_log");
        }
      } else {
        if (isset($valu_rows_a[$row_id][$v])) {
          $valuation.= "<div class='item c'><div class='d".$v."'>
            <input id='add_".$v."_".$row_id."' type='text' class='val valinput' value='$".number_format($valu_rows_a[$row_id][$v], 2)."' />
          </div></div>";
		  // $valu_cols[$v]['id']
          $col_add_total[$v] = $col_add_total[$v] + $valu_rows_a[$row_id][$v];
        } else {
          error_log("ERROR - Line 114: row_id: ".$row_id." or v: ".$v." \n", 3, "php_error_log");
        }
      }
    }
  }

  // add blank row for adding new addition
  $valuation.= "<div id='create_new_addition' class='item c c0 edittext na'>
    <textarea id='new_addition_text' name='new_addition_text' type='text' class='tex'></textarea>
      <input type='button' value='Cancel' class='cancelnewtext' id='cancel_new_addition' />
      <input type='button' value='Save'   class='savenewtext'   id='save_new_addition' />
    </div>";
  for ($v=1; $v <= $count; $v++) {
    $valuation.= "<div class='item c na'><div class='d".$v."'>
        <!-- <input id='add_new_".$v."' type='text' class='val valinput' value='' /> -->
      </div>
    </div>";
  }


  $valuation.= "<div class='item c0 rowheading total' > Total Additions </div>";
  for ($v=1; $v <= $count; $v++) {
    $valuation.= "<div class='item c'><div class='d".$v."'>
    <input id='totsadd_".$v."' type='text' class='val' value='$".number_format($col_add_total[$v], 2)."' readonly>
    </div></div>";
  }

  $valuation.= "<div class='item-new'><p><a id='new_addition'>Enter new addition here</a></p></div>";

  $valuation.= "<div class='item-span rowheading'>Deductions:</div>";
  // loop through each deduction

  foreach ($rowidarray as $row_id) {
    for ($v=0; $v <= $count; $v++) {
      if ($v == 0) {
        if (isset($valu_rows_d[$row_id][$v])) {
          $valuation.= "<div class='item c c0 description' id='text_0_".$row_id."'>".$valu_rows_d[$row_id][0]." </div>";
          $valuation.= "<div id='edittext_0_".$row_id."' class='item c c0 edittext'>
            <textarea id='areatext_0_".$row_id."' type='text' class='tex'>".$valu_rows_d[$row_id][0]."</textarea>
            <input type='button' value='Cancel' class='canceltext' id='ctext_0_".$row_id."' />
            <input type='button' value='Remove' class='removetext' id='rtext_0_".$row_id."' />
            <input type='button' value='Save'   class='savetext'   id='stext_0_".$row_id."' />
          </div>";
        } else {
          error_log("ERROR - Line 159: row_id: ".$row_id." or v: ".$v." \n", 3, "php_error_log");
        }
      } else {
        if (isset($valu_rows_d[$row_id][$v])) {
          $valuation.= "<div class='item c'><div class='d".$v."'>
          <input id='ded_".$v."_".$row_id."' type='text' class='val valinput' value='($".number_format($valu_rows_d[$row_id][$v], 2).")' />
          </div></div>";
		  // $valu_cols[$v]['id']
          $col_ded_total[$v] = $col_ded_total[$v] + $valu_rows_d[$row_id][$v];
        } else {
          error_log("ERROR - Line 168: row_id: ".$row_id." or v: ".$v." \n", 3, "php_error_log");
        }
      }
    }
  }

  // add blank row for adding new deduction
  $valuation.= "<div id='create_new_deduction' class='item c c0 edittext nd'>
    <textarea id='new_deduction_text' name='new_deduction_text' type='text' class='tex'></textarea>
      <input type='button' value='Cancel' class='cancelnewtext' id='cancel_new_deduction' />
      <input type='button' value='Save'   class='savenewtext'   id='save_new_deduction' />
    </div>";
  for ($v=1; $v <= $count; $v++) {
    $valuation.= "<div class='item c nd'><div class='d".$v."'>
      <!-- <input id='ded_new_".$v."' type='text' class='val valinput' value='$' /> -->
      </div>
    </div>";
  }

  $valuation.= "<div class='item c0 rowheading total' > Total Deductions </div>";
  for ($v=1; $v <= $count; $v++) {
    $valuation.= "<div class='item c'><div class='d".$v."'>
    <input id='totsded_".$v."' type='text' class='val' value='($".number_format($col_ded_total[$v], 2).")' readonly>
    </div></div>";
  }

  $valuation.= "<div class='item-new'><p><a id='new_deduction'>Enter new deduction here</a></p></div>";

  $valuation.= "<div class='item-span'> </div>";

  $valuation.= "<div class='item c0 rowheading'>
  Adjusted EBITDA <span id='Adjusted_ebitda_message'></span></div>";
  // calculate adjusted ebitda
  for ($v=1; $v <= $count; $v++) {
    $col_adj_total[$v] = $valu_cols[$v]['ebitda'] + $col_add_total[$v] - $col_ded_total[$v];
    $valuation.= "<div class='item c'><div class='d".$v."'>";
    if ($col_adj_total[$v] < 0) {
      $col_adj_total[$v] = 0;
      $valuation.= "<strong>*</strong> ";
      $footnote = "* Denotes negative value in Adjusted EBITDA which is automatically set to 0";
    }
    $valuation.= "<input id='totsadj_".$v."' type='text' class='val' value='$".number_format($col_adj_total[$v], 2)."' readonly>
    </div></div>";
  }

  $valuation.= "<div class='item-span'> </div>";

  $valuation.= "<div class='item c0 rowheading'> Multiplier </div>";

  for ($m=1; $m <= $count; $m++) {
    $valuation.= "<div class='item c".$m."'><div class='d".$m."'>X <select id='multi_".$m."' class='val multinput' name='multiplier".$m."'>";
	// $valu_cols[$m]['id']
    for ($s=1; $s <= 20; $s++) {
      $valuation.= "<option value='".$s."'";
      if ($s == $valu_cols[$m]['multiplier']) { $valuation.= " selected='selected'"; }
      $valuation.= ">".$s."</option>";
    }
    $valuation.= "</select></div></div>";
  }

  $valuation.= "<div class='item-span'> </div>";

  $valuation.= "<div class='item c0 rowheading'> Estimated value </div>";
  // calculate estimated value, also is in the database
  for ($v=1; $v <= $count; $v++) {
    $col_val_total[$v] = $col_adj_total[$v] * $valu_cols[$v]['multiplier'];
    $valuation.= "<div class='item c'><div class='d".$v."'>
      <input id='val_total_".$v."' type='text' class='val estival' value='$".number_format($col_val_total[$v], 2)."' readonly>
    </div></div>";
  }

  $valuation.= "<div class='item-span'> </div>";

  $valuation.= "<div class='item c0 rowheading'> Difference </div>";
  if (count($col_val_total) > 0) {
    $totalMin = min($col_val_total);
    if ($totalMin < 0) { $totalMin = 0; }
    $totalMax = max($col_val_total);
    if ($totalMax < 0) { $totalMax = 0; }
    $difference = $totalMax - $totalMin;
  } else {
    $difference = 0;
  }

  $valuation.= "<div class='item-difference'><input id='difference' type='text' class='val' value='$".number_format($difference, 2)."' readonly></div>";

  $valuation.= "<div id='updatesSaved' class='savedDiv'>Updates Saved</div>";
  $valuation.= "<div id='editSaved' class='savedDiv'>Edit Saved</div>";
  $valuation.= "<div id='newSaved' class='savedDiv'>Saved</div>";
  $valuation.= "<div id='removeSaved' class='savedDiv'>Saved</div>";
  $valuation.= "<div id='dateSaved' class='savedDiv'>Date Saved</div>";

  $valuation.= "<br />".$footnote;

  return $valuation;

}