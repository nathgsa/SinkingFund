<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is a member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch member details
$stmt = $pdo->prepare("SELECT * FROM member WHERE user_id = ?");
$stmt->execute([$user_id]);
$member = $stmt->fetch();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['contribution'])) {
        $amount = $_POST['amount'];
        $gcash_number = $_POST['gcash_number'];
        
        $stmt = $pdo->prepare("CALL SaveContribution(?, ?, ?, ?, ?, ?)");
        $stmt->execute([$member['member_id'], $member['firstname'], $member['lastname'], date('Y-m-d'), $gcash_number, $amount]);
    } elseif (isset($_POST['loan_application'])) {
        $amount = $_POST['amount'];
        $gcash_number = $_POST['gcash_number'];
        
        $stmt = $pdo->prepare("INSERT INTO loan_applications (member_id, amount, gcash_number, date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$member['member_id'], $amount, $gcash_number, date('Y-m-d')]);
    } elseif (isset($_POST['loan_payment'])) {
        $amount = $_POST['amount'];
        $gcash_number = $_POST['gcash_number'];
        
        $stmt = $pdo->prepare("INSERT INTO loan_payed (member_id, amount, gcash_number, date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$member['member_id'], $amount, $gcash_number, date('Y-m-d')]);
    }
    
    header("Location: member_dash.php");
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
    <title>Member Dashboard</title>
    <style>
        /* Your styles here */
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

        .profile_info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        #inner-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .btn-contribution, .btn-loan-application, .btn-loan-payment {
            margin-right: 10px;
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
        <a class="navbar-brand brandname" href="member_dash.php">
            <img src="images/logo.png" alt="" width="35" height="35"> Sinking Fund
        </a>
        <a href="member_dash.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="member_profile.php">
            <i class="fas fa-user"></i> Personal Info
        </a>
        <a href="member_history.php">
            <i class="fas fa-history"></i> History
        </a>
        <a href="member_contribution_tracker.php">
            <i class="fas fa-chart-line"></i> Contribution Tracker
        </a>
        <a href="logout.php" class="btn button" id="logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="container">
        <div class="profile_info text-center">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($member['firstname'] . ' ' . $member['lastname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($member['email']); ?></p>
        </div>

        <div id="inner-content">
            <div class="d-flex justify-content-around mb-4">
                <button class="btn btn-contribution" id="btn-contribution">
                    <i class="fas fa-hand-holding-usd"></i> Contribution
                </button>
                <button class="btn btn-loan-application" id="btn-loan-application">
                    <i class="fas fa-money-bill"></i> Loan Application
                </button>
                <button class="btn btn-loan-payment" id="btn-loan-payment">
                    <i class="fas fa-money-check-alt"></i> Loan Payment
                </button>
            </div>

            <div id="dynamic-content"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const dynamicContent = document.getElementById('dynamic-content');

        document.getElementById('btn-contribution').addEventListener('click', () => {
            dynamicContent.innerHTML = `
                <h3>Contribution</h3>
                <form action="member_dash.php" method="post">
                    <input type="hidden" name="contribution" value="1">
                    <div class="mb-3">
                        <label for="contribution-gcash" class="form-label">G-Cash Number</label>
                        <input type="text" class="form-control" id="contribution-gcash" name="gcash_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="contribution-amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="contribution-amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn button">Pay</button>
                </form>
            `;
        });

        document.getElementById('btn-loan-application').addEventListener('click', () => {
            dynamicContent.innerHTML = `
                <h3>Loan Application</h3>
                <form action="member_dash.php" method="post">
                    <input type="hidden" name="loan_application" value="1">
                    <div class="mb-3">
                        <label for="loan-application-gcash" class="form-label">G-Cash Number</label>
                        <input type="text" class="form-control" id="loan-application-gcash" name="gcash_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="loan-application-amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="loan-application-amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn button">Apply</button>
                </form>
            `;
        });

        document.getElementById('btn-loan-payment').addEventListener('click', () => {
            dynamicContent.innerHTML = `
                <h3>Loan Payment</h3>
                <form action="member_dash.php" method="post">
                    <input type="hidden" name="loan_payment" value="1">
                    <div class="mb-3">
                        <label for="loan-payment-gcash" class="form-label">G-Cash Number</label>
                        <input type="text" class="form-control" id="loan-payment-gcash" name="gcash_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="loan-payment-amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="loan-payment-amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn button">Pay</button>
                </form>
            `;
        });
    </script>
</body>
</html>
