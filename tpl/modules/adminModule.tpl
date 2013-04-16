<script src="js/modules/adminModule.js"></script>

<h2>Policy Details</h2>
<p>Manage API and user access, among other administrative things here.</p>

<h3><abbr title="These are stored in /SHADOW/ApiKeys. The file format is KEY:NAME:PRIORITY.
    Keys with higher priorities will take precedence when requesting control
    of the fountain.">API Keys</abbr></h3>

<div class="well">
    <table class="table">
        <tr>
            <th>Key</th>
            <th>Name</th>
            <th>Priority</th>
        </tr>

        <tbody data-bind="template: { name: 'apiKey-template', foreach: apiItems, as: 'apiItem' }"></tbody>
    </table>

    <a href="#" id="refreshAPIListButton" onclick="refreshAPIKeyList(); return false;" class="btn"><i class="icon-refresh"> </i>Refresh list</a>
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
    <a href="#" onclick="postNewAPIKey($('#apiUsername').val(), $('#apiPriority').val()); return false" class="btn btn-primary">Generate</a>
</div>

<!-- Template for the API keys -->
<script type="text/html" id="apiKey-template">
    <tr>
        <td data-bind="text: apiItem.apiKeyItem"></td>
        <td data-bind="text: apiItem.apiNameItem"></td>
        <td data-bind="text: apiItem.apiPriorityItem"></td>
    </tr>
</script>

<!-- End of template -->