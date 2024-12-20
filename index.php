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
    <title>Sinking Fund</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #e4effa, #ffffff);
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
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        header {
            text-align: center;
            padding: 100px 20px 50px;
            background: linear-gradient(to bottom, #e4effa, #ffffff);
            color: #12293f;
            border-bottom: 10px solid #e4effa;
        }
        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        header p {
            font-style: italic;
            margin-bottom: 20px;
        }
        .brandname {
            font-size: 1.5rem;
        }
        .fst-italic {
            font-size: 1.25rem;
        }
        .btn-lg {
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
        }
        h1 {
            font-size: 2.5rem;
        }
        p {
            font-size: 1rem;
        }
        @media (min-width: 768px) {
            header h1 {
                font-size: 3rem;
            }
            header p {
                font-size: 1.25rem;
            }
            .btn-lg {
                font-size: 1.5rem;
            }
        }
        @media (min-width: 1200px) {
            header h1 {
                font-size: 4rem;
            }
            header p {
                font-size: 1.5rem;
            }
        }
        .container {
            max-width: 1200px;
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
                <ul class="navbar-nav ms-auto center">
                    <li class="nav-item">
                        <a href="login.php" class="button outline nav-link">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a href="signup.php" class="button nav-link">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="text-center py-5 container">
        <div class="row">
            <div class="col-12"><br><br><br><br>
                <h1>Welcome to Sinking Fund</h1><br><br>
                <p class="fst-italic">“A penny saved is a penny earned.” - Benjamin Franklin</p><br>
                <a href="login.php" class="btn btn-dark btn-lg">Get Started</a>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12"><br><br><br><br><br><br>
                <h2>About</h2><br>
                <p>The sinking fund is an effective financial management tool, <br>
                    allowing you to set aside money periodically to meet specific <br>
                    economic objectives and get a loan.</p> 
            </div>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
        <p>© 2024 Sinking Fund</p>
    </footer>
</body>
</html>
