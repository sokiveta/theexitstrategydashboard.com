<script>
// ShowHide New Task
function showhideNew (e) {
  let addnewtaskbtn = document.querySelector("#addnewtaskbtn");
  let addnewtaskbox = document.querySelector("#addnewtaskbox");
  addnewtaskbtn.style.display = (addnewtaskbtn.style.display != 'none') ? 'none' : 'block';
  addnewtaskbox.style.display = (addnewtaskbox.style.display != 'none') ? 'none' : 'block';
  e.preventDefault();
};

// Save button clicked // Save New Task
const saveNewTask = async (e) => {
  e.preventDefault();
  try {
    let task_title = document.getElementById('newtasksubject').value;
    let task_description = document.getElementById('newtaskdescription').value;
    let task_location = document.getElementById('newtasklocation').value;
    task_location = (task_location == "") ? "No location set" : task_location;
    let task_edate = document.getElementById('newtaskdate').value;
    task_edate = (task_edate == "") ? "0000-00-00" : task_edate;
    let chapter_id = document.getElementById('newtaskchapter').value;
    let employee_id = document.getElementById('newtaskemployee').value;
    const newTask = { user_id, demo, task_title,task_description,task_location,task_edate,chapter_id,employee_id }
    const taskBody = JSON.stringify(newTask);
    const response = await fetch('<?=$dir?>/api/tasks_newtask.php', { 
      method: 'POST', 
      body: taskBody 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return; 
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('newSaved');
    let addnewtaskbtn = document.querySelector("#addnewtaskbtn");
    let addnewtaskbox = document.querySelector("#addnewtaskbox");
    addnewtaskbtn.style.display = (addnewtaskbtn.style.display != 'none') ? 'none' : 'block';
    addnewtaskbox.style.display = (addnewtaskbox.style.display != 'none') ? 'none' : 'block';
    window.location.reload();
  } catch (error) {
    console.log(error);
  }
};

// New Task buttons
const addnewtaskbtn = document.querySelector("#addnewtaskbtn");
addnewtaskbtn.addEventListener("click", showhideNew);
const cancelnewtaskbtn = document.querySelector("#cancelnewtaskbtn");
cancelnewtaskbtn.addEventListener("click", showhideNew);
const savenewtaskbtn = document.querySelector("#savenewtaskbtn");
savenewtaskbtn.addEventListener("click", saveNewTask);

// ShowHide Edit window
function showhideEdit (e) {
  let plainId = e.target.id;
  console.log(plainId);
  let resultId= plainId.split('_');
  let tasksId = resultId[1];
  let taskId = "#taskcontainer_" + tasksId;
  let boxId = "#taskedit_" + tasksId;
  let tsk = document.querySelector(taskId);
  let box = document.querySelector(boxId);
  tsk.style.display = (tsk.style.display != 'none') ? 'none' : 'block';
  box.style.display = (box.style.display != 'none') ? 'none' : 'block';
  e.preventDefault();
};
const edittaskbtn = document.querySelectorAll(".edittaskbtn");
edittaskbtn.forEach(i => {
  i.addEventListener("click", showhideEdit);
});
const canceledit = document.querySelectorAll(".canceledit");
canceledit.forEach(c => {
  c.addEventListener('click', showhideEdit);
});

const saveEdit = async (e) => {
  e.preventDefault();
  try {
    let plainId = e.target.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    let task_edate = document.getElementById('edate_'+tasksId).value;
    task_edate = (task_edate == "") ? "0000-00-00" : task_edate;
    let task_description = document.getElementById('taskdesc_'+tasksId).value;
    let employee_id = document.getElementById('empid_'+tasksId).value;
    let task_location = document.getElementById('tlocation_'+tasksId).value;
    task_location = (task_location == "No location set") ? "" : task_location;
    let task_status = (document.getElementById('teditcomp_'+tasksId).checked === true) ? "checked" : "unchecked";
    const updateTask = { user_id,demo,tasksId,task_edate,task_description,employee_id,task_location,task_status }
    const taskBody = JSON.stringify(updateTask);
    const response = await fetch('<?=$dir?>/api/tasks_updatetask.php', { 
      method: 'POST', 
      body: taskBody 
    });    
    if (!response.ok) { 
      console.log('Not OK');
      return; 
    } 
    const output = await response.json(); 
    console.log(output);    
    SuccessMessage('editSaved');
    let taskId = "#taskcontainer_" + tasksId;
    let boxId = "#taskedit_" + tasksId;
    let tsk = document.querySelector(taskId);
    let box = document.querySelector(boxId);
    tsk.style.display = (tsk.style.display != 'none') ? 'none' : 'block';
    box.style.display = (box.style.display != 'none') ? 'none' : 'block';
    window.location.reload();
  } catch (error) {
    console.log(error);
  }
};
const saveedit = document.querySelectorAll(".saveedit");
saveedit.forEach(c => {
  c.addEventListener('click', saveEdit);
});

</script>