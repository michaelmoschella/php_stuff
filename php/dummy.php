<?php
/********************************************** 
register-parent.php

Takes info registed by parents and inserts into
the User table Parent table, as well as
the Moderator table if needed
***********************************************/
  $stuff = $_POST['the_stuff'];


  $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
  $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

  # determine next available id
  $max_id_query = "SELECT MAX(uID) FROM User";
  $result1 = mysqli_query($myconnection, $max_id_query) or die ('Query failed: ' . mysqli_error($myconnection));
  $row = mysqli_fetch_row($result1);
  if ($row){
    $p_id = $row[0] + 1;
  } else {
    $p_id = 1;
  }
  mysqli_free_result($result1);


  $object = new stdClass();
  $object->status = 1; // 1 indicates success
  $object->stuff = $stuff;
  $object->id = $p_id;

  $the_json = json_encode($object);

  echo($the_json);
  echo("<h3><a href='../Phase2.html'>Back to main page</a></h3>");// DELETE ME

  mysqli_close($myconnection);
  exit;
?>

