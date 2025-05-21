$(function() {
  // Login form submit via AJAX
  $('#loginForm').on('submit', function(e) {
    e.preventDefault();
    let form = $(this);
    $('#loginMsg').text('');
    form.find('button[type=submit]').prop('disabled', true);

    $.ajax({
      url: 'ajax/login.php',
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function(res) {
        if (res.status === 'success') {
          window.location.href = 'dashboard.php';
        } else {
          $('#loginMsg').text(res.message);
        }
      },
      error: function() {
        $('#loginMsg').text('Something went wrong.');
      },
      complete: function() {
        form.find('button[type=submit]').prop('disabled', false);
      }
    });
  });

  // Bulk user form submit via AJAX
  $('#bulkUserForm').on('submit', function(e) {
    e.preventDefault();
    let form = $(this);
    let msgDiv = $('#bulkAddMsg');
    msgDiv.text('').removeClass('text-success text-danger');
    form.find('button[type=submit]').prop('disabled', true);

    $.ajax({
      url: 'ajax/add_users.php',
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function(res) {
        if (res.status === 'success') {
          msgDiv.addClass('text-success').html(res.message);
          if (res.errors && res.errors.length) {
            msgDiv.append('<br>Errors:<br>' + res.errors.join('<br>'));
          }
          setTimeout(() => location.reload(), 1500);
        } else {
          msgDiv.addClass('text-danger').html(res.message);
          if (res.errors && res.errors.length) {
            msgDiv.append('<br>Errors:<br>' + res.errors.join('<br>'));
          }
        }
      },
      error: function() {
        msgDiv.addClass('text-danger').text('Something went wrong.');
      },
      complete: function() {
        form.find('button[type=submit]').prop('disabled', false);
      }
    });
  });

  // Add new user row to bulk add form
  $('#addUserRowBtn').on('click', function() {
    const userRow = `
      <div class="userRow row mb-2">
        <div class="col"><input type="text" class="form-control" name="name[]" placeholder="Name" required /></div>
        <div class="col"><input type="email" class="form-control" name="email[]" placeholder="Email" required /></div>
        <div class="col"><input type="password" class="form-control" name="password[]" placeholder="Password" required /></div>
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
      </div>`;
    $('#usersWrapper').append(userRow);
  });

  // Remove user row from bulk add form
  $(document).on('click', '.removeUserRowBtn', function() {
    $(this).closest('.userRow').remove();
  });

  // Select all users checkbox logic
  $('#selectAll').on('change', function() {
    const checked = $(this).is(':checked');
    $('.user-checkbox').prop('checked', checked);
    toggleDeleteSelectedBtn();
  });

  // Individual user checkbox change event
  $(document).on('change', '.user-checkbox', function() {
    // If any unchecked, uncheck selectAll
    if (!$(this).is(':checked')) {
      $('#selectAll').prop('checked', false);
    } else {
      // If all checked, check selectAll
      if ($('.user-checkbox').length === $('.user-checkbox:checked').length) {
        $('#selectAll').prop('checked', true);
      }
    }
    toggleDeleteSelectedBtn();
  });

  // Enable/Disable bulk delete button
  function toggleDeleteSelectedBtn() {
    const anyChecked = $('.user-checkbox:checked').length > 0;
    $('#deleteSelectedBtn').prop('disabled', !anyChecked);
  }

  // Bulk delete selected users
  $('#deleteSelectedBtn').on('click', function() {
    const ids = $('.user-checkbox:checked').map(function() {
      return $(this).val();
    }).get();

    if (ids.length === 0) return;

    if (!confirm(`Are you sure you want to delete ${ids.length} user(s)?`)) return;

    $.ajax({
      url: 'ajax/delete_users.php',
      method: 'POST',
      data: { ids },
      dataType: 'json',
      success: function(res) {
        if (res.status === 'success') {
          alert(res.message);
          location.reload();
        } else {
          alert(res.message);
        }
      },
      error: function() {
        alert('Something went wrong.');
      }
    });
  });

  // Soft delete a single user
  $(document).on('click', '.softDeleteBtn', function() {
    const tr = $(this).closest('tr');
    const id = tr.data('user-id');

    if (!confirm('Delete this user?')) return;

    $.ajax({
      url: 'ajax/soft_delete.php',
      method: 'POST',
      data: { id },
      dataType: 'json',
      success: function(res) {
        if (res.status === 'success') {
          tr.remove();
          toggleDeleteSelectedBtn();
        } else {
          alert(res.message);
        }
      },
      error: function() {
        alert('Something went wrong.');
      }
    });
  });

  // Show edit user modal with populated data
  $(document).on('click', '.editBtn', function() {
    const tr = $(this).closest('tr');
    const id = tr.data('user-id');
    const name = tr.find('td').eq(2).text().trim();
    const email = tr.find('td').eq(3).text().trim();
    const role = tr.find('td').eq(4).text().trim();

    $('#editUserId').val(id);
    $('#editUserName').val(name);
    $('#editUserEmail').val(email);
    $('#editUserRole').val(role);
    $('#editUserPassword').val('');
    $('#editUserMsg').text('');

    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editModal.show();
  });

  // Submit edit user form via AJAX
  $('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    let form = $(this);
    $('#editUserMsg').text('');
    form.find('button[type=submit]').prop('disabled', true);

    $.ajax({
      url: 'ajax/edit_user.php',
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function(res) {
        if (res.status === 'success') {
          alert('User updated successfully');
          location.reload();
        } else {
          $('#editUserMsg').text(res.message);
        }
      },
      error: function() {
        $('#editUserMsg').text('Something went wrong.');
      },
      complete: function() {
        form.find('button[type=submit]').prop('disabled', false);
      }
    });
  });
});
