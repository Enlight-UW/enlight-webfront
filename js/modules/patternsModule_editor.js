/* 
 * Project: enlight-webfront
 * File: patternsModule_editor.js
 * Author: Alex Kersten
 * 
 * Anything and everything to do with the client-side editor for patterns.
 * 
 * The data structures we generate here will be used to create the pattern files
 * which will have a giant bit map (not the .bmp kind) corresponding to the
 * grid, as well as the resolution parameter so we know how long each bit
 * represents.
 */

//Name of the patterns element in the HTML markup.
var patternEditorCanvasName = 'patternEditorCanvas';

//Actual element reference.
var patternEditorElement;

//Canvas draw context.
var patternEditorContext;

//Dimensions of the pattern editor, gathered from the HTML.
var patternEditorWidth, patternEditorHeight;

//How many cells to the right has the user scrolled on the pattern designer?
var patternEditorCellOffset = 0;

//Array that will store the bitmaps for each row. Each outer element of the
//array corresponds to a row and each inner element is a 32-bit value which we
//treat as flags for each cell selected (so with the default value of 480 max
//cells horizontally, we have 480 / 32 = 15 elements per row). That is, each
//integer represents 32 cells. This array is inited and blanked every time 
//initPatternEditorCanvas is called, which should be whenever we make a new
//pattern or load one.
var valveBitmaps;

//Mouse positions.
var mouseX, mouseY;

//------------------------------------------------------------------------------
//Some constants that probably shouldn't change but can if they have to I guess
//------------------------------------------------------------------------------
//How wide the valve list sidebar should be.
const patternEditorSidebarSize = 96;

//How wide each pattern cell should be.
const patternEditorCellWidth = 28;

//How tall each valve row should be drawn.
const patternEditorCellHeight = 14;

//Resolution (in milliseconds) of each discrete on/off state.
const patternEditorResolution = 250;

//Maximum length of a pattern. This should be a multiple of 8 for a efficient
//use of bitmaps, and it will imply that the max length of any pattern is this
//multiplied by the resolution. Default is 480 (120 seconds).
const patternEditorMaxLength = 480;

//List of all the valves.
const patternEditorValves = new Array(
    "V1", "V2", "V3", "V4", "V5", "V6", "V7", "V8", "V9", "V10",
    "VC", "VR",
    "H1", "H2", "H3", "H4", "H5", "H6", "H7", "H8", "H9", "H10",
    "HC", "HR"
    );


/**
 * Increment or decrement the current offset, and refresh the display.
 */
function incPatternEditor(amt) {
    patternEditorCellOffset+= amt;
    
    if (patternEditorCellOffset < 0) {
        patternEditorCellOffset = 0;
    }
    
    if (patternEditorCellOffset > patternEditorMaxLength) {
        patternEditorCellOffset = patternEditorMaxLength;
    }
    
    repaintPatternEditor();
}

/**
 * This function will be called whenever we create a new file or before loading
 * an existing one - it resets the canvas (i.e. functionality of the
 * "New Pattern" button).
 */
function initPatternEditorCanvas() {
    //Sanity check (it's really important that this is divisible by 32, or else
    //we won't be able to store things nicely in integers).
    if (patternEditorMaxLength % 32 != 0) {
        alert("That's not divisible by 32! >:3 Fix it! Things may not work.");
    }
    
    //Create the bitmap array.
    valveBitmaps = new Array(patternEditorValves.length);
    
    //For each row
    for (var i = 0; i < valveBitmaps.length; i++) {
        //Create its bitmap array
        valveBitmaps[i] = new Array(patternEditorMaxLength / 32);
        
        //And set them all to 0
        for (var j = 0; j < valveBitmaps[i].length; j++) {
            valveBitmaps[i][j] = 0;
        }
    }
    
    
    patternEditorElement = document.getElementById(patternEditorCanvasName);

    if (patternEditorElement.getContext) {
        patternEditorContext = patternEditorElement.getContext('2d');
    } else {
        alert("Couldn't create graphics context. Try a newer browser.");
    }
    
    patternEditorCellOffset = 0;
    
    updatePatternEditorSize();
    repaintPatternEditor();
}

/**
 * Called on repaint and init in order to keep the internally tracked size of
 * this element consistent with its onscreen size (like when the user resizes
 * the window). Call this before repainting so the pixel buffer is properly
 * resized. Defintely call repaint after this too, to update the canvas content.
 */
function updatePatternEditorSize() {   
    //Update width to correspond to size in browser.
    patternEditorWidth = $(patternEditorElement).width();

    //We'll need to calculate the height based on how many valves we have.
    patternEditorHeight = (patternEditorValves.length + 1) * patternEditorCellHeight;
    
    //Those are the dimensions read from the CSS.. We have to be fairly clever
    //here and update the actual HTML dimensions too, so that the pixel buffer
    //is properly recreated.
    $(patternEditorElement).attr("width", patternEditorWidth);
    $(patternEditorElement).attr("height", patternEditorHeight);
}


/**
 * Rendering method for the pattern editor. Call whenever the state changes via
 * user interaction. This will draw everything needed including rendering the
 * current pattern to the screen.
 */
