<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit();
}

$userName = isset($_SESSION["user_name"]) ? explode(" ", $_SESSION["user_name"])[0] : "User";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .logout-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        font-size: 1.5rem;
        cursor: pointer;
        color: #b00e0eff;
    }
</style>
</head>
<body>
<div class="container mt-5 text-center">
    <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p class="lead">Manage your account efficiently and securely.</p>
</div>

<!-- Logout Icon -->
<i class="bi bi-power logout-btn" onclick="logoutConfirm()" title="Logout"></i>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Logout</h5></div>
      <div class="modal-body">Do you really want to log out</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function logoutConfirm() {
    var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
    logoutModal.show();
}
</script>
</body>
</html>