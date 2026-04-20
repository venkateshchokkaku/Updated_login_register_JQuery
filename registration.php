<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        background: #f0f2f5;
    }
    .vh-fit {
        min-height: 100vh;
    }
    .card {
        background: linear-gradient(135deg, #ffffff, #e9ecef);
    }
</style>
</head>
<body>
<div class="d-flex justify-content-center align-items-center vh-fit">
    <div class="card shadow p-4" style="width: 100%; max-width: 450px;">
        <h2 class="text-center mb-4">Register</h2>

        <?php
    if(isset($_POST["submit"])){
    require_once "database.php";

    $fullName = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $errors = array();

    // First, check if any mandatory field is empty
    if(empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($phone)){
        $errors[] = "All mandatory fields are required";
    } else {
        // Only validate further if all fields are filled
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Email is not valid";
        }
        if(strlen($password) < 8){
            $errors[] = "Password must be at least 8 characters long";
        }
        if($password != $passwordRepeat){
            $errors[] = "Passwords do not match";
        }

        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $errors[] = "Email already exists!";
        }
    }

    // Display errors
    if(count($errors) > 0){
        foreach($errors as $error){
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Insert user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $unique_id = uniqid("user_");

        $sql = "INSERT INTO users (unique_id, full_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql)){
            mysqli_stmt_bind_param($stmt,"sssss",$unique_id,$fullName,$email,$phone,$passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You registered successfully. <a href='login.php'>Login here</a></div>";
        } else {
            die("Something went wrong");
        }
    }
}

        ?>

        <form action="registration.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name">
                <label for="fullname">Full Name <span class="text-danger">*</span></label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                <label for="email">Email <span class="text-danger">*</span></label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                <label for="phone">Phone <span class="text-danger">*</span></label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password <span class="text-danger">*</span></label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Repeat Password">
                <label for="repeat_password">Confirm Password <span class="text-danger">*</span></label>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="submit">Register</button>
        </form>
        <p class="mt-3 text-center">Already registered? <a href="login.php">Login Here</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
