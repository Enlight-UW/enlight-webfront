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


//This is the Knockout viewmodel that all dynamic elements of the page will
//reference - this gets updated by the KO Mapping plugin - we'll just define
//the default values here.
var viewModel = ko.mapping.fromJSON('{"error":false,"errormessage":"No error"}');

//New state callback - this gets executed whenever we receive an updated state
//datagram from the server; this is responsible for updating the DOM with new
//information by updatnig the knockout viewmodel.
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
            var obj = $.parseJSON(msg);

            if (obj.error) {
                window.location = 'error.php?id=' + obj.errormessage;
            }
            
            $('#ajaxer').css('display', 'none');
            
            //Update our viewmodel with the new JSON object
            ko.mapping.fromJS(obj, viewModel);
            
            setTimeout(ajaxStateUpdater, 1000);
        });   
    };
    
    setTimeout(ajaxStateUpdater, 1000);
});