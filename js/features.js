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