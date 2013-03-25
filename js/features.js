/* 
 * Project: enlight-webfront
 * File: features.js
 * Author: Alex Kersten
 * 
 * Some general site-wide features that we'll use over and over again.
 */


function getAjaxObject() {
    var xml;
                
    //Get new HTML from server.
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    } else {
        alert("Your browser does not support AJAX - you'll need to upgrade.");
    }
    
    return xml;
}


/**
 * Because we might have multiple things that react to the window resizing,
 * handle all of them here.
 */
function windowResized() {
    //Check to see if the pattern editor needs to be resized.
    if (patternEditorCanvasReady) {
        updatePatternEditorSize();
        repaintPatternEditor();
    }
}

//We don't want to do intense computation every single instant the user is
//resizing the window, so do nothing until 300ms after resizing is complete.
var resizeLock;
window.onresize = function() {
    clearTimeout(resizeLock);
    
    resizeLock = setTimeout(function() {
        windowResized();    
    }, 300);
};


//New state callback - this gets executed whenever we receive an updated state
//datagram from the server; this is responsible for updating the DOM with new
//information. The way it works is, if you require updated information, ID your
//element as FIELD_anyName - this is how we'll ID elements and update them
//to their correct value.


$(document).ready(function() {
    var ajaxStateUpdater = function (){
        $('#ajaxer').css('display', 'inline');
        //Request update from the server
        $.ajax(
        {
            type: "POST",
            url:"php/ajax.php",
            data: {
                updateState: "true"
            }
        }).done(function(msg) {
            $('#ajaxer').css('display', 'none');
            if (msg.substring(0, 6) == "error:") {
                window.location = 'error.php?id=' + msg.substring(6);
            }
            //alert(msg);
        });
        
        setTimeout(ajaxStateUpdater, 1000);
    };
    
    setTimeout(ajaxStateUpdater, 1000);
});