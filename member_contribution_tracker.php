<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is an member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
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

// Calculate sinking fund balance
$total_contribution = $contribution['total_contribution'] ?? 0;
$total_loan = $loan['total_loan'] ?? 0;
$total_loan_payment = $loan_payed['total_loan_payment'] ?? 0;
$sinking_fund_balance = $total_contribution - $total_loan + $total_loan_payment;

// Calculate yearly goal (example: 20% more than current total contribution)
$yearly_goal = $total_contribution * 1.2;

// Calculate loan balance
$loan_balance = $total_loan - $total_loan_payment;

// Calculate monthly interest (5% of remaining loan balance)
$monthly_interest = $loan_balance * 0.05;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Contribution Tracker</title>
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

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card h2 {
            color: #12293f;
            margin-bottom: 15px;
        }

        .progress {
            height: 20px;
            margin-bottom: 10px;
        }

        .progress-bar {
            background-color: #12293f;
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
        <a class="brandname" href="member_dash.php">
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
        <h1 class="mb-4">Contribution Tracker</h1>

        <div class="card">
            <h2>Sinking Fund Balance</h2>
            <h3 id="sinking-fund-balance">₱<?php echo number_format($sinking_fund_balance, 2); ?></h3>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?php echo min(($sinking_fund_balance / $yearly_goal) * 100, 100); ?>%" aria-valuenow="<?php echo $sinking_fund_balance; ?>" aria-valuemin="0" aria-valuemax="<?php echo $yearly_goal; ?>"></div>
            </div>
            <p>Yearly Goal: ₱<?php echo number_format($yearly_goal, 2); ?></p>
        </div>

        <div class="card">
            <h2>Contributions</h2>
            <p>Total Contributions: ₱<span id="total-contributions"><?php echo number_format($total_contribution, 2); ?></span></p>
        </div>

        <div class="card">
            <h2>Loans<h2>Loans</h2>
            <p>Total Loans: ₱<span id="total-loans"><?php echo number_format($total_loan, 2); ?></span></p>
            <p>Loan Balance: ₱<span id="loan-balance"><?php echo number_format($loan_balance, 2); ?></span></p>
            <p>Total Loan Payments: ₱<span id="total-loan-payments"><?php echo number_format($total_loan_payment, 2); ?></span></p>
        </div>

        <div class="card">
            <h2>Monthly Interest</h2>
            <p>Current Monthly Interest (5%): ₱<span id="monthly-interest"><?php echo number_format($monthly_interest, 2); ?></span></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateSinkingFundBalance() {
            const totalContributions = parseFloat(document.getElementById('total-contributions').textContent.replace(',', ''));
            const totalLoans = parseFloat(document.getElementById('total-loans').textContent.replace(',', ''));
            const totalLoanPayments = parseFloat(document.getElementById('total-loan-payments').textContent.replace(',', ''));
            
            const sinkingFundBalance = totalContributions - totalLoans + totalLoanPayments;
            document.getElementById('sinking-fund-balance').textContent = '₱' + sinkingFundBalance.toFixed(2);
            
            const loanBalance = totalLoans - totalLoanPayments;
            document.getElementById('loan-balance').textContent = '₱' + loanBalance.toFixed(2);
            
            const monthlyInterest = loanBalance * 0.05;
            document.getElementById('monthly-interest').textContent = '₱' + monthlyInterest.toFixed(2);
            
            const yearlyGoal = <?php echo $yearly_goal; ?>;
            const progressBar = document.querySelector('.progress-bar');
            const progressPercentage = Math.min((sinkingFundBalance / yearlyGoal) * 100, 100);
            progressBar.style.width = progressPercentage + '%';
            progressBar.setAttribute('aria-valuenow', sinkingFundBalance);
        }

        updateSinkingFundBalance();
    </script>
</body>
</html>