<script src="js/modules/adminModule.js"></script>

<h2>Policy Details</h2>
<p>Manage API and user access, among other administrative things here.</p>
<h3>API Keys</h3>
<div class="well">
    <table class="table">
        <tr>
            <th>Key</th>
            <th>Name</th>
            <th>Priority</th>
        </tr>
    </table>
</div>
<h3>Generate Key</h3>
<div class="well">
    <strong>User/Service name:</strong>
    <input type="text" name="apiUsername" id="apiUsername" />
    <strong>
        <abbr title="When controlling the fountain, API keys with higher
              priority will be allowed to assume control of the valve states
              over those with lower priority. Non-interactive requests (state
              updates) are still allowed by the keys with lower priority.">
            Priority:
        </abbr>
    </strong>
    <input type="text" name="apiPriority" id="apiPriority" />
    <a href="#" onclick="return false" class="btn btn-primary">Generate</a>
</div>