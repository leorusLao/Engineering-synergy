<?php
require_once '_db.php';

db_update_task($_POST["id"], $_POST["start"], $_POST["end"]);

class Result {}

$response = new Result();
$response->result = 'OK';

header('Content-Type: application/json');
echo json_encode($response);

?>
