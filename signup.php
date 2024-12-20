<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $b_date = $_POST['dob'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    try {
        $pdo->beginTransaction();

        $user_query = $pdo->prepare("INSERT INTO user (username, password, role) VALUES (:username, :password, :role)");
        $user_query->bindParam(':username', $username);
        $user_query->bindParam(':password', $password);
        $user_query->bindParam(':role', $role);
        $user_query->execute();
        $user_id = $pdo->lastInsertId();

        if ($role === 'admin') {
            $admin_query = $pdo->prepare("
                INSERT INTO admin (user_id, firstname, lastname, b_date, email, contact_number, address) 
                VALUES (:user_id, :firstname, :lastname, :b_date, :email, :contact_number, :address)
            ");
            $admin_query->bindParam(':user_id', $user_id);
            $admin_query->bindParam(':firstname', $firstname);
            $admin_query->bindParam(':lastname', $lastname);
            $admin_query->bindParam(':b_date', $b_date);
            $admin_query->bindParam(':email', $email);
            $admin_query->bindParam(':contact_number', $contact_number);
            $admin_query->bindParam(':address', $address);
            $admin_query->execute();
        } elseif ($role === 'member') {
            $member_query = $pdo->prepare("
                INSERT INTO member (user_id, firstname, lastname, b_date, email, contact_number, address) 
                VALUES (:user_id, :firstname, :lastname, :b_date, :email, :contact_number, :address)
            ");
            $member_query->bindParam(':user_id', $user_id);
            $member_query->bindParam(':firstname', $firstname);
            $member_query->bindParam(':lastname', $lastname);
            $member_query->bindParam(':b_date', $b_date);
            $member_query->bindParam(':email', $email);
            $member_query->bindParam(':contact_number', $contact_number);
            $member_query->bindParam(':address', $address);
            $member_query->execute();
        }
        
        $pdo->commit();

        echo "<p>Registration successful! <a href='login.php'>Login here</a>.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Sinking Fund Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #12293f;
        }

        nav {
            background-color:transparent;
            color: white;
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar {
            position: fixed;
            top: 0;
        }

        .brandname {
            font-size: 20px;
        }

        .signup-container {
            max-width: 100%;
            width: 500px;
            background-color: #e4effa;
            padding: 30px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .signup-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .signup-form input, .signup-form select, .signup-form textarea {
            background-color: #ffffff;
            border: 1px solid #ccc;
            color: #12293f;
            margin-bottom: 15px;
            border-radius: 5px;
            width: 100%;
            padding: 8px;
        }

        .signup-form {
            background: linear-gradient(to bottom,rgb(216, 235, 255), #ffffff, rgb(216, 235, 255));
            color: #12293f;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
        }

        .signup-form a {
            color: #12293f;
            text-decoration: none;
            font-size: 0.9em;

        }

        .signup-form a:hover {
            text-decoration: underline;
        }

        .signup-form .text-center {
            margin-top: 20px;
        }

        .signup-form .text-center a {
            font-weight: bold;
        }

        @media (max-width: 576px) {
            .signup-form {
                width: 90%;
                padding: 20px;
            }
        }
        .btn {
            background-color: #12293f;
            color: white;
        }
        .btn:hover {
            background-color: #e4effa;
            color: black;
            border: 2px solid #12293f;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand brandname" href="index.php">
                <img src="images/logo.png" alt="Logo" width="35" height="35"> Sinking Fund
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="signup-form">
                    <h2 class="text-center mb-4">Sign Up</h2>
                    <?php 
                    if (isset($error)) {
                        echo "<p class='text-danger'>$error</p>";
                    }
                    if (isset($success)) {
                        echo "<p class='text-success'>$success</p>";
                    }
                    ?>
                    <form action="signup.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" oninput="removeSpaces('username')">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" oninput="removeSpaces('password')">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="admin">Admin</option>
                                <option value="member">Member</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob " name="dob" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact #</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required oninput="removeSpaces"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function removeSpaces(inputId) {
            var inputField = document.getElementById(inputId);
            inputField.value = inputField.value.replace(/^\s+|\s+$/g, '');
        }
    </script>
</body>
</html>