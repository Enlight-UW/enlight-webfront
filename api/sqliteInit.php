<?php
/**
 * Initialize the database (if need be). This describes the schema and creates
 * the tables for the Webfront.
 */
require '../constants.php';

//
// Table schemas
//

/**
 * A table of API keys. They have a name (like the name of the program it's
 * registered to), the actual API key (alphanumeric string), a priority (higher
 * priorities will be able to take over), a creation date (epoch timestamp), and
 * a boolean enabled value (0 = disabled or revoked).
 */
$stmt_create_table_apikeys = <<<stmt
        CREATE TABLE IF NOT EXISTS apikeys (
             apikey TEXT PRIMARY KEY,
             name TEXT NOT NULL,
             priority INTEGER NOT NULL,
             date INTEGER NOT NULL,
             enabled INTEGER DEFAULT 1 NOT NULL
         )
stmt;

/**
 * A table of api keys which are currently controlling or in queue for
 * controlling the fountain. Each has a controllerID associated with it which
 * represents this unique instance of fountain control (unique for a while, at
 * least). Aquired and expires timestamps determine a period of control, and a
 * numeric priority field determines who should be in control. Queue positions
 * are tracked here as well.
 */
$stmt_create_table_controlQueue = <<<stmt
        CREATE TABLE IF NOT EXISTS controlQueue (
            controllerID INTEGER PRIMARY KEY,
            acquired INTEGER NOT NULL,
            expires INTEGER NOT NULL,
            priority INTEGER NOT NULL,
            queuePosition INTEGER NOT NULL,
            apikey REFERENCES apikeys (apikey)
        )
stmt;

/**
 * A table of valves which describe the interactive elements of the fountain.
 * Each valve has a numeric ID, a name, a description, and boolean enabled and
 * spraying states.
 */
$stmt_create_table_valves = <<<stmt
        CREATE TABLE IF NOT EXISTS valves (
            ID INTEGER PRIMARY KEY,
            name TEXT UNIQUE NOT NULL,
            description TEXT,
            spraying INTEGER NOT NULL,
            enabled INTEGER DEFAULT 1 NOT NULL
        )
stmt;

/**
 * A table of patterns which are sequences of on/off triggers for valves. Each
 * pattern consists of an ID, a name, a description, an active field, and an
 * enabled field.
 */
$stmt_create_table_patterns = <<<stmt
        CREATE TABLE IF NOT EXISTS patterns (
            ID INTEGER PRIMARY KEY,
            name TEXT UNIQUE NOT NULL,
            description TEXT,
            active INTEGER NOT NULL,
            enabled INTEGER DEFAULT 1 NOT NULL
        )
stmt;

/**
 * A table of pattern data. Each pattern has associated with it a series of
 * actions, represented here. A single entry represents one action. An action
 * has a pattern ID reference, an activation time (since the start of pattern),
 * a valve to action, and an action to take.
 */
$stmt_create_table_patternData = <<<stmt
        CREATE TABLE IF NOT EXISTS patternData (
            patternID REFERENCES patterns(ID),
            time INTEGER NOT NULL,
            valve REFERENCES valves(ID),
            action INTEGER NOT NULL
        )        
stmt;


//
// Default values
//

$stmt_insert_default_valves = <<<stmt
        INSERT OR IGNORE INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (1, 'V1', 'Vertical caliper jet 1', 0, 1),
            (2, 'V2', 'Vertical caliper jet 2', 0, 1),
            (3, 'V3', 'Vertical caliper jet 3', 0, 1),
            (4, 'V4', 'Vertical caliper jet 4', 0, 1),
            (5, 'V5', 'Vertical caliper jet 5', 0, 1),
            (6, 'V6', 'Vertical caliper jet 6', 0, 1),
            (7, 'V7', 'Vertical caliper jet 7', 0, 1),
            (8, 'V8', 'Vertical caliper jet 8', 0, 1),
            (9, 'V9', 'Vertical caliper jet 9', 0, 1),
            (10, 'V10', 'Vertical caliper jet 10', 0, 1),
            (11, 'VC', 'Vertical caliper jet center', 0, 1),
            (12, 'VR', 'Vertical caliper jet ring', 0, 1),
            (13, 'H1', 'Horizontal caliper jet 1 (pointed up)', 0, 1),
            (14, 'H2', 'Horizontal caliper jet 2', 0, 1),
            (15, 'H3', 'Horizontal caliper jet 3', 0, 1),
            (16, 'H4', 'Horizontal caliper jet 4', 0, 1),
            (17, 'H5', 'Horizontal caliper jet 5', 0, 1),
            (18, 'H6', 'Horizontal caliper jet 6', 0, 1),
            (19, 'H7', 'Horizontal caliper jet 7', 0, 1),
            (20, 'H8', 'Horizontal caliper jet 8', 0, 1),
            (21, 'H9', 'Horizontal caliper jet 9', 0, 1),
            (22, 'H10', 'Horizontal caliper jet 10', 0, 1),
            (23, 'HC', 'Horizontal caliper jet center', 0, 1),
            (24, 'HR', 'Horizontal caliper jet ring', 0, 1)
stmt;

//
// Create tables
//

$db = new SQLite3('../' . $DB_FILENAME);

function runQueryAndPrintStatus($db, $msg, $query) {
    echo $msg;
    $stmt = $db->prepare($query);
    if ($stmt->execute() === FALSE)
        die($db->lastErrorMsg());
    echo ' ok<br />';    
}

runQueryAndPrintStatus($db, 'Creating API keys table...', $stmt_create_table_apikeys);
runQueryAndPrintStatus($db, 'Creating valves table...', $stmt_create_table_valves);
runQueryAndPrintStatus($db, 'Creating control queue table...', $stmt_create_table_controlQueue);
runQueryAndPrintStatus($db, 'Creating patterns table...', $stmt_create_table_patterns);
runQueryAndPrintStatus($db, 'Creating pattern data table...', $stmt_create_table_patternData);

//
// Populate default valves
//

runQueryAndPrintStatus($db, 'Inserting default valves...', $stmt_insert_default_valves);

echo 'done.';
