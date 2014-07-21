<?php


function verifyAPIKey($key) use ($db) {
    $Q_QUERY_API_KEY_COUNT = <<<stmt
        SELECT COUNT(*) as count FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_COUNT);
    $stmt->bindValue(':key', $key, SQLITE3_STRING)
    $res = $stmt->execute();

    //TODO: Error check
    
    // If there are any returned, it's a valid key
    $r = $res->fetchArray();
    return $r['count'] > 0;
}

function getAPIKeyPriority($key) use ($db) {
    if (!verifyAPIKey($key))
        return 0;

    $Q_QUERY_API_KEY_PRIORITY = <<<stmt
        SELECT priority FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_PRIORITY);
    $stmt->bindValue(':key', $key, SQLITE3_STRING)
    //TODO: Error check
    $res = $stmt->execute();
    
    $r = $res->fetchArray();

    return $r['priority'];
    
}
