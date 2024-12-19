<!-- admin_dash.php -->
<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch members
$stmt = $pdo->query("SELECT member_id, firstname, lastname, email FROM member");
$members = $stmt->fetchAll();

// Fetch loans
$stmt = $pdo->query("SELECT loan_id, member_id, amount FROM loan");
$loans = $stmt->fetchAll();

// Fetch loan applications
$stmt = $pdo->query("SELECT loan_id, member_id, amount FROM loan_applications");
$loan_applications = $stmt->fetchAll();

// Handle delete actions
if (isset($_GET['delete_member'])) {
    $member_id = $_GET['delete_member'];
    $stmt = $pdo->prepare("CALL delete_member(?)");
    $stmt->execute([$member_id]);
    header("Location: admin_dash.php");
    exit();
}

if (isset($_GET['delete_loan'])) {
    $loan_id = $_GET['delete_loan'];
    $stmt = $pdo->prepare("CALL DeleteLoan(?)");
    $stmt->execute([$loan_id]);
    header("Location: admin_dash.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
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

        .content {
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .btn-edit, .btn-delete {
            padding: 5px 10px;
            margin: 2px;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
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
        <!-- Sidebar Links -->
    </div>

    <div class="content">
        <h2>Members</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['firstname'] . ' ' . $member['lastname']) ?></td>
                        <td><?= htmlspecialchars($member['email']) ?></td>
                        <td>
                            <a href="view_member.php?member_id=<?= $member['member_id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="?delete_member=<?= $member['member_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Members Loan</h2>
        <table>
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan['member_id']) ?></td>
                        <td><?= htmlspecialchars($loan['amount']) ?></td>
                        <td>
                            <a href="edit_loan.php?loan_id=<?= $loan['loan_id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="?delete_loan=<?= $loan['loan_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this loan?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Members Loan Application</h2>
        <table>
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_applications as $loan_app): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan_app['member_id']) ?></td>
                        <td><?= htmlspecialchars($loan_app['amount']) ?></td>
                        <td>
                            <a href="approve_loan.php?loan_id=<?= $loan_app['loan_id'] ?>" class="btn btn-edit">Accept</a>
                            <a href="?delete_loan=<?= $loan_app['loan_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this loan application?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>