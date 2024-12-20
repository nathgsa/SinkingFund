<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $member_id = $_GET['delete'];

    if (empty($member_id)) {
        die("No member ID provided for deletion.");
    }

    try {
        $pdo->beginTransaction();

        $query = $pdo->prepare("CALL delete_member_user(?)");
        $query->execute([$member_id]);

        $pdo->commit();
        header('Location: admin_dash.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error deleting member and user: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT member_id, firstname, lastname, email FROM member");
$members = $stmt->fetchAll();

$stmt = $pdo->query("SELECT loan_id, member_id, amount FROM loan");
$loans = $stmt->fetchAll();

$stmt = $pdo->query("SELECT loanAppli_id, member_id, amount FROM loan_application");
$loan_applications = $stmt->fetchAll();

if (isset($_GET['action']) && isset($_GET['loan_id'])) {
    $loan_id = $_GET['loan_id'];
    $action = $_GET['action'];

    if ($action == 'accept') {
        $stmt = $pdo->prepare("SELECT * FROM loan_application WHERE loanAppli_id = ?");
        $stmt->execute([$loan_id]);
        $loan_application = $stmt->fetch();

        if ($loan_application) {
            $insert_stmt = $pdo->prepare("INSERT INTO loan (member_id, amount) VALUES (?, ?)");
            $insert_stmt->execute([$loan_application['member_id'], $loan_application['amount']]);

            $delete_stmt = $pdo->prepare("DELETE FROM loan_application WHERE loanAppli_id = ?");
            $delete_stmt->execute([$loan_id]);

            header("Location: admin_dash.php");
            exit();
        }
    } elseif ($action == 'decline') {
        $delete_stmt = $pdo->prepare("DELETE FROM loan_application WHERE loanAppli_id = ?");
        $delete_stmt->execute([$loan_id]);

        header("Location: admin_dash.php");
        exit();
    }
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
                            <a href="view_member.php?member_id=<?= $member['member_id'] ?>" class="btn btn-edit">View</a>
                            <a href="?delete=<?php echo $row['user_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
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
                            <a href="view_loan.php?loan_id=<?= $loan['loan_id'] ?>" class="btn btn-edit">View</a>
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
                            <a href="?action=accept&loan_id=<?= $loan_app['loanAppli_id'] ?>" class="btn btn-edit">Accept</a>
                            <a href="?action=decline&loan_id=<?= $loan_app['loanAppli_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to decline this loan application?');">Decline</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>