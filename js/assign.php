<script>

// Display Edits
function showhideEdit (e) {
  let plainId  = e.target.id;
  let resultId = plainId.split('_');
  let employId = resultId[1];
  let editId = "#edit_" + employId;
  let boxId = "#box_" + employId;
  let edi = document.querySelector(editId);
  let box = document.querySelector(boxId);
  edi.style.display = (edi.style.display != 'none') ? 'none' : 'block';
  box.style.display = (box.style.display != 'none') ? 'none' : 'block';
  e.preventDefault();
};
const tedit = document.querySelectorAll(".teamedit");
tedit.forEach(i => {
  i.addEventListener("click", showhideEdit);
});
const canc = document.querySelectorAll(".canceledit");
canc.forEach(c => {
  c.addEventListener('click', showhideEdit);
});

// Save Edits
const saveEdit = async (e) => {
  e.preventDefault();
  try {
    const plainId = e.target.id;
    const resultId = plainId.split('_');
    const employee_id = resultId[1];
    const employee_status = document.getElementById('employee_remove_'+employee_id).checked === true ? 0 : 1;
    const update_employee = {
      user_id: user_id,
      demo: demo,
      employee_id: employee_id,
      employee_name: document.getElementById('employee_name_'+employee_id).value,
      employee_position: document.getElementById('employee_position_'+employee_id).value,
      employee_email: document.getElementById('employee_email_'+employee_id).value,
      employee_phone: document.getElementById('employee_phone_'+employee_id).value,
      employee_status: employee_status
    } 
    const userBody = JSON.stringify(update_employee);
    const response = await fetch('<?=$dir?>/api/assign_update.php', { 
      method: 'POST', 
      body: userBody 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return; 
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('editSaved');
    let employId = resultId[1];
    let editId = "#edit_" + employId;
    let boxId = "#box_" + employId;
    let edi = document.querySelector(editId);
    let box = document.querySelector(boxId);    
    edi.style.display = (edi.style.display != 'none') ? 'none' : 'block';
    box.style.display = (box.style.display != 'none') ? 'none' : 'block';  
    window.location.reload();
  } catch (error) {
    console.log(error);
  }
}

const saveedit = document.querySelectorAll(".saveedit");
saveedit.forEach(c => {
  c.addEventListener('click', saveEdit);
});

// Display tasks
function showhideTasks (e) {
  let plainId = e.target.id;
  let resultId = plainId.split('_');
  let employId = resultId[1];
  let taskp = "#taskp_" + employId;
  let lisId = "#tlist_" + employId;
  let tas = document.querySelector(taskp);
  let lis = document.querySelector(lisId);
  tas.style.display = (tas.style.display != 'none') ? 'none' : 'block';
  lis.style.display = (lis.style.display != 'none') ? 'none' : 'block';
  e.preventDefault();
};
const assignedtasks = document.querySelectorAll(".assignedtasks");
assignedtasks.forEach(c => {
  c.addEventListener('click', showhideTasks);
});
const closetasks = document.querySelectorAll(".closetasks");
closetasks.forEach(c => {
  c.addEventListener('click', showhideTasks);
});

// Add new person
const addNewBtn = document.querySelector('#addnewlink');
const addNewFrm = document.querySelector('#addnewform');
addNewBtn.addEventListener('click', function (e) {
  addNewBtn.style.display = "none";
  addNewFrm.style.display = "block";
  e.preventDefault();
});
const cancelNewBtn = document.querySelector('#cancelnew');
cancelNewBtn.addEventListener('click', function (e) {
  addNewBtn.style.display = "block";
  addNewFrm.style.display = "none";
  e.preventDefault();
});

const newEmployee = async (e) => {
  e.preventDefault();
  addNewBtn.style.display = "block";
  addNewFrm.style.display = "none";
  try {
    const new_employee = {
      user_id: user_id,
      demo: demo,
      employee_name: document.getElementById('new_employee_name').value,
      employee_position: document.getElementById('new_employee_position').value,
      employee_email: document.getElementById('new_employee_email').value,
      employee_phone: document.getElementById('new_employee_phone').value,
    }
    const userBody = JSON.stringify(new_employee);
    const response = await fetch('<?=$dir?>/api/assign_new.php', { 
      method: 'POST', 
      body: userBody 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return;
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('newSaved');
    window.location.reload();
  } catch (error) {
    console.log(error);
  }
}

const saveNewBtn = document.querySelector('#savenew');
saveNewBtn.addEventListener('click', newEmployee);

</script>