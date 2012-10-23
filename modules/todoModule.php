<?php

/*
 * Project: CleanWebfront
 * File: todoModule.php
 * Author: Alex Kersten
 * 
 * Module for keeping a todo list. Be sure to update the GitHub wiki if/when you
 * change the tables/cols this thing uses in the DB.
 * 
 * Table is named `todo` with cols `id` `time` `priority` `name` `goal`
 */


//Check for any AJAX posted to this page...
if (isset($_POST['ajax_get_messages'])) {
    require "../php/startDatabase.php";


    $state = $_SESSION['db']->prepare("SELECT * FROM `todo` ORDER BY `priority` DESC");
    $state->execute();
    $result = $state->get_result();

    while ($row = $result->fetch_assoc()) {
        //TODO (Tuesday project?!) - format this nicely.
        echo $row['name'] . " " . $row['time'] . " " . $row['goal'] . " " . $row['priority'];
    }

    //Exit unless you want it to uselessly evaluate class inheritance...
    exit(0);
}

class todoModule extends module {

    function __construct() {
        parent::__construct("Todo");
    }

    function getInnerContent() {
        return '
            <script type="text/javascript">
            function refreshTodoList() {
                //Clear existing todo list
                document.getElementById(\'todoList\').innerHTML = "";

                var xml;
                
                //Get new HTML from server.
                if (window.XMLHttpRequest) {
                    xml = new XMLHttpRequest();
                }
                
                xml.onreadystatechange=function() {
                    if (xml.readyState==4 && xml.status==200) {
                        document.getElementById(\'todoList\').innerHTML=xml.responseText;
                    }
                }
                
                xml.open("POST", "modules/todoModule.php", true);
                xml.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xml.send("ajax_get_messages=true");
                
            }
            
            </script>
            

  <p>Todo is our own private laundry list of maintenance-related things for Enlight. We can put things like "order new bubble tubes" or "whip it out" here. If it\'s a bug with Webfront or the server, it goes on the GitHub issue tracker.</p>
<h3>Create New</h3>
<h3>Current Todo List</h3>
<a href="#" onclick="refreshTodoList()">Refresh (debug)</a>
<table class="table table-hover" id="todoList">


<tr>
    <th>User</th>
    <th>Date</th>
    <th>Goal</th>
    <th>Priority</th>
    <th>Actions</th>
</tr>





<tr>
    <td>Alex</td>
    <td>6.11.2015</td>
    <td>Avoid being added to the "manhole cover open" mailing list</td>
    <td><span class="label label-warning">Medium</span></td>
    <td><a href="#" class="btn btn-mini">Done!</a></td>
</tr>

<tr>
    <td>Dustin</td>
    <td>10.11.2012</td>
    <td>Finish the amazing polish the student shop did on the fountain before graduating</td>
    <td><span class="label label-info">Low</span></td>
    <td><a href="#" class="btn btn-mini">Done!</a></td>
</tr>

<tr>
    <td>PHP</td>
    <td>1.1.1970</td>
    <td>Write good code</td>
    <td><span class="label">Eventually</span></td>
    <td><a href="#" class="btn btn-mini">Done!</a></td>
</tr>

<tr>
    <td>Depeche Mode</td>
    <td>10.23.12</td>
    <td>Release an awesome new studio album and announce world tour dates. Also, clean the spillway.</td>
    <td><span class="label label-important">High</span></td>
    <td><a href="#" class="btn btn-mini">Done!</a></td>
</tr>


</table>

';
    }

}

?>