function repaintPatternEditor() {
    //Background
    patternEditorContext.fillStyle = "#EFEEEF";
    patternEditorContext.beginPath();
    patternEditorContext.rect(0, 0, patternEditorWidth, patternEditorHeight);
    patternEditorContext.closePath();
    patternEditorContext.fill();
      
    //Draw horizontal guidelines
    for (var i = 0; i < patternEditorValves.length; i++) {        
        patternEditorContext.fillStyle = (i % 2 == 0? "#DFDDDF" : "#CFCCCF");
        patternEditorContext.beginPath();
        patternEditorContext.rect(0, (i + 1) * patternEditorCellHeight, patternEditorWidth, patternEditorCellHeight);
        patternEditorContext.closePath();
        patternEditorContext.fill();
    }
    
    //Draw vertical column guidelines starting at sidebar width and going to the
    //edge of the drawable area.
    for (var i = patternEditorSidebarSize; i < patternEditorWidth; i+= patternEditorCellWidth) {
        patternEditorContext.fillStyle = "#BFBBBF";
        patternEditorContext.beginPath();
        patternEditorContext.rect(i, patternEditorCellHeight, 1, patternEditorHeight);
        patternEditorContext.closePath();
        patternEditorContext.fill();
        
        //We'll also draw the active cells here
        var ai = (i - patternEditorSidebarSize) / patternEditorCellWidth + patternEditorCellOffset;
        
        if (ai >= patternEditorMaxLength) {
            //No attempting to draw blobs beyond max length. Even though this
            //shouldn't be a problem there's no point wasting cycles accessing
            //memory that will never be set.
            continue;
        }
        
        //For every row in this column, check to see if it has an active cell
        for (var j = 0; j < patternEditorValves.length; j++) {
            //Get the corresponding bitfield int
            var tmp = valveBitmaps[j][Math.floor(ai / 32)];
            
            //Check the position, See the patternEditorClick function if you're
            //confused.
            var mask = (1 << 31) >>> (ai % 32);
            if ((tmp & mask) != 0) {
                //This location is active. Draw a box.
                patternEditorContext.fillStyle = "#0099CC";
                patternEditorContext.beginPath();
                patternEditorContext.rect(i, (j + 1) * patternEditorCellHeight, patternEditorCellWidth, patternEditorCellHeight);
                patternEditorContext.closePath();
                patternEditorContext.fill();
            }
        }
    }
    
    //Draw the text in the sidebar... First the resolution, and then the valves.
    patternEditorContext.fillStyle = "#444444";
    patternEditorContext.font = '14px Monospace';
    
    patternEditorContext.fillText(patternEditorResolution + "ms each", 0, patternEditorCellHeight - 2);
    var cols = 0;
    
    for (var i = patternEditorSidebarSize; i < patternEditorWidth; i+= patternEditorCellWidth) {    
        if (patternEditorCellOffset + cols < patternEditorMaxLength) {
            //Draw the indexes on each column based on our current scroll offset.
            patternEditorContext.fillText(cols + patternEditorCellOffset, i + 2, patternEditorCellHeight - 2);
        }
        cols++;
    }
    
    //Valve Time
    for (var i = 0; i < patternEditorValves.length; i++) {
        patternEditorContext.fillText(patternEditorValves[i], 0, (i + 2) * (patternEditorCellHeight) - 2);
    }
}



function patternEditorMouseMove(event) {
    //stackoverflow.com/questions/1114465/getting-mouse-location-in-canvas ----
    if (event.offsetX) {
        mouseX = event.offsetX;
        mouseY = event.offsetY;
    } else if(event.layerX) {
        mouseX = event.layerX;
        mouseY = event.layerY;
    }
//--------------------------------------------------------------------------
}

/**
 * Click event for the editor. Manipulates the bit field in memory to reflect
 * the current state of the pattern, then repaints.
 */
function patternEditorClick() {
    //Translate the mouseX and mouseY to a cell.
    
    //Compensate for axis labels
    var eX = mouseX - patternEditorSidebarSize;
    var eY = mouseY - patternEditorCellHeight;
    
    //Find grid index from this
    var iX = eX / patternEditorCellWidth;
    var iY = eY / patternEditorCellHeight;
    iY = Math.floor(iY);
    
    //Add current offset to x
    iX += patternEditorCellOffset;
    iX = Math.floor(iX);
    
    if (iX >= patternEditorMaxLength) {
        //Even though javascript would happily let us write to this memory
        //location, at least in my testing, that's really sloppy form and it's
        //just asking for trouble. (This is beyond the bounds of our bitfield).
        return;
    }
    
    //Find that cell and toggle it.
    //It will be in the iY'th row
    //And iX indexes deep into the bitfield represented by 32-bit integers
    //So:
    var tmp = valveBitmaps[iY][Math.floor(iX / 32)];
    
    //Flip the (iX % 32)th bit (from the left) of this,
    //by xoring it with a mask we generate from iX
    var tmp2 = 1 << 31;
    tmp2 >>>= (iX % 32);
    tmp ^= tmp2;
    
    //Store back to the bitfiled
    valveBitmaps[iY][Math.floor(iX / 32)] = tmp;
    
    //Update the display
    repaintPatternEditor();
}