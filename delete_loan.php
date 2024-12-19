<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $loan_id = $_POST['id'];

  $query = $conn->prepare("DELETE FROM loan WHERE user_id = ?");
  $query->bind_param("i", $loan_id);
  $query->execute();

  echo 'Loan application deleted successfully';
}
?>
