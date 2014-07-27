<?php


function verifyAPIKey($db, $key) {
    $Q_QUERY_API_KEY_COUNT = <<<stmt
        SELECT COUNT(*) as count FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_COUNT);
    $stmt->bindValue(':key', $key);
    $res = $stmt->execute();

    //TODO: Error check
    
    // If there are any returned, it's a valid key
    $r = $res->fetchArray();
    return $r['count'] > 0;
}

function getAPIKeyPriority($db, $key) {
    if (!verifyAPIKey($key))
        return 0;

    $Q_QUERY_API_KEY_PRIORITY = <<<stmt
        SELECT priority FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_PRIORITY);
    $stmt->bindValue(':key', $key, SQLITE3_STRING);
    //TODO: Error check
    $res = $stmt->execute();
    
    $r = $res->fetchArray();

    return $r['priority'];
    
}

function getControllingKey() {
    $Q_QUERY_CONTROLLING_KEY = <<<stmt
        SELECT apikey, acquire AS acq
        FROM controlQueue
        WHERE queuePosition=0 AND acquire=MAX(acq)
stmt;

    $stmt = $db->prepare($Q_QUERY_CONTROLLING_KEY);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        return NULL;
    
    $ret = $res->fetchArray();
    return $ret[0]['apikey'];    
}
/**
 * Takes a result set from a SQLite3 query and returns all the rows as JSON. If
 * there is more than one row, they are returned in the format:
 * 
 *  [{"row0col0":"val","row0col1":"val"},{"row1col0":"val","row1col1":"val"}]
 * 
 * otherwise the surrounding braces are stripped and the format is:
 * 
 *  {"row0col0":"val","row0col1":"val"}
 * 
 * @param type $res The result set to convert to JSON.
 * @return string A JSON representation of the result set.
 */
function rowsAsJSON($res) {
    $count = 0;
    $rs = '[';
    
    while ($row = $res->fetchArray()) {       
        $count++;
        $rs .= json_encode($row) . ',';
    }
    
     // Shave last comma to conform to JSON standard
    $rs = substr($rs, 0, strlen($rs) - 1) . ']';
    
    // Get rid of array brackets if singleton
    if ($count == 1)
        $rs = substr($rs, 1, strlen($rs) - 2);
    
    if (strlen($rs) == 1)
        $rs = '[' . $rs;
    
    return $rs;
}
