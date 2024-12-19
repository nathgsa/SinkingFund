<?php
session_start();
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>controller</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e4effa;
            color: #12293f;
        }

        nav {
            background-color: #12293f;
            color: white;
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar{
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }


        .brandname {
            font-size: 20px;
            color: #ffffff;
        }

        header{
            text-align: center;
        }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand brandname" href="index.php">
                <img src="images/logo.png" alt="" width="35" height="35"> Sinking Fund
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

            </div>
        </div>
    </nav>

    <header><br><br><br><br><br>
        <a href="index.php">INDEX</a><br>
        <a href="signup.php">SIGN UP</a><br>
        <a href="login.php">LOG IN</a><br>
        <a href="admin_dash.php">ADMIN DASHBOARD</a><br>
        <a href="member_dash.php">MEMBER DASHBOARD</a><br>
        <a href="contribution_tracker.php">CONTRIBUTION TRACKER</a><br>
        <a href="history.php">HISTORY</a><br>
        <a href="profile.php">PROFILE</a><br>
        <a href="view_sf.php">VIEW_SF</a><br>
        <a href="notification.php">NOTIFICATION</a>
    </header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
