/* 
 * Project: enlight-webfront
 * File: todoModule.js
 * Author: Alex Kersten
 * 
 * Clientside JS for ajaxing to the server from the todo module.
 */


function refreshTodoList() {
    //Clear existing todo list
    document.getElementById('todoList').innerHTML = "";

    var xml;
                
    //Get new HTML from server.
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
                
    xml.onreadystatechange=function() {
        if (xml.readyState==4 && xml.status==200) {
            document.getElementById('todoList').innerHTML=xml.responseText;
        }
    }
                
    xml.open("POST", "modules/todoModule.php", true);
    xml.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xml.send("ajax_get_todoList=true");
}

function postTodoItem() {
    var xml;
    
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
}

function resolveTodoItem() {
    
}