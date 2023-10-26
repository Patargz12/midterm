<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
      foreach ($_POST['id'] as $id) {
        $query = "DELETE FROM `users` WHERE `id` = $id";

        $delete_user_query = mysqli_query($connection, $query);

        if (!$delete_user_query) {
          die("Query Failed" . mysqli_error($connection));
        }
      }
    }
  }

  if (isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim(($_POST['email']));
    $password = trim($_POST['password']);
    $address_id = isset($_POST['address_id']) ? trim($_POST['address_id']) : '';

    $error = [
      'name' => '',
      'email' => '',
      'password' => '',
      'address_id' => ''
    ];

    if (empty($name)) {
      $error['name'] = "Name cannot be empty";
    }

    if (empty($email)) {
      $error['email'] = "Email cannot be empty";
    }

    if (empty($password)) {
      $error['password'] = "Password cannot be empty";
    }

    if (empty($address_id)) {
      $error['address_id'] = "Address cannot be empty";
    }

    foreach ($error as $key => $value) {
      if (empty($value)) {
        unset($error[$key]);
      }
    }

    if (empty($error)) {
      global $connection;

      $name = mysqli_real_escape_string($connection, $name);
      $email = mysqli_real_escape_string($connection, $email);
      $password = mysqli_real_escape_string($connection, $password);
      $address_id = mysqli_real_escape_string($connection, $address_id);

      $query = "INSERT INTO `users`(`name`, `email`, `password`, `address_id`) VALUES ('$name','$email','$password','$address_id')";

      $result = mysqli_query($connection, $query);

      if (!$result) {
        die("Query Failed" . mysqli_error($connection));
      }

      header("Location: users.php");
    }
  }
}
?>

<main class="container-fluid bg-dark text-white min-vh-100 p-4">
  <div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis animate__animated animate__rubberBand" style="color: white !important;">Users</h1>
  </div>

  <div class="card bg-dark text-white animate__animated animate__bounceInDown">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h4 class="mb-0" >Users Table</h4>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-plus-circle me-2"></i>Add New User
      </button>
    </div>
    <div class="card-body">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="table-responsive mb-3">
          <table class="table table-bordered table-dark table-striped table-hover" id="example" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">
                  <div class="form-check">
                    <input type="checkbox" id="checkAll" class="form-check-input">
                  </div>
                </th>
                <th>ID</th>
                <th>Student Name</th>
                <th>Email Address</th>
                <th>Address</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th></th>
                <th>ID</th>
                <th>Student Name</th>
                <th>Email Address</th>
                <th>Address</th>
              </tr>
            </tfoot>
            <tbody>
              <?php
              $query = "SELECT users.id, users.name, users.email, address.location
          FROM `users`
          INNER JOIN address ON users.address_id = address.id;";

              $select_users_query = mysqli_query($connection, $query);

              while ($row = mysqli_fetch_assoc($select_users_query)) :
                $users_id = $row['id'];
                $users_name = $row['name'];
                $users_email = $row['email'];
                $address_location = $row['location'];
              ?>
                <tr class="bg-gray-800 hover:bg-gray-900">
                  <td>
                    <input type="checkbox" class="checkItem form-check-input" value="<?php echo $users_id; ?>" name="id[]">
                  </td>
                  <td class="text-white"><?php echo $users_id; ?></td>
                  <td class="text-white"><?php echo $users_name; ?></td>
                  <td class="text-white"><?php echo $users_email; ?></td>
                  <td class="text-white"><?php echo $address_location; ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger"><i class="bi bi-trash me-2"></i>Delete</button>
      </form>
    </div>
  </div>
</main>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="addUserForm" enctype="multipart/form-data">
        <div class="modal-header bg-dark text-white">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-dark">
          <div class="mb-3">
            <label for="name" class="form-label text-white">Student Name</label>
            <input type="text" class="form-control <?php echo (!empty($error['name'])) ? 'is-invalid' : ''; ?>" id="name" name="name">
            <span class="invalid-feedback"><?php echo isset($error['name']) ? $error['name'] : '' ?></span>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label text-white">Email Address</label>
            <input type="email" class="form-control <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" id="email" name="email">
            <span class="invalid-feedback"><?php echo isset($error['email']) ? $error['email'] : '' ?></span>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label text-white">Password</label>
            <input type="password" class="form-control <?php echo (!empty($error['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password">
            <span class="invalid-feedback"><?php echo isset($error['password']) ? $error['password'] : '' ?></span>
          </div>

          <div class="mb-3">
            <label for="address_id" class="form-label text-white">Address</label>
            <select class="form-select <?php echo (!empty($error['address_id'])) ? 'is-invalid' : ''; ?>" aria-label="Default select example" id="address_id" name="address_id" form="addUserForm">
              <option selected disabled>Select Address</option>
              <?php
              $query = "SELECT * FROM `address`";
              $select_address_query = mysqli_query($connection, $query);

              while ($row = mysqli_fetch_assoc($select_address_query)) :
                $address_id = $row['id'];
                $address_location = $row['location'];
              ?>
                <option value="<?php echo $address_id; ?>" class="text-dark"><?php echo $address_location; ?></option>
              <?php endwhile; ?>
            </select>
            <span class="invalid-feedback"><?php echo isset($error['address_id']) ? $error['address_id'] : '' ?></span>
          </div>
        </div>
        <div class="modal-footer bg-dark">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_user" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
