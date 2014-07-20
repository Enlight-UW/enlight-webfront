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
        name TEXT,
        priority INTEGER,
        date INTEGER,
        enabled INTEGER
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
        acquired INTEGER,
        expires INTEGER,
        priority INTEGER,
        queuePosition INTEGER,
        apikey REFERENCES apikeys (apikey)
        )
stmt;



$db = new SQLite3;
$db->open('../webfront.sql');

// API key table
$stmt = $db->prepare($stmt_create_table_apikeys);

$res = $stmt->execute();

echo $res->numColumns();