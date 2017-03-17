<?php  
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');
$board_database = new DrewSprint\BoardDatabase($database);
echo json_encode($board_database->requestAll());

