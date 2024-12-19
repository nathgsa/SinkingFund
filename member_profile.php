<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch admin details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'member'");
$stmt->execute([$user_id]);
$member = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $bdate = $_POST['bdate'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];


    // Start transaction
    $pdo->beginTransaction();

    try {
        // Update admin information
        $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, `b-date` = ?, address = ?, contact = ? WHERE user_id = ?");
        $stmt->execute([$firstname, $lastname, $email, $bdate, $address, $contact, $user_id]);


        // Commit transaction
        $pdo->commit();

        $success = "Profile updated successfully";
            
        // Refresh admin details
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'member'");
        $stmt->execute([$user_id]);
        $member = $stmt->fetch();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $error = "An error occurred. Please try again.";
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
    <title>Member Profile - Sinking Fund Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }

        .sidebar {
            background: linear-gradient(180deg, #e4effa, #ffffff);
            color: #12293f;
            padding: 20px;
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

        .profile-info form {
            margin-top: 20px;
        }

        .profile-info input {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .profile-info .btn-update {
            background-color: #12293f;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-info .btn-update:hover {
            background-color: #e4effa;
            color: black;
            border: 2px solid #12293f;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100%;
                transition: 0.3s;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .container {
                margin-left: 0;
                padding: 0 20px;
            }

            .btn-sidebar-toggle {
                display: block;
                margin-bottom: 15px;
            }
        }

        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                height: 100vh;
            }

            .container {
                margin-left: 270px;
                padding: 20px;
            }
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

    <!-- Main Content -->
    <div class="container">
        <div class="profile-info">
            <h2>Personal Information</h2>
            <?php if (isset($error)): ?>
                <p class="text-danger"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="text-success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form action="member_profile.php" method="post">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($member['firstname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($member['lastname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="bdate" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="bdate" name="bdate" value="<?php echo htmlspecialchars($member['b-date']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($member['address']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($member['contact']); ?>" required>
                </div>
                <button type="submit" class="btn btn-update">Update Profile</button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>