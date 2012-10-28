/* 
 * Project: enlight-webfront
 * File: todoModule.js
 * Author: Alex Kersten
 * 
 * Clientside JS for ajaxing to the server from the todo module. All of the user
 * input here will obviously be checked server-side to prevent terrible things
 * from happening to our precious database, so don't worry about any of these
 * functions.
 */


function refreshTodoList() {
    var xml = getAjaxObject();
                
    xml.onreadystatechange=function() {
        if (xml.readyState==4 && xml.status==200) {
            //Clear existing todo list
            document.getElementById('todoList').innerHTML = "";

            //Replace it.
            document.getElementById('todoList').innerHTML=xml.responseText; 
       }
    }
                
    xml.open("POST", "modules/todoModule.php", true);
    xml.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    
    xml.send("ajax_get_todoList=true");
}

function postTodoItem() {
    var xml = getAjaxObject();
    
    var goalText = $('#todoGoalText').val();
    var priorityText = $('#todoPriorityText').val();
    
    //Base 64 them, so that the & character works correctly, since we're POSTing
    //this string.
    goalText = $.base64.encode(goalText);
    priorityText = $.base64.encode(priorityText);
    
    if (goalText == "") {
        return;
    }
    
    xml.open("POST", "modules/todoModule.php", true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xml.send("ajax_post_todoItem=true&todoPriority=" + priorityText + "&todoGoal=" + goalText);
}

function resolveTodoItem(id) {
    var xml = getAjaxObject();
    
    if (id < 1) {
        //Not an integer
        return;
    }
    
    id = $.base64.encode(id + "");
    
    xml.open("POST", "modules/todoModule.php", true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   
    xml.send("ajax_resolve_todoItem=" + id);
}