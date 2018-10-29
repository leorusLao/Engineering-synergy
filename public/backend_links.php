<?php
require_once '_db.php';

$stmt = $db->prepare("SELECT * FROM task_link");
$stmt->execute();
$items = $stmt->fetchAll();
file_put_contents('qqqq','11111');
class Link {}

$result = array();

foreach($items as $item) {
    $r = new Link();
    $r->id = $item['id'];
    $r->from = $item['from_id'];
    $r->to = $item['to_id'];
    $r->type = $item['type'];
  
    $result[] = $r;
}

file_put_contents('qqqq',print_r($result,true));

header('Content-Type: application/json');
echo json_encode($result);

?>
