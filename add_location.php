<?php
include "includes/header.php";
include "includes/navbar.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
      foreach ($_POST['id'] as $id) {
        $query = "DELETE FROM `address` WHERE `id` = $id";

        $delete_address_query = mysqli_query($connection, $query);

        if (!$delete_address_query) {
          die("Query Failed" . mysqli_error($connection));
        }
      }
    }
  }

  if (isset($_POST['add_location'])) {
    $location = trim($_POST['location']);

    $error = [
      'location' => ''
    ];

    if (empty($location)) {
      $error['location'] = "Location cannot be empty";
    }

    foreach ($error as $key => $value) {
      if (empty($value)) {
        unset($error[$key]);
      }
    }

    if (empty($error)) {
      global $connection;

      $location = mysqli_real_escape_string($connection, $location);

      $query = "INSERT INTO `address`(`location`) VALUES ('$location')";

      $result = mysqli_query($connection, $query);

      if (!$result) {
        die("Query Failed" . mysqli_error($connection));
      }

      header("Location: add_location.php");
    }
  }
}

?>

<main class="container-fluid bg-dark text-white min-h-screen p-4">
  <div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold animate__animated animate__rubberBand">Add Location</h1>
  </div>

  <div class="card bg-dark text-white animate__animated animate__bounceInDown">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Location Table</h4>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Location
      </button>
    </div>
    <div class="card-body">
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="table-responsive mb-3">
        <table class="table table-bordered table-dark table-striped table-hover " id="example" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th width="1%">
        <div class="form-check">
          <input type="checkbox" id="checkAll" class="form-check-input">
        </div>
      </th>
      <th>ID</th>
      <th>Location</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>
      </th>
      <th>ID</th>
      <th>Location</th>
    </tr>
  </tfoot>
  <tbody>
    <?php
    $query = "SELECT * FROM `address`";

    $select_address_query = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_address_query)) :
      $address_id = $row['id'];
      $address_location = $row['location'];
    ?>
      <tr class="bg-gray-800 hover:bg-gray-900">
        <td>
          <input type="checkbox" class="checkItem form-check-input" value="<?php echo $address_id; ?>" name="id[]">
        </td>
        <td class="text-white"><?php echo $address_id; ?></td>
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
    <div class="modal-content bg-dark text-white">
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Location</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for "location" class="form-label">Location</label>
            <input type="text" class="form-control <?php echo (!empty($error['location'])) ? 'is-invalid' : ''; ?>" id="location" name="location">
            <span class="invalid-feedback"><?php echo isset($error['location']) ? $error['location'] : '' ?></span>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_location" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php" ?>
