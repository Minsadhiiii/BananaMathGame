<?php
session_start();
require 'db_connection.php';

$user_id = $_SESSION['user_id'];

$query = "UPDATE users SET score = score + 10, levels_played = levels_played + 1 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo json_encode(["score" => $_SESSION['score'] + 10, "levels" => $_SESSION['levels_played'] + 1]);
?>