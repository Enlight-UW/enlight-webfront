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


/*
 * The first thing we'll do is check for anything AJAX'd to this page and
 * verify session credentials. The calls here come from clientside code in
 * /js/modules/todoModule.js
 */

if (isset($_POST['ajax_get_todoList'])) {
    require "../php/startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }


    $state = $_SESSION['db']->prepare("SELECT * FROM `todo` ORDER BY `priority` DESC");
    $state->execute();
    $result = $state->get_result();

    echo '            <tr>
                <th>User</th>
                <th>Date</th>
                <th>Goal</th>
                <th>Priority</th>
                <th>Actions</th>
            </tr>
';

    while ($row = $result->fetch_assoc()) {
        //TODO (Tuesday project?!) - format this nicely.
        echo '<tr><td>' . $row['name'] . "</td><td>" . $row['time'] . "</td><td>" . $row['goal'] . "</td><td>" . $row['priority'] . "</td></tr>";
    }

    //Exit unless you want it to uselessly evaluate class inheritance...
    exit(0);
}

if (isset($_POST['ajax_post_todoItem'])) {
    require "../php/startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    $state = $_SESSION['db']->prepare("INSERT INTO `todo` (`priority`, `name`, `goal`) VALUES (?,?,?)");

    //TODO: Make sure prepared statements are actually sanitizing this!
    $state->bind_param("iss", $_POST['todoPriority'], $_SESSION['USERNAME'], $_POST['todoGoal']);
    $state->execute();

    exit(0);
}

class todoModule extends module {

    function __construct() {
        parent::__construct("Todo");
    }

    function getInnerContent() {
        return '
            <script src="js/modules/todoModule.js"></script>           
            <p>Todo is our own private laundry list of maintenance-related things for Enlight. We can put things like "order new bubble tubes" or "whip it out" here. If it\'s a bug with Webfront or the server, it goes on the GitHub issue tracker.</p>
            <h3>Add Item</h3>
            <form class="form-horizontal">
                <div class="control-group">
                    <p class="control-label">Goal</p>
                    <div class="controls">
                        <textarea type="textarea" name="todoGoal" id="todoGoalText"></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <p class="control-label">Priority</p>
                    <div class="controls">
                        <label class="radio">
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" onclick="$(\'#todoPriorityText\').val(1)">
                            <span class="label">Eventually</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"  onclick="$(\'#todoPriorityText\').val(2)" checked>
                            <span class="label label-info">Low</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3"  onclick="$(\'#todoPriorityText\').val(3)">
                            <span class="label label-warning">Medium</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="optionsRadios" id="optionsRadios4" value="option4" onclick="$(\'#todoPriorityText\').val(4)">
                            <span class="label label-important">High</span>
                        </label>
                    </div>
                </div>
                
                <div class="control-group">
                    <p class="control-label">Ready?</p>
                    <div class="controls">
                        <a href="#" class="btn btn-primary" onclick="postTodoItem()">Add</a>
                    </div>
                </div>

            <input type="text" name="todoPriority" value="2" id="todoPriorityText" style="display: none;"/>
            </form>
            

            
            
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
            

            <script type="text/javascript">
                //We\'ve got that, now refresh it for the first display.
                refreshTodoList();
            </script>
';
    }

}

?>
