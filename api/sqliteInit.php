<?php
/**
 * Initialize the database (if need be). This describes the schema and creates
 * the tables for the Webfront.
 */
require 'constants.php';

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
            controllerID INTEGER PRIMARY KEY AUTOINCREMENT,
            acquire INTEGER NOT NULL,
            ttl INTEGER NOT NULL,
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

$stmt_insert_default_valves_legacy = [
        "INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (1, 'V1', 'Vertical caliper jet 1', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (2, 'V2', 'Vertical caliper jet 2', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES
            (3, 'V3', 'Vertical caliper jet 3', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (4, 'V4', 'Vertical caliper jet 4', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (5, 'V5', 'Vertical caliper jet 5', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (6, 'V6', 'Vertical caliper jet 6', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (7, 'V7', 'Vertical caliper jet 7', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (8, 'V8', 'Vertical caliper jet 8', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (9, 'V9', 'Vertical caliper jet 9', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (10, 'V10', 'Vertical caliper jet 10', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (11, 'VC', 'Vertical caliper jet center', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (12, 'VR', 'Vertical caliper jet ring', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (13, 'H1', 'Horizontal caliper jet 1 pointed up', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (14, 'H2', 'Horizontal caliper jet 2', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (15, 'H3', 'Horizontal caliper jet 3', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (16, 'H4', 'Horizontal caliper jet 4', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (17, 'H5', 'Horizontal caliper jet 5', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (18, 'H6', 'Horizontal caliper jet 6', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (19, 'H7', 'Horizontal caliper jet 7', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (20, 'H8', 'Horizontal caliper jet 8', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (21, 'H9', 'Horizontal caliper jet 9', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (22, 'H10', 'Horizontal caliper jet 10', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (23, 'HC', 'Horizontal caliper jet center', 0, 1)","
        INSERT INTO valves (ID, name, description, spraying, enabled)
        VALUES 
            (24, 'HR', 'Horizontal caliper jet ring', 0, 1)"];

$stmt_insert_testing_keys = [
        "INSERT INTO apikeys (apikey, name, priority, date) VALUES ('abc123', 'test api key 1', 10, strftime('%s','now'))",
        "INSERT INTO apikeys (apikey, name, priority, date) VALUES ('pri20a', '20 priority test key a', 20, strftime('%s','now'))",
        "INSERT INTO apikeys (apikey, name, priority, date) VALUES ('pri20b', '20 priority test key b', 20, strftime('%s','now'))",
        "INSERT INTO apikeys (apikey, name, priority, date) VALUES ('pri30a', '30 priority test key a', 30, strftime('%s','now'))",
        "INSERT INTO apikeys (apikey, name, priority, date) VALUES ('pri30b', '30 priority test key b', 30, strftime('%s','now'))",
    ];

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

foreach ($stmt_insert_default_valves_legacy as $s)
    runQueryAndPrintStatus($db, 'Inserting default valve...', $s);
runQueryAndPrintStatus($db, 'Inserting default keys...', $stmt_insert_testing_keys);

foreach($stmt_insert_testing_keys as $s)
    runQueryAndPrintStatus ($db, 'Inserting default key...', $s);

$db->close();

echo 'done.';
