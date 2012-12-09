/* 
 * Project: enlight-webfront
 * File: serverModule.js
 * Author: Alex Kersten
 * 
 * Handles the button AJAX for the serverModule buttons.
 */

function sendTestUDPMessage() {
    var message = $('#UDPTestInput').val();
            
    if (message == "") {
        return;
    }
    
    $('#UDPTestInputList').html(message + '<br />' + $('#UDPTestInputList').html());
    
    var xml = getAjaxObject();
    
    msg = $.base64.encode(message);
    
    xml.open("POST", "modules/serverModule.php", true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   
    xml.send("ajax_testUDPMessage=" + msg);
}