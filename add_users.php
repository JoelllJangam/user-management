<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Multiple Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
  <h3>Add Multiple Users</h3>
  <form id="multiUserForm" novalidate>
    <div id="user-container">
      <div class="user-block border p-3 mb-3 rounded">
        <div class="row g-3">
          <div class="col-md-3">
            <input
              name="users[0][name]"
              type="text"
              class="form-control"
              placeholder="Name"
              required
            />
          </div>
          <div class="col-md-3">
            <input
              name="users[0][email]"
              type="email"
              class="form-control"
              placeholder="Email"
              required
            />
          </div>
          <div class="col-md-3">
            <input
              name="users[0][password]"
              type="password"
              class="form-control"
              placeholder="Password"
              required
              minlength="4"
            />
          </div>
          <div class="col-md-2">
            <select
              name="users[0][role]"
              class="form-select"
              required
            >
              <option value="">Select Role</option>
              <option>Project Manager</option>
              <option>Team Lead</option>
              <option>Developer</option>
            </select>
          </div>
          <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-remove-user" title="Remove" disabled>&times;</button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" class="btn btn-secondary mb-3" id="addMoreBtn">Add More</button>
    <br />
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="dashboard.php" class="btn btn-link">Back to Dashboard</a>
  </form>
</div>

<script>
$(document).ready(function () {
  // Add more user blocks
  let count = 1;
  $('#addMoreBtn').click(function () {
    let block = `
      <div class="user-block border p-3 mb-3 rounded">
        <div class="row g-3">
          <div class="col-md-3">
            <input name="users[${count}][name]" type="text" class="form-control" placeholder="Name" required />
          </div>
          <div class="col-md-3">
            <input name="users[${count}][email]" type="email" class="form-control" placeholder="Email" required />
          </div>
          <div class="col-md-3">
            <input name="users[${count}][password]" type="password" class="form-control" placeholder="Password" required minlength="4" />
          </div>
          <div class="col-md-2">
            <select name="users[${count}][role]" class="form-select" required>
              <option value="">Select Role</option>
              <option>Project Manager</option>
              <option>Team Lead</option>
              <option>Developer</option>
            </select>
          </div>
          <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-remove-user" title="Remove">&times;</button>
          </div>
        </div>
      </div>`;
    $('#user-container').append(block);
    count++;
    updateRemoveButtons();
  });

  // Remove user block
  $(document).on('click', '.btn-remove-user', function () {
    $(this).closest('.user-block').remove();
    updateRemoveButtons();
  });

  function updateRemoveButtons() {
    let total = $('.user-block').length;
    $('.btn-remove-user').prop('disabled', total === 1);
  }

  // Form submit with client validation
  $('#multiUserForm').submit(function (e) {
    e.preventDefault();
    if (this.checkValidity() === false) {
      this.classList.add('was-validated');
      return;
    }

    $.post('ajax/add_users.php', $(this).serialize(), function (res) {
      alert(res.message);
      window.location.href = 'dashboard.php';
    }, 'json').fail(() => alert('Error adding users. Please try again.'));
  });
});
</script>
</body>
</html>
