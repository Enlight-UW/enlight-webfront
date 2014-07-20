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
$stmt_create_apikey_table = <<<stmt
   CREATE TABLE IF NOT EXISTS apikeys
       (apikey TEXT PRIMARY KEY,
        name TEXT,
        priority INTEGER,
        date INTEGER,
        enabled INTEGER)
stmt;


$stmt_create_

$db = new SQLite3;
$db->open('../webfront.sql');

// API key table
$stmt = $db->prepare($stmt_create_apikey_table);

$res = $stmt->execute();

echo $res->numColumns();