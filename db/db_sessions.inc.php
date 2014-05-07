<?php

# Script 3.1 - db_sessions.inc.php
//require_once ('../config.php');
require_once($serverpath['db'] . '/opslagmedium.php');


/*
 *  This page creates the functional interface for 
 *  storing session data in a database.
 *  This page also starts the session.
 */

// Global variable used for the database 
// connections in all session functions:
$sdbc = NULL;

function getConnection() {// to get the connection
    global $link;

    if (isset($link)) {
        return $link;
    }

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_error());
        die();
    } else {
        return $link;
    }
}

function doQuery($link, $query) { // to execute the connection
    $result = $link->query($query);
    if (is_bool($result) === true) {
        return $result; //in the case of a update 
    } else {
        if (!$result->num_rows == 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return null;
        }
    }
}

// Define the open_session() function:
// This function takes no arguments.
// This function should open the database connection.
// This function should return true.
function open_session() {


    $sdbc = getConnection();
    // Connect to the database:
    // $sdbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)
    //  or die ("Unable to connect to database");

    return true;
}

// End of open_session() function.
// Define the close_session() function:
// This function takes no arguments.
// This function closes the database connection.
// This function returns the closed status.
function close_session() {
    $sdbc = getConnection();
    return mysqli_close($sdbc);
}

// End of close_session() function.
// Define the read_session() function:
// This function takes one argument: the session ID.
// This function retrieves the session data.
// This function returns the session data as a string.
function read_session($sid) {

    $sdbc = getConnection();

    // Query the database:
    $q = sprintf('SELECT data FROM sessions WHERE id="%s"', mysqli_real_escape_string($sdbc, $sid));
    $r = doQuery($sdbc, $q);
    if (count($r) == 1) {
        $data = $r[0];
        $data = $data['data'];
        //$bool = session_decode($data2);
        return $data;
    } else {
        return '';
    }
}

// End of read_session() function.
// Define the write_session() function:
// This function takes two arguments: 
// the session ID and the session data.
function write_session($sid, $data) {

    $sdbc = getConnection();
    $q = sprintf('SELECT data FROM sessions WHERE id="%s"', mysqli_real_escape_string($sdbc, $sid));
    $r = doQuery($sdbc, $q);
    if (!is_null($r)) {
        // Store in the database:
        $q = sprintf('UPDATE sessions SET data="%s" WHERE id= "%s"', mysqli_real_escape_string($sdbc, $data), mysqli_real_escape_string($sdbc, $sid));
    } else {
        $q = sprintf('INSERT INTO sessions (id, data) VALUES ("%s", "%s")', mysqli_real_escape_string($sdbc, $sid), mysqli_real_escape_string($sdbc, $data));
    }

    // $q = sprintf('INSERT INTO sessions (id, data) VALUES ("%s", "%s")', mysqli_real_escape_string($sdbc, $sid), mysqli_real_escape_string($sdbc, $data)); 
    $r = doQuery($sdbc, $q);


    return true;
}

// End of write_session() function.
// Define the destroy_session() function:
// This function takes one argument: the session ID.
function destroy_session($sid) {
    $sdbc = getConnection();

    // Delete from the database:
    $q = sprintf('DELETE FROM sessions WHERE id="%s"', mysqli_real_escape_string($sdbc, $sid));
    $r = doQuery($sdbc, $q);


    // Clear the $_SESSION array:
    $_SESSION = array();

    return true;
}

// End of destroy_session() function.
// Define the clean_session() function:
// This function takes one argument: a value in seconds.
function clean_session($expire) {
    $sdbc = getConnection();

    // Delete old sessions:
    $q = sprintf('DELETE FROM sessions WHERE DATE_ADD(last_accessed, INTERVAL %d SECOND) < NOW()', (int) $expire);
    $r = doQuery($sdbc, $q);

    return true;
}

// End of clean_session() function.
# **************************** #
# ***** END OF FUNCTIONS ***** #
# **************************** #
// Declare the functions to use: This is the function that defines which userdefined procedures are actually calles .

session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'clean_session');

session_start();
// Make whatever other changes to the session settings, if you want.

// Start the session:




