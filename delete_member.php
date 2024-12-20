<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $member_id = $_POST['id'];

        $query = $pdo->prepare("CALL delete_member_user(?)");
        $query->execute([$member_id]);

        echo 'Deleted successfully';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
