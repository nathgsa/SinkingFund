<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch members
$stmt = $pdo->query("SELECT member_id, firstname, lastname FROM member");
$members = $stmt->fetchAll();

// Fetch contribution
$stmt = $pdo->query("SELECT contribution_id, member_id, amount, b_date FROM contribution");
$loans = $stmt->fetchAll();

// Fetch loans
$stmt = $pdo->query("SELECT loan_id, member_id, amount, b_date FROM loan");
$loans = $stmt->fetchAll();

// Fetch loan paid
$stmt = $pdo->query("SELECT loanp_id, loan_id, amount, b_date FROM loan_paid");
$loans = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Member Dashboard</title>
</head>
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
<body>
    <div class="container">
        <h2>Member Dashboard</h2>

        <h3>Contributions</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contribution as $contributions): ?>
                    <tr>
                        <td><?= htmlspecialchars($contribution['fullname']) ?></td>
                        <td><?= htmlspecialchars($contribution['amount']) ?></td>
                        <td><?= htmlspecialchars($contribution['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Loans</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan as $loans): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan['fullname']) ?></td>
                        <td><?= htmlspecialchars($loan['amount']) ?></td>
                        <td><?= htmlspecialchars($loan['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Loan Payments</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_payment as $loan_payments): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan_payment['fullname']) ?></td>
                        <td><?= htmlspecialchars($loan_payment['amount']) ?></td>
                        <td><?= htmlspecialchars($loan_payment['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
