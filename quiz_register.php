<?php
// Include config file
require_once "config/connector.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = $trainee_id = $first_name = $last_name = $recruiter_name = $dev_domain = "";
$email_err = $password_err = $confirm_password_err = $trainee_id_err = $first_name_err = $last_name_err = $recruiter_name_err = $dev_domain_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Trainee ID
    if(empty(trim($_POST["trainee_id"]))) {
        $trainee_id_err = "Please enter a trainee id.";
    }
    else {
        $trainee_id = trim($_POST["trainee_id"]);
    }

    // Validate First Name
    if(empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter a first name.";
    }
    else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate Last Name
    if(empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter a last name.";
    }
    else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate Dev Domain
    if(empty(trim($_POST["dev_domain"]))) {
        $dev_domain_err = "Please enter a dev domain.";
    }
    else {
        $dev_domain = trim($_POST["dev_domain"]);
    }

    // Validate Recruiter Name
    if(empty(trim($_POST["recruiter_name"]))) {
        $recruiter_name_err = "Please enter a recruiter name.";
    }
    else {
        $recruiter_name = trim($_POST["recruiter_name"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($trainee_id_err) && empty($first_name_err) && empty($last_name_err) && empty($recruiter_name_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, trainee_id, first_name, last_name, dev_domain, recruiter_name) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $email, $param_password, $trainee_id, $first_name, $last_name, $dev_domain, $recruiter_name);
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                header("location: quiz_login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/base.css">
</head>
<body>
<div class="wrapper">
    <h2>Quiz Sign Up</h2>
    <p>Please fill registration form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($trainee_id_err)) ? 'has-error' : ''; ?>">
            <label>Trainee ID</label>
            <input type="text" name="trainee_id" class="form-control" value="<?php echo $trainee_id; ?>">
            <span class="help-block"><?php echo $trainee_id_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
            <span class="help-block"><?php echo $first_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
            <span class="help-block"><?php echo $last_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($recruiter_name_err)) ? 'has-error' : ''; ?>">
            <label>Recruiter Name</label>
            <input type="text" name="recruiter_name" class="form-control" value="<?php echo $recruiter_name; ?>">
            <span class="help-block"><?php echo $recruiter_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($dev_domain_err)) ? 'has-error' : ''; ?>">
            <label>DEV Domain</label>
            <select class="form-control" name="dev_domain" class="form-control">
                <option>PHP</option>
                <option>Java</option>
                <option>UI</option>
                <option>BigData</option>
                <option>Data Science</option>
                <option>DotNet</option>
            </select>
            <span class="help-block"><?php echo $dev_domain_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email (Username)</label>
            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>Already have an account? <a href="quiz_login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
