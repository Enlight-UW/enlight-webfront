function postNewAPIKey(name, priority) {
    $.ajax(
    {
        type: "POST",
        url:"modules/adminModule.php",
        data: {
            "APIGenUser": name,
            "APIGenPriority": priority
        }
    }).done(function(msg) {
        $('#apiUsername').val('');
        $('#apiPriority').val('');
        refreshAPIKeyList();
    });
}

function refreshAPIKeyList() {
    $('#refreshAPIListButton').toggleClass('disabled', true);
    //Request update from the server
    $.ajax(
    {
        type: "POST",
        url:"modules/adminModule.php",
        data: {
            updateAPIKeyList: "true"
        }
    }).done(function(msg) {
        var obj = $.parseJSON(msg);

        $('#refreshAPIListButton').toggleClass('disabled', false);
            
        //Update our viewmodel with the new JSON object
        ko.mapping.fromJS(obj, viewModel);
    });
}

//Since the API Key list isn't updated constantly within the update loop, do it
//once here and then manually trigger it when the refresh button is pressed.
refreshAPIKeyList();