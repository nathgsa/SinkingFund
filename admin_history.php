<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch member details
$stmt = $pdo->prepare("SELECT * FROM member WHERE user_id = ?");
$stmt->execute([$user_id]);
$member = $stmt->fetch();

// Fetch contribution total
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_contribution FROM contributions WHERE member_id = ?");
$stmt->execute([$member['member_id']]);
$contribution = $stmt->fetch();

// Fetch loan total
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_loan FROM loans WHERE member_id = ?");
$stmt->execute([$member['member_id']]);
$loan = $stmt->fetch();

// Fetch loan payments total
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_loan_payment FROM loan_payed WHERE member_id = ?");
$stmt->execute([$member['member_id']]);
$loan_payed = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #e4effa, #ffffff);
            color: #12293f;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: block;
            color: #12293f;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .container {
            margin-left: 270px;
            padding: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #12293f;
            background-color: white;
            color: #12293f;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .btn.button {
            background-color: #12293f;
            color: white;
        }

        .btn:hover {
            background-color: #e4effa;
            color: black;
            border: 2px solid #12293f;
        }

        .btn i {
            margin-right: 10px;
        }

        .profile_info, .contribution, .loan, .loan-payment {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        h3 {
            color: #12293f;
        }

        .brandname {
            font-size: 20px;
            color: #12293f;
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a class="navbar-brand brandname" href="admin_dash.php">
            <img src="images/logo.png" alt="" width="35" height="35"> Sinking Fund
        </a>
        <a href="admin_dash.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="admin_profile.php">
            <i class="fas fa-user"></i> Personal Info
        </a>
        <a href="admin_history.php">
            <i class="fas fa-history"></i> History
        </a>
        <a href="admin_contribution_tracker.php">
            <i class="fas fa-chart-line"></i> Contribution Tracker
        </a>
        <a href="logout.php" class="btn button" id="logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="container">
        <div class="profile_info">
            <h3>Profile Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($member['firstname'] . ' ' . $member['lastname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($member['email']); ?></p>
        </div>

        <div class="contribution">
            <h3>Contributions</h3>
            <p><strong>Total Contributions:</strong> ₱<?php echo number_format($contribution['total_contribution'] ?? 0, 2); ?></p>
        </div>

        <div class="loan">
            <h3>Loans</h3>
            <p><strong>Total Loans:</strong> ₱<?php echo number_format($loan['total_loan'] ?? 0, 2); ?></p>
        </div>

        <div class="loan-payment">
            <h3>Loan Payments</h3>
            <p><strong>Total Loan Payments:</strong> ₱<?php echo number_format($loan_payed['total_loan_payment'] ?? 0, 2); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>