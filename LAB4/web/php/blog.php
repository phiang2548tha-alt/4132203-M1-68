<?php

header('Content-type:application/json');

include("condb.php");

$method = $_SERVER['REQUEST_METHOD'];
$response = ['status' => 'error', 'message' => 'Invalid request method'];

switch ($method) {
    case 'GET':
        // get all data
        $sql = "SELECT * FROM blog ORDER BY id DESC";
        $stmt = $condb->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $blog[] = $row;

        }

        $response = ['status' => 'success', 'data' => $blog];
        break;
    
    case 'POST':
        // insert data
        $comment = $_POST['blog'] ?? null;
        if ($comment) {
            $sql = "INSERT INTO blog (comment) VALUES (?)";
            $stmt = $condb->prepare($sql);
            $stmt->bind_param("s", $comment);
            if ($stmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Blog Inserted'];
            } else {
                $response = ['status' => 'error', 'message' => $condb->error];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'comment is null'];
        }
        break;
}

echo json_encode($response);
