<script>
const colArray = [];
<?php
// valu_cols
$valuationSQL = "SELECT id FROM `valu_cols` WHERE user_id = ".$user_id." AND active = 1 LIMIT 5";
$valuationvals = $wpdb->get_results($valuationSQL);
// $count = count($valuationvals);
$i = 1;
foreach ($valuationvals as $valuationval) {
  echo "colArray[".$i."] = ".$valuationval->id."; ";
  $i++;
}
?>
//  colArray[1] = 1;
//  colArray[2] = 2;
//  colArray[3] = 3;
//  colArray[4] = 4;
//  colArray[5] = 5;
const dateArray = [];
const plusArray = [];
const minuArray = [];

// function saveRow (id, label) {
//   $.ajax({
//     url: '<?=$dir?>/ajax_valu.php',
//     method: 'post',
//     data: {action:'save',user_id:user_id,id:id,label:label,demo:demo},
//     cache: false,
//     success: function(data) {
//       console.log(data);
//       SuccessMessage('editSaved');
//     }
//   });
// }

// function newRow (type,label) {
//   $.ajax({
//     url: '<?=$dir?>/ajax_valu.php',
//     method: 'post',
//     data: {action:'new',user_id:user_id,type:type,label:label,demo:demo},
// 		cache: false,
// 		success: function(data){
//       console.log(data);
//       SuccessMessage('newSaved');
//       $(document).ajaxStop(function(){
//         window.location.reload();
//       });

// 		}
//   });
// }

// function removeRow (id) {
//   $.ajax({
//     url: '<?=$dir?>/ajax_valu.php',
//     method: 'post',
//     data: {action:'remove',user_id:user_id,id:id,demo:demo},
// 		cache: false,
// 		success: function(data){
//       console.log(data);
//       SuccessMessage('removeSaved');
//     }
//   });
// }

function updateEbitda (col, ebitda) {
  console.log(window.location.href);
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'ebitda',user_id:user_id,col:col,ebitda:ebitda,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
    }
  });
}

function updateValueVals (col_id, row_id, value) {
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'valuevals',user_id:user_id,col_id:col_id,row_id:row_id,value:value,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
    }
  });
}

function updateMultiplier (col, multiplier) {
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'multiplier',user_id:user_id,col:col,multiplier:multiplier,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
    }
  });
}

function updateEstimatedValue (col, estimated_value) {
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'estimated_value',user_id:user_id,col:col,estimated_value:estimated_value,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
    }
  });
}

function updateDifference (difference) {
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'difference',user_id:user_id,difference:difference,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
    }
  });
}

function saveDate (col, newdate) {
  $.ajax({
    url: '<?=$dir?>/ajax_valu.php',
    method: 'post',
    data: {action:'savedate',user_id:user_id,col:col,date:newdate,demo:demo},
		cache: false,
		success: function(data){
      console.log(data);
      SuccessMessage('dateSaved');
    }
  });
}





// $( function () {
document.addEventListener('DOMContentLoaded', function () {

  for (let c = 1; c <= 5; c++) {

    // put datepicker on date field
    let dateId = "#date_" + colArray[c];
    // let dateIc = "#date_" + c;
    $(dateId).datepicker();
    // document.getElementById(dateId).datepicker()

    // watch for change on date
    // $(dateId).on('change', function () { // dateIc
    //   dateArray[c] = new Date($(dateId).val()).toJSON().slice(0, 10); // dateIc
    //   saveDate(c,dateArray[c]);
    // });
    document.getElementById(dateId).addEventListener('change',function () { // dateIc
      dateArray[c] = new Date(document.getElementById(dateId).value()).toJSON().slice(0, 10); // dateIc
      // ajax
      saveDate(c,dateArray[c]);
    })

    // Expand and collapse collumn
    let colShow = "column_show_" + c;
    let colHide = "column_hide_" + c;
    let dclass  = "d" + c;
    let eclass  = "e" + c;
    const dpboxes = [];
    const epboxes = [];
    const dmboxes = [];
    const emboxes = [];

    plusArray[c] = document.getElementById(colShow);
    plusArray[c].addEventListener('click', () => {
      dpboxes[c] = document.getElementsByClassName(dclass);
      for (let dclass of dpboxes[c]) { dclass.style.display = 'block'; }
      epboxes[c] = document.getElementsByClassName(eclass);
      for (let eclass of epboxes[c]) { eclass.style.display = 'none'; }
    });

    minuArray[c] = document.getElementById(colHide);
    minuArray[c].addEventListener('click', () => {
      dmboxes[c] = document.getElementsByClassName(dclass);
      for (let dclass of dmboxes[c]) { dclass.style.display = 'none'; }
      emboxes[c] = document.getElementsByClassName(eclass);
      for (let eclass of emboxes[c]) { eclass.style.display = 'block'; }
    });

  }
} );

