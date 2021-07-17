<?php

include_once 'db.php';

if(!$conn){
    die('Could not Connect MySql Server:' .mysql_error());
}

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json" && $_SERVER["REQUEST_METHOD"]=== 'POST') {
    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    $current_direction = $decoded['direction'];

    if ($current_direction == 'left') {
        $sql = "INSERT INTO `directions` (`to_left`) VALUES (1)";
    } elseif ($current_direction == 'right') {
        $sql = "INSERT INTO `directions` (`to_right`) VALUES (1)";
    } elseif ($current_direction == 'forward') {
        $sql = "INSERT INTO `directions` (`forward`) VALUES (1)";
    } elseif ($current_direction == 'backward') {
        $sql = "INSERT INTO `directions` (`backward`) VALUES (1)";
    } else {
        $sql = "INSERT INTO `directions` (`stop`) VALUES (1)";
    }

    if (mysqli_query($conn, $sql)) {
        $decoded['success'] = true;    // Add some data to be returned.
        // echo "New record has been added successfully !";
    } else {
        $decoded['success'] = false;
        $decoded['err_msg'] = mysqli_error($conn);
        // echo "Error: " . $sql . ":-" . mysqli_error($conn);
    }
    $reply = json_encode($decoded);
    mysqli_close($conn);
}

header("Content-Type: application/json; charset=UTF-8");
echo $reply;

?>