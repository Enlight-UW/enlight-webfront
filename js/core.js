/* 
 * Project: enlight-webfront
 * File: core.js
 * Author: Alex Kersten
 * 
 * This file encapsulates the core Javascript functionalities of the Webfront.
 * Global handlers (window resize, ajax updating) are handled here, as well
 * as the knockout viewmodel which keeps all of the dynamic elements up to date.
 * 
 * The Webfront using the KO Mapping plugin to dynamically update local JS (and
 * consequently, DOM) data from the JSON returned by the server on update
 * responses.
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
var defaultData = {
    error: false,
    errormessage: "No error",
    testvalue: "NO MESSAGES YET"
}

var viewModel = ko.mapping.fromJS(defaultData);

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

            //Check if we need to redirect to an error page.
            if (obj.error) {
                window.location = 'error.php?id=' + obj.errormessage;
            }
            
            //Hide the worker indicator
            $('#ajaxer').css('display', 'none');
            
            //Update our viewmodel with the new JSON object
            ko.mapping.fromJS(obj, viewModel);
            
            setTimeout(ajaxStateUpdater, 1000);
        });   
    };
    
    setTimeout(ajaxStateUpdater, 1000);
    
    ko.applyBindings(viewModel);
});