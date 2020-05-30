<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: quiz_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/base.css">
</head>
<body>
<div class="page-header">
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["email"]); ?></b>. Welcome to the Quiz site.</h1>
        <a href="logout.php" class="btn btn-danger">Sign Out of Quiz Account</a>
</div>
<div id="quiz"></div>
<button id="submit" class="btn btn-primary">Submit Quiz</button>
<div id="results"></div>

<script src="js/js_quiz.js"></script>
</body>
</html>