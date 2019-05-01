<?php
/********************************************** 
student-view-session.php

Displays a list of all sessions for sections that
the student has enrolled in and gives them the 
option to participate if they are eligible.
Also gives them the option to view study material
for each session.
***********************************************/
    session_start();
    
    $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');
    
    /* Get todays date and determine the date of the previous friday */
    $todays_date = new DateTime(date("Y-m-d"));
    $today = date("D");

    switch ($today) {
        case "Sat":
            $offset = "1 day";
            break;
        case "Sun":
            $offset = "2 days";
            break;
        case "Mon":
            $offset = "3 days";
            break;
        case "Tue":
            $offset = "4 days";
            break;
        case "Wed":
            $offset = "5 days";
            break;
        case "Thu":
            $offset = "6 days";
            break;
        default:
            $offset = "0 days";
    }
    $fri_date = date_sub($todays_date, date_interval_create_from_date_string($offset));

    $active_id = $_POST['active_ID'];
    /*$html_string = "
    <head>
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 30px;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            tr:nth-child(even) {
               background-color: #dddddd;
            }
        </style>
    </head>
    <h1> Participate in sessions as a mentor </h1>";*/

    $object = new stdClass();
    $object->mentor_sections=array();
    $object->mentee_sections=array();


    /* Get list of sections student is mentoring */
    $get_sec_info_query = "SELECT Section.name, Course.title, Section.secID, Section.cID FROM Section, Course, Teaches 
    WHERE {$active_id} = Teaches.orID 
    AND Section.secID = Teaches.secID AND Section.cID = Teaches.cID
    AND Course.cID = Teaches.cID;"; 
    $result1 = mysqli_query($myconnection, $get_sec_info_query) or die ('Query failed: ' . mysqli_error($myconnection));
    while ($row = mysqli_fetch_row($result1)) {
       /* $html_string .= "<label>
        <table style='width:25%' style='height:15%'>
        <tr>
            <td colspan = '8' style = 'text-align: center'><h4>{$row[1]} {$row[0]}</h4></td>
        </tr>
        <tr>
            <th>Session Name</th>
            <th>Announcement</th>
            <th>Date</th>
            <th>Time</th>
            <th>Mentor Count</th>
            <th>Mentee Count</th>
            <th>View Study Material</th>
            <th>Participate</th>
        </tr>";*/                    
        $section_obj = new stdClass();
        $section_obj->Course_Title = $row[1];
        $section_obj->Section_Name = $row[0];
        $section_obj->Sessions = array();

        /* Get list of sessions in those sections */
        $get_sessions_query ="SELECT Session.name, Session.theDate, Schedule.startTime, Schedule.endTime,
                Session.sesID, Session.secID, Session.cID, Session.announcement
            FROM Session, Section, Schedule
            WHERE 
                Session.secID = Section.secID AND Session.cID = Section.cID
                And Session.secID = {$row[2]} AND Session.cID = {$row[3]}
                AND Section.schedID = Schedule.schedID;";
        $result2 = mysqli_query($myconnection, $get_sessions_query) or die ('Query failed: ' . mysqli_error($myconnection));

        
        if (mysqli_num_rows($result2)) {
            while($a_row = mysqli_fetch_row($result2)){
                /* Get number of mentors participating in the session */
                $get_mentor_count_query = "SELECT count(*) FROM SessTeach 
                    WHERE SessTeach.sesID = {$a_row[4]} AND SessTeach.secID = {$a_row[5]} AND SessTeach.cID = {$a_row[6]};";
                $result3 = mysqli_query($myconnection, $get_mentor_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row1 = mysqli_fetch_row($result3);
                mysqli_free_result($result3);
                
                /* Get number of mentees participating in session */
                $get_mentee_count_query = "SELECT COUNT(*) FROM SessLearn 
                    WHERE SessLearn.sesID = {$a_row[4]} AND SessLearn.secID = {$a_row[5]} AND SessLearn.cID = {$a_row[6]};";
                $result4 = mysqli_query($myconnection, $get_mentee_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row2 = mysqli_fetch_row($result4);

                /* Determine if mentor is already participating in session*/
                $is_mentoring_query = "SELECT COUNT(*) FROM SessTeach 
                    WHERE SessTeach.sesID = {$a_row[4]} AND SessTeach.secID = {$a_row[5]} AND SessTeach.cID = {$a_row[6]}
                            AND SessTeach.orID = $active_id;";
                $result5 = mysqli_query($myconnection, $is_mentoring_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row3 = mysqli_fetch_row($result5);

                mysqli_free_result($result4);
                $session_obj = new stdClass();
                $session_obj->Session_Name = $a_row[0];
                $session_obj->Announcement = $a_row[7];
                $session_obj->Date = $a_row[1];
                $session_obj->Start_Time = $a_row[2];
                $session_obj->End_Time = $a_row[3];
                $session_obj->Mentor_Count = $row1[0];
                $session_obj->Mentee_Count = $row2[0];
                $session_obj->cID = $a_row[6];
                $session_obj->secID = $a_row[5];
                $session_obj->sesID = $a_row[4];


               /* $html_string .="
                    <tr>
                        <td>{$a_row[0]}</td>
                        <td>{$a_row[7]}</td>
                        <td>{$a_row[1]}</td>
                        <td>{$a_row[2]}-{$a_row[3]}</td>
                        <td>{$row1[0]}</td>
                        <td>{$row2[0]}</td>
                        <td><a href='view-study-material.php?sesID=".$a_row[4]."&&secID=".$a_row[5]."&&cID=".$a_row[6].
                        "&&cTitle=".$row[1]."&&secName=".$row[0]."&&sesName=".$a_row[0]."'>View Study Materials</a></td>";*/
                        $sess_date = new DateTime($a_row[1]);
                        if ($sess_date < $todays_date) {
                            $session_obj->status = -2;
                           // $html_string .= "<td>Session ended</td>";
                        } else {
                            if (!$row3[0]) {
                                $session_obj->status = 1;
                              /*  $html_string .= "<td><a href='enroll-mentor-session.php?cID=".$a_row[6]."&&secID=".$a_row[5].
                                "&&sesID=".$a_row[4]."'>Participate</td>";*/
                            } else {
                                $session_obj->status = -1;
                              //  $html_string .= "<td>Currently Participating</td>";
                            }
                        }
                    //$html_string .= "</tr>";
                array_push($section_obj->Sessions, $session_obj);
            }
        }
        array_push($object->mentor_sections, $section_obj);
        mysqli_free_result($result2);
       /* $html_string .= "
            </table>
        <label>";*/
    }
    mysqli_free_result($result1);

   // $html_string .= "<h1>Participate in sessions as a mentee</h1>"; 
    $object->mentee_sections=array();

    /* Get List of sections student is a mentee in */
    $get_sec_info_query = "SELECT Section.name, Course.title, Section.secID, Section.cID FROM Section, Course, Learns 
    WHERE {$active_id} = Learns.eeID 
    AND Section.secID = Learns.secID AND Section.cID = Learns.cID
    AND Course.cID = Learns.cID;"; 
    $result1 = mysqli_query($myconnection, $get_sec_info_query) or die ('Query failed: ' . mysqli_error($myconnection));
    while ($row = mysqli_fetch_row($result1)) {

        $section_obj = new stdClass();
        $section_obj->Course_Title = $row[1];
        $section_obj->Section_Name = $row[0];
        $section_obj->Sessions = array();

        /*$html_string .=     
            "<label>
                <table style='width:25%' style='height:15%'>
                <tr>
                    <td colspan = '7' style = 'text-align: center;'><h4>{$row[1]} {$row[0]}</h4></td>
                </tr>
                <tr>
                    <th>Session Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Mentor Count</th>
                    <th>Mentee Count</th>
                    <th>View Study Material</th>
                    <th>Participate</th>
                </tr>";*/ 


        /* Get Sessions of those sections */
        $get_sessions_query ="SELECT Session.name, Session.theDate, Schedule.startTime, Schedule.endTime,
                Session.sesID, Session.secID, Session.cID, Session.announcement
            FROM Session, Section, Schedule
            WHERE 
            Session.secID = Section.secID AND Session.cID = Section.cID
            And Session.secID = {$row[2]} AND Session.cID = {$row[3]}
            AND Section.schedID = Schedule.schedID;";
        $result2 = mysqli_query($myconnection, $get_sessions_query) or die ('Query failed: ' . mysqli_error($myconnection));
        
        if (mysqli_num_rows($result2)){
            while($a_row = mysqli_fetch_row($result2)){
                /* Get number of mentors in those sessions */
                $get_mentor_count_query = "SELECT count(*) FROM SessTeach 
                    WHERE SessTeach.sesID = {$a_row[4]} AND SessTeach.secID = {$a_row[5]} AND SessTeach.cID = {$a_row[6]};";
                $result3 = mysqli_query($myconnection, $get_mentor_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row1 = mysqli_fetch_row($result3);
                mysqli_free_result($result3);
                
                /* Get number of mentees in those sessions */
                $get_mentee_count_query = "SELECT COUNT(*) FROM SessLearn 
                    WHERE SessLearn.sesID = {$a_row[4]} AND SessLearn.secID = {$a_row[5]} AND SessLearn.cID = {$a_row[6]};";
                $result4 = mysqli_query($myconnection, $get_mentee_count_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row2 = mysqli_fetch_row($result4);
                mysqli_free_result($result4);
                
                /* Determine if student is already participating in session*/
                $is_menteeing_query = "SELECT COUNT(*) FROM SessLearn 
                    WHERE SessLearn.sesID = {$a_row[4]} AND SessLearn.secID = {$a_row[5]} AND SessLearn.cID = {$a_row[6]}
                            AND SessLearn.eeID = $active_id;";
                $result5 = mysqli_query($myconnection, $is_menteeing_query) or die ('Query failed: ' . mysqli_error($myconnection));
                $row3 = mysqli_fetch_row($result5);
                mysqli_free_result($result5);
                $session_obj = new stdClass();
                $session_obj->Session_Name = $a_row[0];
                $session_obj->Announcement = $a_row[7];
                $session_obj->Date = $a_row[1];
                $session_obj->Start_Time = $a_row[2];
                $session_obj->End_Time = $a_row[3];
                $session_obj->Mentor_Count = $row1[0];
                $session_obj->Mentee_Count = $row2[0];
                $session_obj->cID = $a_row[6];
                $session_obj->secID = $a_row[5];
                $session_obj->sesID = $a_row[4];

                /*$html_string .="
                    <tr>
                        <td>{$a_row[0]}</td>
                        <td>{$a_row[1]}</td>
                        <td>{$a_row[2]}-{$a_row[3]}</td>
                        <td>{$row1[0]}</td>
                        <td>{$row2[0]}</td>
                        <td><a href='view-study-material.php?sesID=".$a_row[4]."&&secID=".$a_row[5]."&&cID=".$a_row[6].
                        "&&cTitle=".$row[1]."&&secName=".$row[0]."&&sesName=".$a_row[0]."'>View Study Materials</a></td>";
*/
                        $sess_date = new DateTime($a_row[1]);
                        if ($sess_date < $todays_date) {
                            $session_obj->status = -2;
                           // $html_string .= "<td>Session ended</td>";
                        } else {
                            if (!$row3[0]) {
                                /*mentees can not enroll after Thursday for sessions in the upcoming week*/
                                if (date_diff($sess_date, $fri_date)->format("%d") < 9){ # assuming weeks start on mon end on Sun
                                    $session_obj->status = -3;
                                    //$html_string .= "<td>Missed Thursday Deadline</td>";
                                } else {
                                    $session_obj->status = 1;
                                    //$html_string .= "<td><a href='enroll-mentee-session.php?cID=".$a_row[6]."&&secID=".$a_row[5]."&&sesID=".$a_row[4]."'>Participate</td>";
                                }
                            } else {
                            $session_obj->status = -1;
                                //$html_string .= "<td>Currently Participating</td>";
                            }
                        }
                   // $html_string .= "</tr>";
                array_push($section_obj->Sessions, $session_obj);
            } 
        }
        array_push($object->mentee_sections, $section_obj);
        mysqli_free_result($result2);
    /*    $html_string .= "
            </table>
        <label>";*/
    }
    mysqli_free_result($result1);

    $the_json = json_encode($object);
    echo($the_json);

   /* echo($html_string);
    echo('<h3><a href="student-dashboard.php">Back to your dashboard</a></h3>');
    echo('<h3><a href="logout.php">Logout</a></h3>');*/

    mysqli_close($myconnection);
    exit;
?>

