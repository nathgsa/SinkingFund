<?php
session_start();
include 'db_connection.php';  // Ensure this file sets up the $pdo connection properly

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } else {
        try {
            // Prepare the query using PDO
            $query = $pdo->prepare("SELECT * FROM user WHERE username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();

            // Check if a user was found
            if ($query->rowCount() > 0) {
                $user = $query->fetch(PDO::FETCH_ASSOC);

                if ($password === $user["password"]) {          
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] == 'admin') {
                        header("Location: admin_dash.php");
                        exit();
                    } else {
                        header("Location: member_dash.php");
                        exit(); 
                    }
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sinking Fund - Log In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #12293f;
        }
        nav {
            background-color: transparent;
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
        .login-container {
            max-width: 330px;
            background-color: #e4effa;
            padding: 30px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container input {
            background-color: #ffffff;
            border: 1px solid #ccc;
            color: #12293f;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .login-container .btn-login {
            background-color: #12293f;
            color: white;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
        .login-container a {
            color: #12293f;
            text-decoration: none;
            font-size: 0.9em;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .login-container .text-center {
            margin-top: 20px;
        }
        .login-container .text-center a {
            font-weight: bold;
        }
        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
                margin: 30px auto;
            }
            .login-container h2 {
                font-size: 1.5em;
            }
            .login-container input {
                font-size: 0.9em;
                padding: 10px;
            }
            .login-container .btn-login {
                font-size: 0.9em;
                padding: 8px;
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

        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand brandname" href="index.php">
                    <img src="images/logo.png" alt="" width="35" height="35"> Sinking Fund
                </a>
            </div>
        </nav>

    <div class="login-container">
        <h2>Log In</h2>
        <div class="login-form">
            <?php
            if (isset($error)) {
                echo "<p class='text-danger'>$error</p>";
            }
            ?>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
