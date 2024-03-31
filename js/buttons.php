<script>

// show user pulldown for all checked
function showhideDate () {
  let checkedtasksdate = document.getElementById('checkedtasksdate');
  if (checkedtasksdate.style.display == 'none') {
    checkedtasksdate.style.display = 'block';
    checkedtaskselect.style.display = 'none';
	  checkedtasksend.style.display = 'none';
  } else {
    checkedtasksdate.style.display = 'none';
  }
}
const dateeditallbtn = document.querySelectorAll(".dateeditallbtn");
dateeditallbtn.forEach(c => {
  c.addEventListener('click', showhideDate);
});


// show user pulldown for all checked
function showhidePulldown () {
  let checkedtaskselect = document.getElementById('checkedtaskselect');
  if (checkedtaskselect.style.display == 'none') {
    checkedtaskselect.style.display = 'block';
    checkedtasksdate.style.display = 'none';
	  checkedtasksend.style.display = 'none';
  } else {
    checkedtaskselect.style.display = 'none';
  }
}
const assigneditallbtn = document.querySelectorAll(".assigneditallbtn");
assigneditallbtn.forEach(c => {
  c.addEventListener('click', showhidePulldown);
});


// Mark All Complete
const completeAllChecked = async () => {
// function completeAllChecked () {
  const task_ids = [];
  const editchckbx = document.querySelectorAll(".editchckbx");
  editchckbx.forEach(d => {
    let plainId = d.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    if (d.checked) {
      task_ids.push(tasksId);
    }
  });
  if (task_ids.length > 0) {
    if (confirm("The tasks with a checked box will be marked complete. Continue?")) {
      // markAllCompleted(completedTasks);
      const markAllCompleted = { user_id, demo, task_ids };
      try { 
        const response = await fetch('<?=$dir?>/api/buttons_markallcomplete.php', { 
          method: 'POST', 
          body: JSON.stringify(markAllCompleted) 
        });    
        if (!response.ok) { 
          console.log('Not OK');
          return; 
        } 
        const output = await response.json(); 
        console.log(output);    
        SuccessMessage('editSaved');
        window.location.reload();        
      } catch (error) {
        console.log(error);
      }
    } else {
      console.log('Cancel!');
    }
  } else {
    alert("No tasks have been selected");
  }
}
const saveeditallbtn = document.querySelectorAll(".saveeditallbtn");
saveeditallbtn.forEach(c => {
  c.addEventListener('click', completeAllChecked);
});


// Mark All Due Date
const dateTask = async () => {
//function dateTask () {
  const duedate = document.getElementById("duedateforall").value;
  const task_ids = [];
  const editchckbx = document.querySelectorAll(".editchckbx");
  editchckbx.forEach(d => {
    let plainId = d.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    if (d.checked) {
      task_ids.push(tasksId);
    }
  });
  if (task_ids.length > 0) {
    if (confirm("The tasks with a checked box will be modified. Continue?")) {
      const markAllDate = { user_id, demo, task_ids, duedate };
      try { 
        const response = await fetch('<?=$dir?>/api/buttons_markallduedate.php', { 
          method: 'POST', 
          body: JSON.stringify(markAllDate) 
        });    
        if (!response.ok) { 
          console.log('Not OK');
          return; 
        } 
        const output = await response.json(); 
        console.log(output);    
        SuccessMessage('editSaved');
        window.location.reload();        
      } catch (error) {
        console.log(error);
      }
    } else {
      console.log('Cancel!');
    }
  } else {
    alert("No tasks have been selected");
  }

}
const alldatepicker = document.querySelectorAll(".alldatepicker");
alldatepicker.forEach(c => {
  c.addEventListener('change', dateTask);
});


// Mark All Assigned
const assignPerson = async (e) => {
// function assignPerson (e) {
  let employee_id = e.target.value;
  const task_ids = [];
  const editchckbx = document.querySelectorAll(".editchckbx");
  editchckbx.forEach(d => {
    let plainId = d.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    if (d.checked) {
      task_ids.push(tasksId);
    }
  });
  if (task_ids.length > 0) {
    if (confirm("The tasks with a checked box will be modified. Continue?")) {
      // markAllAssigned(completedTasks,employee_id);
      const markAllAssigned = { user_id, demo, task_ids, employee_id };
      try { 
        const response = await fetch('<?=$dir?>/api/buttons_markallassigned.php', { 
          method: 'POST', 
          body: JSON.stringify(markAllAssigned) 
        });    
        if (!response.ok) { 
          console.log('Not OK');
          return; 
        } 
        const output = await response.json(); 
        console.log(output);    
        SuccessMessage('editSaved');
        window.location.reload();        
      } catch (error) {
        console.log(error);
      }
    } else {
      console.log('Cancel!');
    }
  } else {
    alert("No tasks have been selected");
  }
}
const personselect = document.querySelectorAll(".personselect");
personselect.forEach(c => {
  c.addEventListener('change', assignPerson);
});


