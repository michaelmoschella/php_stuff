<?php
/********************************************** 
logout.php

Ends session with variable indicating who
was logged in. (logging them out)
***********************************************/
    session_start();
    if (session_destroy()) {
        $object = new stdClass();
        $object->status = 1; // 1 indicates success
        echo ("
            <h3><a href='../Phase2.html'>Back to main page</a></h3>
        "); //DELETE ME
    } else {
        $object = new stdClass();
        $object->status = 0; // 0 indicates success
        echo ("
            <h3><a href='../Phase2.html'>Back to main page</a></h3>
        "); //DELETE ME
    }
    $the_json = json_encode($object);
    echo($the_json);
    
    exit;
?>