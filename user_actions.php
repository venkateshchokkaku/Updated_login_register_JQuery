<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit();
}

require_once "database.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'];

    if($action === 'add'){
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];

        // Check for existing email/phone
        $stmtEmail = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
        mysqli_stmt_bind_param($stmtEmail,"s",$email);
        mysqli_stmt_execute($stmtEmail);
        mysqli_stmt_store_result($stmtEmail);
        if(mysqli_stmt_num_rows($stmtEmail) > 0){
            header("Location: masters.php");
            exit();
        }

        $stmtPhone = mysqli_prepare($conn, "SELECT id FROM users WHERE phone=?");
        mysqli_stmt_bind_param($stmtPhone,"s",$phone);
        mysqli_stmt_execute($stmtPhone);
        mysqli_stmt_store_result($stmtPhone);
        if(mysqli_stmt_num_rows($stmtPhone) > 0){
            header("Location: masters.php");
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $unique_id = uniqid("user_");

        $sql = "INSERT INTO users (unique_id, full_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql)){
            mysqli_stmt_bind_param($stmt,"sssss",$unique_id,$full_name,$email,$phone,$passwordHash);
            mysqli_stmt_execute($stmt);
        }

    } elseif($action === 'edit'){
        $id = intval($_POST['id']);
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];

        if(!empty($password)){
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET full_name=?, email=?, phone=?, password=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt, $sql)){
                mysqli_stmt_bind_param($stmt,"ssssi",$full_name,$email,$phone,$passwordHash,$id);
                mysqli_stmt_execute($stmt);
            }
        } else {
            $sql = "UPDATE users SET full_name=?, email=?, phone=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt, $sql)){
                mysqli_stmt_bind_param($stmt,"sssi",$full_name,$email,$phone,$id);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header("Location: masters.php");
    exit();
}

// Delete user
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])){
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: masters.php");
    exit();
}
?>
