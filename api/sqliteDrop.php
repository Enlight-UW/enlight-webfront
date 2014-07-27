<?php
/**
 * Initialize the database (if need be). This describes the schema and creates
 * the tables for the Webfront.
 */
require 'constants.php';

$stmt_drop_apikeys = <<<stmt
        DROP TABLE apikeys;
stmt;

$stmt_drop_controlQueue = <<<stmt
        DROP TABLE controlQueue;
stmt;

$stmt_drop_valves = <<<stmt
        DROP TABLE valves;
stmt;

$stmt_drop_patterns = <<<stmt
        DROP TABLE patterns;
stmt;

$stmt_drop_patternData = <<<stmt
        DROP TABLE patternData;
stmt;

$db = new SQLite3('../' . $DB_FILENAME);

function runQueryAndPrintStatus($db, $msg, $query) {
    echo $msg;
    $stmt = $db->prepare($query);
    if ($stmt->execute() === FALSE)
        die($db->lastErrorMsg());
    echo ' ok<br />';    
}

runQueryAndPrintStatus($db, 'Drop API keys table...', $stmt_drop_apikeys);
runQueryAndPrintStatus($db, 'Drop valves table...', $stmt_drop_controlQueue);
runQueryAndPrintStatus($db, 'Drop control queue table...', $stmt_drop_valves);
runQueryAndPrintStatus($db, 'Drop patterns table...', $stmt_drop_patterns);
runQueryAndPrintStatus($db, 'Drop pattern data table...', $stmt_drop_patternData);

$db->close();

echo 'done.';
