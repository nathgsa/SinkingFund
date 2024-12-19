<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $member_id = $_POST['id'];

  $query = $conn->prepare("DELETE FROM member WHERE user_id = ?");
  $query->bind_param("i", $member_id);
  $query->execute();

  $query_user = $conn->prepare("DELETE FROM user WHERE user_id = ?");
  $query_user->bind_param("i", $member_id);
  $query_user->execute();

  echo 'Member deleted successfully';
}
?>
