<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'config/db.php';

// Fetch users (not soft-deleted)
$stmt = $pdo->prepare("SELECT * FROM users WHERE deleted_at IS NULL ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar navbar-expand bg-dark navbar-dark">
    <div class="container">
      <a href="#" class="navbar-brand">User Management</a>
      <div class="ms-auto text-white">
        Welcome, <?=htmlspecialchars($_SESSION['user_name'])?> |
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h3>User List</h3>

    <!-- Bulk Delete Button -->
    <button id="deleteSelectedBtn" class="btn btn-danger mb-3" disabled>Delete Selected</button>

    <table class="table table-bordered table-striped" id="userTable">
      <thead class="table-dark">
        <tr>
          <th><input type="checkbox" id="selectAll" /></th>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
        <tr data-user-id="<?= $user['id'] ?>">
          <td><input type="checkbox" class="user-checkbox" value="<?= $user['id'] ?>" /></td>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td>
            <button class="btn btn-sm btn-warning editBtn">Edit</button>
            <button class="btn btn-sm btn-danger softDeleteBtn">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <hr />
    <h3>Add Multiple Users</h3>
    <form id="bulkUserForm">
      <div id="usersWrapper">
        <div class="userRow row mb-2">
          <div class="col">
            <input type="text" class="form-control" name="name[]" placeholder="Name" required />
          </div>
          <div class="col">
            <input type="email" class="form-control" name="email[]" placeholder="Email" required />
          </div>
          <div class="col">
            <input type="password" class="form-control" name="password[]" placeholder="Password" required />
          </div>
          <div class="col">
            <select class="form-select" name="role[]" required>
              <option value="">Select Role</option>
              <option>Project Manager</option>
              <option>Team Lead</option>
              <option>Developer</option>
            </select>
          </div>
          <div class="col-auto">
            <button type="button" class="btn btn-danger removeUserRowBtn">&times;</button>
          </div>
        </div>
      </div>
      <button type="button" id="addUserRowBtn" class="btn btn-secondary mb-3">Add More</button>
      <button type="submit" class="btn btn-success">Add Users</button>
    </form>

    <div id="bulkAddMsg" class="mt-3"></div>
  </div>

  <!-- Modal for Editing User -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="editUserForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editUserId" />
          <div class="mb-3">
            <label for="editUserName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editUserName" name="name" required />
          </div>
          <div class="mb-3">
            <label for="editUserEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editUserEmail" name="email" required />
          </div>
          <div class="mb-3">
            <label for="editUserRole" class="form-label">Role</label>
            <select class="form-select" id="editUserRole" name="role" required>
              <option>Project Manager</option>
              <option>Team Lead</option>
              <option>Developer</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editUserPassword" class="form-label">Password <small>(Leave blank to keep unchanged)</small></label>
            <input type="password" class="form-control" id="editUserPassword" name="password" />
          </div>
          <div id="editUserMsg" class="text-danger"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>

  <!-- jQuery + Bootstrap Bundle JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="js/main.js"></script>
</body>
</html>
