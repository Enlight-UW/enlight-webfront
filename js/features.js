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
