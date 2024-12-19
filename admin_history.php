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

// Fetch contributions from the view
$stmt = $pdo->prepare("SELECT CONCAT(UCASE(c.lastname), ', ', UCASE(c.firstname)) AS fullname, c.amount, c.date 
                       FROM vw_hcontr c WHERE c.member_id = ?");
$stmt->execute([$member['member_id']]);
$contributions = $stmt->fetchAll();

// Fetch loans from the view
$stmt = $pdo->prepare("SELECT CONCAT(UCASE(l.lastname), ', ', UCASE(l.firstname)) AS fullname, l.amount, l.date
                       FROM vw_hloan l WHERE l.member_id = ?");
$stmt->execute([$member['member_id']]);
$loans = $stmt->fetchAll();

// Fetch loan payments from the view
$stmt = $pdo->prepare("SELECT CONCAT(UCASE(lp.lastname), ', ', UCASE(lp.firstname)) AS fullname, lp.amount, lp.date
                       FROM vw_hloanp lp WHERE lp.member_id = ?");
$stmt->execute([$member['member_id']]);
$loan_payments = $stmt->fetchAll();
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
        <a href="login.php" class="btn button" id="logout">
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
            <?php foreach ($contributions as $contribution): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($contribution['fullname']); ?></p>
                <p><strong>Amount:</strong> ₱<?php echo number_format($contribution['amount'], 2); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($contribution['date']); ?></p>
                <hr>
            <?php endforeach; ?>
        </div>

        <div class="loan">
            <h3>Loans</h3>
            <?php foreach ($loans as $loan): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($loan['fullname']); ?></p>
                <p><strong>Amount:</strong> ₱<?php echo number_format($loan['amount'], 2); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($loan['date']); ?></p>
                <hr>
            <?php endforeach; ?>
        </div>

        <div class="loan-payment">
            <h3>Loan Payments</h3>
            <?php foreach ($loan_payments as $payment): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($payment['fullname']); ?></p>
                <p><strong>Amount:</strong> ₱<?php echo number_format($payment['amount'], 2); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($payment['date']); ?></p>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
