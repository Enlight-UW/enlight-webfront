/*
 * Sends the webfront API key to the server and, if successful, the binding's
 * override variable becomes true and causes the override controls to become
 * active.
 */
function requestOverride() {
    //TODO: Actually request the override from the server - for now (since there
    //aren't any other devices that could request access, we'll just allow it).
    viewModel.LMOC(true);
}

function toggleValve(which) {
    //Get the existing state from our binding and change what needs to be
    //changed
    var newState = viewModel.valveState ^ which;
    
    $.ajax(
    {
        type: "POST",
        url:"php/ajax.php",
        data: {
            valveState: newState
        }
    });
}

function toggleRestrict(which) {
    //Get the existing restrict state and toggle the one we want.
    var newState = viewModel.restrictState ^ which;
    
    $.ajax(
    {
        type: "POST",
        url:"php/ajax.php",
        data: {
            restrictState: newState
        }
    });
}