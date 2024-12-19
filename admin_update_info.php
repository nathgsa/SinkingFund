<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $query = $pdo->prepare("SELECT * FROM admin WHERE user_id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo "Admin not found!";
        exit;
    }
} else {
    echo "User ID is missing!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $b_date = $_POST['b_date'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    $query = $pdo->prepare("UPDATE admin SET firstname = :firstname, lastname = :lastname, b_date = :b_date, email = :email, contact_number = :contact_number, address = :address WHERE user_id = :user_id");
    $query->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $query->bindParam(':b_date', $b_date, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $query->execute();

    header('Location: admin_profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
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
        .brandname {
            font-size: 20px;
            color: #12293f;
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-box {
            max-width: 600px;
            background: linear-gradient(180deg,rgb(208, 231, 255),rgb(240, 246, 252), rgb(208, 231, 255));
            color: black;
            padding: 30px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-box h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-box input, .form-box textarea {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            color: #12293f;
            margin-bottom: 15px;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
        }
        .form-box input[type="date"] {
            padding: 9px;
        }
        .form-box button {
            background-color: #12293f;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-box button:hover {
            background-color: #e4effa;
            color: black;
            border: 2px solid #12293f;
        }
        @media (max-width: 576px) {
            .form-box {
                padding: 20px;
                margin: 30px auto;
            }
            .form-box h3 {
                font-size: 1.5em;
            }
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
        <div class="form-box">
            <h3>Update Profile</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($admin['firstname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($admin['lastname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="b_date">Birth Date:</label>
                    <input type="date" id="b_date" name="b_date" value="<?php echo htmlspecialchars($admin['b_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($admin['contact_number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required><?php echo htmlspecialchars($admin['address']); ?></textarea>
                </div>
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

</body>
</html>
