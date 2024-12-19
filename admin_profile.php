<?php
require_once 'db_connection.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $query = $pdo->prepare("SELECT * FROM admin WHERE user_id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo "Admin not found!";
        exit();
    }
} catch (PDOException $e) {
    echo 'Query failed: ' . $e->getMessage();
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Sinking Fund Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        .profile-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info h2 {
            margin-bottom: 15px;
        }

        .profile-info p {
            margin: 5px 0;
        }

        .btn-container {
            text-align: center;
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
        <h1 class="mb-4">Admin Profile</h1>
        <div class="profile-container">
            <div class="profile-info">
                <h2>Personal Information</h2>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($admin['firstname']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($admin['lastname']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['contact_number']); ?></p>
                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($admin['address'])); ?></p>
            </div>
            <div class="btn-container">
                <a href="admin_update_info.php" class="btn" id="update-profile">Update Profile</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
