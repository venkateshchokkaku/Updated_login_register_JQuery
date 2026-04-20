<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: masters.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background: #f0f2f5; }

    .wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card-box {
        width: 100%;
        max-width: 450px;
        background: white;
        border-radius: 10px;
        padding: 25px;
    }

    /* Password Input Fix */
    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 45px !important; /* Space for eye icon */
    }

    .password-wrapper #togglePassword {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
    }
</style>
</head>

<body>

<div class="wrapper">
    <div class="card-box shadow">

        <h3 class="text-center mb-3">Login</h3>

        <?php
        if(isset($_POST["submit"])){
            require_once "database.php";

            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            if(empty($email) || empty($password)){
                echo "<div class='alert alert-danger'>All fields are required.</div>";
            } else {
                $sql = "SELECT * FROM users WHERE email='$email'";
                $result = mysqli_query($conn,$sql);

                if(mysqli_num_rows($result) === 1){
                    $user = mysqli_fetch_assoc($result);

                    if(password_verify($password,$user["password"])){
                        $_SESSION["user"] = $user["unique_id"];
                        $_SESSION["username"] = $user["full_name"];
                        header("Location: masters.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Invalid Password</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email does not exist</div>";
                }
            }
        }
        ?>

        <form id="loginForm" action="login.php" method="post">

            <div class="form-floating mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                <label for="email">Email <span class="text-danger">*</span></label>
            </div>

            <!-- Updated Password Field -->
            <div class="form-floating mb-3 password-wrapper">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password <span class="text-danger">*</span></label>

                <span id="togglePassword">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="submit">Login</button>
        </form>

        <p class="mt-3 text-center">
            Not Registered? <a href="registration.php">Register Here</a>
        </p>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function(){

    // Block Enter if fields empty
    $("#loginForm input").on("keypress", function(e){
        if(e.which === 13){
            if($("#email").val() === "" || $("#password").val() === ""){
                e.preventDefault();
            }
        }
    });

    // Show / Hide password
    $("#togglePassword").click(function(){
        let field = $("#password");
        let type = field.attr("type") === "password" ? "text" : "password";
        field.attr("type", type);
        $("#eyeIcon").toggleClass("bi-eye bi-eye-slash");
    });

});
</script>

</body>
</html>
