<script src="js/modules/todoModule.js"></script>

<h2>Waiting For&hellip;</h2>
<p>The goals page is our own private laundry list of maintenance-related things for Enlight. We can put things like "order new bubble tubes" or "whip it out" here. If it\'s a bug with Webfront or the server, it goes on the GitHub issue tracker.</p>

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
                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" onclick="$('#todoPriorityText').val(1)">
                    <span class="label">Eventually</span>
                </label>
                <label class="radio">
                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"  onclick="$('#todoPriorityText').val(2)" checked>
                    <span class="label label-info">Low</span>
                </label>
                <label class="radio">
                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3"  onclick="$('#todoPriorityText').val(3)">
                    <span class="label label-warning">Medium</span>
                </label>
                <label class="radio">
                    <input type="radio" name="optionsRadios" id="optionsRadios4" value="option4" onclick="$('#todoPriorityText').val(4)">
                    <span class="label label-important">High</span>
                </label>
            </div>
        </div>

        <div class="control-group">
            <p class="control-label"></p>
            <div class="controls">
                <a href="#" class="btn btn-primary btn-small" onclick="
                    postTodoItem();
                    $('#todoGoalText').val('');
                    setTimeout('refreshTodoList()', 500); return false;">Post New Todo</a>
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