// show user Send Email for all checked
const emailCreate = async (e) => {
//function emailCreate (e) {
  const task_ids = [];
  const editchckbx = document.querySelectorAll(".editchckbx");
  editchckbx.forEach(d => {
    let plainId = d.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    if (d.checked) {
      task_ids.push(tasksId);
    }
  });
  if (task_ids.length > 0) {
    if (confirm("The tasks with a checked box will be sent in an email. Continue?")) {
      let email_from = '<?=$email_from?>';
      let to = document.getElementById('report_to').value;
      let cc = document.getElementById('report_cc').value;
      let bcc= email_from + ", " + document.getElementById('report_bcc').value;
      let subject = "Exit Strategy Dashboard - Task List";
      let message  = "<p>" + document.getElementById('report_m').value + "</p>";
      if (to != "") {
        const sendEmail = { user_id, demo, task_ids, email_from, to, cc, bcc, subject, message };
        // sendEmail(report_to, report_cc, report_bcc, report_m, checkedTasks);
        try { 
          const response = await fetch('<?=$dir?>/api/buttons_sendemail.php', { 
            method: 'POST', 
            body: JSON.stringify(sendEmail) 
          });    
          if (!response.ok) { 
            console.log('Not OK');
            return; 
          } 
          const output = await response.json(); 
          console.log(output);    
          SuccessMessage('editSaved');
          window.location.reload();   
        } catch (error) {
          console.log(error);
        }
      } else {
        console.log("ERROR: to");
      }
      let eb = document.querySelector("#checkedtasksend");
      eb.style.display = 'none';
      e.preventDefault();
    } else {
      console.log('Cancel!');
    }
  } else {
    alert("No tasks have been selected");
  }
};
function showhideSend () {
  let checkedtasksend = document.getElementById('checkedtasksend');
  if (checkedtasksend.style.display == 'none') {
    checkedtasksend.style.display = 'block';
    checkedtasksdate.style.display = 'none';
    checkedtaskselect.style.display = 'none';
  } else {
    checkedtasksend.style.display = 'none';
  }
}
const sendeditallbtn = document.querySelectorAll(".sendeditallbtn");
sendeditallbtn.forEach(c => {
  c.addEventListener('click', showhideSend);
});
const emailcancel = document.querySelectorAll(".emailcancel");
emailcancel.forEach(c => {
  c.addEventListener('click', showhideSend);
});
const emailsend = document.querySelectorAll(".emailsend");
emailsend.forEach(c => {
  c.addEventListener('click', emailCreate);
});


// Mark All Removed
const removeAllChecked = async () => {
// function removeAllChecked () {
  const task_ids = [];
  const editchckbx = document.querySelectorAll(".editchckbx");
  editchckbx.forEach(d => {
    let plainId = d.id;
    let resultId= plainId.split('_');
    let tasksId = resultId[1];
    if (d.checked) {
      task_ids.push(tasksId);
    }
  });
  if (task_ids.length > 0) {
    if (confirm("The tasks with a checked box will be removed. Continue?")) {
      // markAllRemoved(completedTasks);      
      const markAllRemoved = { user_id, demo, task_ids };
      try { 
        const response = await fetch('<?=$dir?>/api/buttons_markallremoved.php', { 
          method: 'POST', 
          body: JSON.stringify(markAllRemoved) 
        });    
        if (!response.ok) { 
          console.log('Not OK');
          return; 
        } 
        const output = await response.json(); 
        console.log(output);    
        SuccessMessage('editSaved');
        window.location.reload();        
      } catch (error) {
        console.log(error);
      }
    } else {
      console.log('Cancel!');
    }
  } else {
    alert("No tasks have been selected");
  }
}
const removeeditallbtn = document.querySelectorAll(".removeeditallbtn");
removeeditallbtn.forEach(c => {
  c.addEventListener('click', removeAllChecked);
});

</script>