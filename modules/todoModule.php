<?php

/*
 * Project: CleanWebfront
 * File: todoModule.php
 * Author: Alex Kersten
 * 
 * Module for keeping a todo list. The format of the SQL table is the following:
 * todo [ id | time | priority | name | goal | resolver ]
 *       pkey tstamp        int   text   text       text
 * 
 * If resolver is set, the item is considered resolved and will display the name
 * of the resolver next to it. id and time are set by the database.
 */


// The first thing we'll do is check for anything AJAX'd to this page and
// verify session credentials. The calls here come from clientside code
// /js/modules/todoModule.js

if (isset($_POST['ajax_get_todoList'])) {
    require "../php/startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }


    $state = $_SESSION['db']->
            prepare("SELECT * FROM `todo` ORDER BY `resolver` ASC, `priority` DESC, `time` DESC");

    $state->execute();
    $result = $state->get_result();

    echo '<tr>
            <th>#</th>
            <th>User</th>
            <th>Date</th>
            <th>Goal</th>
            <th>Priority</th>
            <th>Action</th>
          </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['id'] . '</td><td>' .
        htmlspecialchars($row['name']) . '</td><td>' . $row['time'] .
        '</td><td>' . htmlspecialchars($row['goal']) . '</td><td>';

        if ($row['resolver'] != '') {
            //It's resolved.
            echo '<span class="label label-success"><i class="icon-ok icon-white"></i> Resolved</span></td><td>';
            echo '<span class="label label-success">'
            . htmlspecialchars($row['resolver']) . '</span></td></tr>';
        } else {
            //Switch on the priority to decide the color of the label...
            $labelStr = '<span class="label ';

            switch ($row['priority']) {
                case 1:
                    $labelStr .= '">Eventually</span>';
                    break;
                case 2:
                    $labelStr .= 'label-info">Low</span>';
                    break;
                case 3:
                    $labelStr .= 'label-warning">Medium</span>';
                    break;
                case 4:
                    $labelStr .= 'label-important">High</span>';
                    break;
                default:
                    $labelStr .= 'label-inverse">The Situation</span>';
            }

            echo $labelStr . '</td><td>';
            echo '<a href="#" class="btn btn-small" onclick="resolveTodoItem(' .
            $row['id'] .
            ');setTimeout(\'refreshTodoList()\', 500); return false;"><i class="icon-ok"></i> Done!</a></td></tr>';
        }
    }

    exit(0);
}


if (isset($_POST['ajax_post_todoItem'])) {
    require "../php/startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    $state = $_SESSION['db']->prepare("INSERT INTO `todo` (`priority`, `name`, `goal`) VALUES (?, ?, ?)");

    $state->bind_param("iss", base64_decode($_POST['todoPriority']), $_SESSION['USERNAME'], base64_decode($_POST['todoGoal']));
    $state->execute();

    exit(0);
}


if (isset($_POST['ajax_resolve_todoItem'])) {
    require "../php/startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    $itemId = base64_decode($_POST['ajax_resolve_todoItem']);

    //Check that that's actually an integer
    if (intval($itemId < 1)) {
        die("No such item.");
    }

    $itemId = intval($itemId);

    $state = $_SESSION['db']->prepare("UPDATE `todo` SET `resolver` = ? WHERE `id` = ?");
    $state->bind_param("si", $_SESSION['USERNAME'], $itemId);
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
            <!--<h2>Goals, Hopes &amp; Dreams</h2>-->
            <h2>Waiting For&hellip;</h2>
            <p>Todo is our own private laundry list of maintenance-related things for Enlight. We can put things like "order new bubble tubes" or "whip it out" here. If it\'s a bug with Webfront or the server, it goes on the GitHub issue tracker.</p>
            
            <h3>Add Todo Item</h3>
            <div class="well">
            <form class="form-horizontal">
                <div class="control-group">
                    <p class="control-label">Goal</p>
                    <div class="controls">
                        <textarea name="todoGoal" id="todoGoalText" style="width: 95%;" rows="6"></textarea>
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
                    <p class="control-label"></p>
                    <div class="controls">
                        <a href="#" class="btn btn-primary btn-small" onclick="
                        postTodoItem();
                        $(\'#todoGoalText\').val(\'\');
                        setTimeout(\'refreshTodoList()\', 500); return false;">Post New Todo</a>
                    </div>
                </div>

                <input type="text" name="todoPriority" value="2" id="todoPriorityText" style="display: none;"/>
            </form>
            </div>

         
            <h3>Current Todo List</h3>
            <div class="well">
            <a href="#" onclick="refreshTodoList(); return false;" class="btn btn-small"><i class="icon-refresh"></i> Refresh List</a><br /><br />
            
            <!-- This table will be populated via the JS -->
            <table class="table table-striped" id="todoList">
                <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Goal</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </table>
            

            <script type="text/javascript">
                //We\'ve got that, now refresh it for the first display.
                refreshTodoList();
            </script>
            </div>
';
    }

}

?>
