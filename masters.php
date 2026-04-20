<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

// Fetch all users
$sql = "SELECT id, unique_id, full_name, email, phone, created_at FROM users ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masters - User Management</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
.logout-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    font-size: 1.5rem;
    cursor: pointer;
    color: #dc3545;
}
body { background: #f8f9fa; }
</style>
</head>
<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Welcome, <?php echo htmlspecialchars(explode(" ", $_SESSION["username"])[0]); ?>!</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-lg"></i> Add User
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <button class='btn btn-sm btn-primary me-1'
                                onclick='editUser({$row['id']}, \"{$row['full_name']}\", \"{$row['email']}\", \"{$row['phone']}\")'>
                                <i class='bi bi-pencil'></i>
                            </button>

                            <button class='btn btn-sm btn-danger'
                                onclick='deleteUser({$row['id']})'>
                                <i class='bi bi-trash'></i>
                            </button>
                        </td>
                    </tr>";
                    $i++;
                }
            } else {
                echo "<tr>
                        <td colspan='6' class='text-center'>No users found</td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Logout Icon -->
<i class="bi bi-power logout-btn" onclick="logoutConfirm()" title="Logout"></i>

<!-- LOGOUT MODAL -->
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Do you really want to log out?
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
      </div>

    </div>
  </div>
</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="user_actions.php" method="post">

        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="full_name" required>
          </div>

          <div class="mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <div class="mb-3">
            <label>Phone <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="phone" required>
          </div>

          <div class="mb-3">
            <label>Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="password" required>
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="action" value="add">
            Add User
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="user_actions.php" method="post">
        <input type="hidden" name="id" id="edit_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
          </div>

          <div class="mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" id="edit_email" required>
          </div>

          <div class="mb-3">
            <label>Phone <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="phone" id="edit_phone" required>
          </div>

          <div class="mb-3">
            <label>Password (leave blank to keep unchanged)</label>
            <input type="password" class="form-control" name="password" id="edit_password">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="action" value="edit">
            Update User
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
      </div>

    </div>
  </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Script -->
<script>
$(document).ready(function(){

    window.logoutConfirm = function(){
        $('#logoutModal').modal('show');
    }

    window.editUser = function(id, full_name, email, phone){

        $('#edit_id').val(id);
        $('#edit_full_name').val(full_name);
        $('#edit_email').val(email);
        $('#edit_phone').val(phone);
        $('#edit_password').val('');

        $('#editUserModal').modal('show');
    }

    window.deleteUser = function(id){

        $('#confirmDeleteBtn').attr('href', 'user_actions.php?action=delete&id=' + id);

        $('#deleteUserModal').modal('show');
    }

});
</script>

</body>
</html>
