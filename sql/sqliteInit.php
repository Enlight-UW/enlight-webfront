<?php
/**
 * Initialize the database (if need be). This describes the schema and creates
 * the tables for the Webfront.
 */

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
// Create tables
//

$db = new SQLite3;
$db->open('../webfront.sql');

// API key table
$stmt = $db->prepare($stmt_create_table_apikeys);

$res = $stmt->execute();

//
// Populate default valves
//