// format number with commas and decimals
let numberFormatter = new Intl.NumberFormat('en-US', {
  style: 'decimal',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

// Only allow numbers, commas, and periods
function numbersOnly (e) {
  let theEvent = e || window.event;
  let key = theEvent.keyCode || theEvent.which;
  let keyCode = key;
  let regex = /^[0-9.,\b]+$/;
  key = String.fromCharCode(key);
  if (key.length == 0) return true;
  if(keyCode == 188 || keyCode == 190 || keyCode == 37 || keyCode == 39 || keyCode == 96 || keyCode == 97 || keyCode == 98 || keyCode == 99 || keyCode == 100 || keyCode == 101 || keyCode == 102 || keyCode == 103 || keyCode == 104 || keyCode == 105){
      return true;
  } else {
    if (!regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) theEvent.preventDefault();
    }
  }
}
const values = document.querySelectorAll(".valinput");
values.forEach(i => {
  i.addEventListener("keydown", numbersOnly);
});

const forEach = function (array, callback, scope) {
  for (let i = 0; i < array.length; i++) {
    callback.call(scope, i, array[i]);
  }
};

function numbersCalculate (e) {
  const plainId = e.target.id;
  resultId = plainId.split('_');
  let column = resultId[1];
  let rowumn = resultId[2];
  const add_vals = [];
  const ded_vals = [];

  console.log("column: "+column);

  // get values from everywhere else to calculate totals
  const inputvalues = document.querySelectorAll(".valinput");
  forEach(inputvalues, function (index, value) {
    let valueId = value.id.split('_');
    if (valueId[1] == column) {

      if (valueId[0] == "add") {
        let v = parseInt(parseFloat(value.value.replace(/[$,]/g, '')));
        let c = valueId[1]; // column 1-5
        let r = valueId[2]; // id/row
        if (c === column && r === rowumn) {
          // save to database
          // Ajax
          updateValueVals(c,r,v);
        }
        let add_val = {"col": c, "row": r, "val": v}
        add_vals.push(add_val);

      } else if (valueId[0] == "ded") {
        let v = parseInt(parseFloat(value.value.replace(/[($,)]/g,'')));
        let c = valueId[1]; // column 1-5
        let r = valueId[2]; // id/row
        if (c === column && r === rowumn) {
          // save to database
          // Ajax
          updateValueVals(c,r,v);
        }
        let ded_val = {"col": c, "row": r, "val": v}
        ded_vals.push(ded_val);
      }

    }
  });

  // Calculate Additions and display in Total Additions field
  let subaddtots = 0;
  add_vals.forEach((element, index, array) => {
    if (element.col == column) {
      subaddtots = subaddtots + element.val;
    }
  });
  if (subaddtots < 0) { subaddtots = 0; }

  let totsadd = document.querySelector("#totsadd_" + column);
  if (totsadd) {
	totsadd.value = '$' + numberFormatter.format(subaddtots);
  }

  // Calculate Deductions and display in Total Deductions field
  let subdedtots = 0;
  ded_vals.forEach((element, index, array) => {
    if (element.col == column) {
      subdedtots = subdedtots + element.val;
    }
  });
  if (subdedtots < 0) { subdedtots = 0; }
  let totsded = document.querySelector("#totsded_" + column);
  if (totsded) {
	totsded.value = '($' + numberFormatter.format(subdedtots) + ')';
  }

  // Get EBITDA, Additions, and Deductions, then calculate Adjusted EBITDA
  let ebitdavar = "#ebitda_"+column;
  let totsebi = document.querySelector("#ebitda_" + column);
  let ebitdaint = totsebi.value.replace(/[($,)]/g,'');
  // Ajax
  updateEbitda(column,ebitdaint);

  if (ebitdaint < 0)  { ebitdaint = 0; }
  ebitdaint  = parseInt(ebitdaint);
  subaddtots = parseInt(subaddtots);
  subdedtots = parseInt(subdedtots);
  let subval = ebitdaint + subaddtots - subdedtots;
  if (subval < 0) {
    // Prevent negative numbers from being multiplied
    $('#Adjusted_ebitda_message').html(' &nbsp; Notice: Adjusted EBITDA value cannot be negative');
    subval = 0;
  }
  let totsadj = document.querySelector("#totsadj_" + column);
  if (totsadj) {
	totsadj.value = '$' + numberFormatter.format(subval);
  }

  // Get Adjusted EBITDA and Multiplier and calculate Total
  let multiplier_id = "#multi_" + column;
  let multip = document.querySelector(multiplier_id);
  let multiplier = parseInt(multip.value);
  let total  = subval * multiplier;
  let tots = document.querySelector("#val_total_" + column);
  if (tots) {
	tots.value = '$' + numberFormatter.format(total);
  } 
  // Ajax
  updateEstimatedValue(column, total);

}

const userinput = document.querySelectorAll(".valinput");
userinput.forEach(i => {
  i.addEventListener("keyup", numbersCalculate);
});

function differenceCalculate () {
  const totals = [];
  const inputvalues = document.querySelectorAll(".estival");
  forEach(inputvalues, function (index, value) {
    let v = parseInt(parseFloat(value.value.replace(/[$,]/g, '')));
    totals.push(v);
  });
  let max = Math.max(...totals);
  let min = Math.min(...totals);
  let dif = max - min;
  let diff = document.querySelector('#difference');
  diff.value = '$' + numberFormatter.format(dif);
  // Ajax
  updateDifference(dif);
}

function multiplierCalculate (e) {
  const plainId = e.target.id;
  resultId = plainId.split('_');
  let column = resultId[1];
  let multiplier = e.target.value;

  // Database
  // Ajax
  updateMultiplier(column,multiplier);

  // EBITDA
  let ebitda = document.querySelector("#ebitda_" + column);
  let ebitdaint = parseInt(parseFloat(ebitda.value.replace(/[($,)]/g,'')));
  if (ebitdaint < 0)  { ebitdaint = 0; }

  // Total Additions
  let totsadd = document.querySelector("#totsadd_" + column);
  let subaddtots = parseInt(parseFloat(totsadd.value.replace(/[$,]/g, '')));
  if (subaddtots < 0)  { subaddtots = 0; }

  // Total Deductions
  let totsded = document.querySelector("#totsded_" + column);
  let subdedtots = parseInt(parseFloat(totsded.value.replace(/[($,)]/g,'')));
  if (subdedtots < 0)  { subdedtots = 0; }

  // Calculate Adjusted EBITDA
  let subval = ebitdaint + subaddtots - subdedtots;

  // Total
  let total  = subval * multiplier;
  let tots = document.querySelector("#val_total_" + column);
  if (tots) {
	tots.value = '$' + numberFormatter.format(total);
  }
  // Ajax
  updateEstimatedValue(column, total);

  // Difference
  differenceCalculate();

}

const totals = document.querySelectorAll(".val");
totals.forEach(i => {
  i.addEventListener("keyup", differenceCalculate);
});

const multinput = document.querySelectorAll(".multinput");
multinput.forEach(i => {
  i.addEventListener("change", multiplierCalculate);
});

// show/hide/edit the addition description text
function showEdit (e) {
  const divId = "#" + e.target.id;
  const editId = "#edit" + e.target.id;
  const edittext = document.querySelector(divId);
  const addedit = document.querySelector(editId);
  edittext.style.display = 'none';
  addedit.style.display = 'block';
};

function hideEdit (id) {
  const divId  = "#" + id;
  const editId = "#edit" + id;
  const divtext = document.querySelector(divId);
  const edittext = document.querySelector(editId);
  divtext.style.display = 'block';
  edittext.style.display = 'none';
}

const d = document.querySelectorAll(".description");
d.forEach(p => {
  p.addEventListener('click', showEdit);
});

function cancelEdit (e) {
  let plainId = e.target.id;
  plainId = plainId.substring(1);
  const divId  = "#" + plainId;
  const editId = "#edit" + plainId;
  const textar = "#area" + plainId;
  const area = document.querySelector(textar);
  hideEdit(plainId);
};
const canc = document.querySelectorAll(".canceltext");
canc.forEach(c => {
  c.addEventListener('click', cancelEdit);
});

async function removeEdit (e) {
  let plainId = e.target.id;
  resultId = plainId.split('_');
  plainId = plainId.substring(1);
  const addId  = "#" + plainId;
  const editId = "#edit" + plainId;
  const textar = "#area" + plainId;
  const textarea = document.querySelector(textar);
  hideEdit(plainId);
  let rowId = Number(resultId[2]);
  if (Number.isInteger(rowId) && rowId > 0) {
    if (confirm("You are about to remove this row. Continue?")) {
      console.log('Yes!');
      let id = rowId;
      // ajax
      // removeRow(rowId);
      const removeRow = { user_id, demo, id }  
      try { 
        const response = await fetch('<?=$dir?>/api/valu_remove.php', { 
          method: 'POST', 
          body: JSON.stringify(removeRow) 
        });    
        if (!response.ok) { 
          console.log('Not OK');
          return; 
        } 
        const output = await response.json(); 
        console.log(output);    
        SuccessMessage('removeSaved');
        window.location.reload();        
      } catch (error) {
        console.log(error);
      }
      // $(document).ajaxStop(function(){
      //   window.location.reload();
      // });
    } else {
      console.log('Cancel!');
    }
  }
};
const removetext = document.querySelectorAll(".removetext");
removetext.forEach(c => {
  c.addEventListener('click', removeEdit);
});

async function saveEdit (e) {
  let plainId = e.target.id;
  plainId  = plainId.substring(1);
  resultId = plainId.split('_');
  const textar = "area" + plainId;
  const textarea = document.getElementById(textar);
  hideEdit(plainId);
  const divId = "#" + plainId;
  const edittext = document.querySelector(divId);
  edittext.innerHTML = textarea.value;
  let rowId = Number(resultId[2]);
  if (Number.isInteger(rowId) && rowId > 0) {
    // ajax
    // saveRow(rowId, textarea.value);    
    // data: {action:'save',user_id:user_id,id:id,label:label,demo:demo},
    let id = rowId;
    let label = textarea.value;
    const saveRow = { user_id, demo, id, label };
    try { 
      const response = await fetch('<?=$dir?>/api/valu_save.php', { 
        method: 'POST', 
        body: JSON.stringify(saveRow) 
      });    
      if (!response.ok) { 
        console.log('Not OK');
        return; 
      } 
      const output = await response.json(); 
      console.log(output);    
      SuccessMessage('editSaved');
      // window.location.reload();        
    } catch (error) {
      console.log(error);
    }
  }
};
const savetext = document.querySelectorAll(".savetext");
savetext.forEach(c => {
  c.addEventListener('click', saveEdit);
});

const newAddRow = document.querySelectorAll(".na");
const addnewadd = document.querySelector("#new_addition");
const cannewadd = document.querySelector("#cancel_new_addition");
const savnewadd = document.querySelector("#save_new_addition");

function showNewAdd () {
  newAddRow.forEach(nwa => {
    nwa.style.display = 'block';
  });
  addnewadd.style.display = 'none';
}
function hideNewAdd (e) {
  newAddRow.forEach(nwa => {
    nwa.style.display = 'none';
  });
  addnewadd.style.display = 'block';
}
async function saveNewAdd (e) {
  const id = e.target.id;
  const addtext = document.getElementById("new_addition_text");
  const type = 'a';
  const label = addtext.value;
  // ajax
  // newRow('a',addtext.value);
  const newRow = { user_id, demo, type, label }  
  try { 
    const response = await fetch('<?=$dir?>/api/valu_new.php', { 
      method: 'POST', 
      body: JSON.stringify(newRow) 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return; 
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('editSaved');
    // window.location.reload();        
  } catch (error) {
    console.log(error);
  }
  newAddRow.forEach(nwa => {
    nwa.style.display = 'none';
  });
  // addnewadd.style.display = 'block';

}
addnewadd.addEventListener('click', showNewAdd);
cannewadd.addEventListener('click', hideNewAdd);
savnewadd.addEventListener('click', saveNewAdd);

const newDedRow = document.querySelectorAll(".nd");
const addnewded = document.querySelector("#new_deduction");
const cannewded = document.querySelector("#cancel_new_deduction");
const savnewded = document.querySelector("#save_new_deduction");

function showNewDed () {
  newDedRow.forEach(nwa => {
    nwa.style.display = 'block';
  });
  addnewded.style.display = 'none';
}
function hideNewDed (e) {
  newDedRow.forEach(nwa => {
    nwa.style.display = 'none';
  });
  addnewded.style.display = 'block';
}
async function saveNewDed (e) {
  const id = e.target.id;
  const dedtext = document.getElementById("new_deduction_text");
  const type = 'd';
  const label = dedtext.value;
  // ajax
  // newRow('d',dedtext.value);
  const newRow = { user_id, demo, type, label }  
  try { 
    const response = await fetch('<?=$dir?>/api/valu_new.php', { 
      method: 'POST', 
      body: JSON.stringify(newRow) 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return; 
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('editSaved');
    // window.location.reload();        
  } catch (error) {
    console.log(error);
  }
  newDedRow.forEach(nwa => {
    nwa.style.display = 'none';
  });
  // addnewded.style.display = 'block';
}
addnewded.addEventListener('click', showNewDed);
cannewded.addEventListener('click', hideNewDed);
savnewded.addEventListener('click', saveNewDed);

SuccessMessage = function(id) {
  idHash = "#"+id;
  const savedDiv = document.getElementById(id);
  savedDiv.style.display='block';
  setTimeout(function() {
      $(idHash).fadeOut('slow');
  }, 700);
}

</script>