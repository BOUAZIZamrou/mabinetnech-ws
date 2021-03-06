<?php
// include configurations
include "config.inc.php";

/* Get data */
if (isset($_GET['token']))
    $token = $_GET['token'];
else {
    $json = array("status" => 0, "msg" => "Error, token required!");
    goto output;
}
if (isset($_GET['post']))
    $post = $_GET['post'];
else {
    $json = array("status" => 0, "msg" => "Error, post required!");
    goto output;
}


/* Identifying user */
$sql = "SELECT password from users where password='$token'";
$res = $mysqli->query($sql);
if ($res) {

    if ($res->num_rows == 0)// check that the token is valid
    {
        $json = array("status" => 0, "msg" => "token wrong");
        goto output;
    }

} else // problem with database
{
    $json = array("status" => 0, "msg" => "Error checking token!", "details" => $mysqli->error);
    goto output;
}

$sql = "SELECT * FROM comments where post_id=$post";

$res = $mysqli->query($sql);

if ($res) {
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $json = array("status" => 1, "msg" => "comments found !", "comments" => $rows);
    } else {
        $json = array("status" => 1, "msg" => "no comments to be found!", "comments" => array());
    }
} else {
    $json = array("status" => 0, "msg" => "Error getting comments!", "details" => ($mysqli->error));
}


/* Output header */
output:
header('Content-type: application/json');
echo json_encode($json);
