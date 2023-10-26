<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
      foreach ($_POST['id'] as $id) {
        $query = "DELETE FROM `posts` WHERE `id` = $id";

        $delete_post_query = mysqli_query($connection, $query);

        if (!$delete_post_query) {
          die("Query Failed" . mysqli_error($connection));
        }
      }
    }
  }

  if (isset($_POST['add_post'])) {
    $title = trim($_POST['title']);
    $body = trim(($_POST['body']));
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';

    $error = [
      'title' => '',
      'body' => '',
      'user_id' => ''
    ];

    if (empty($title)) {
      $error['title'] = "Title cannot be empty";
    }

    if (empty($body)) {
      $error['body'] = "Body cannot be empty";
    }

    if (empty($user_id)) {
      $error['user_id'] = "Student Name cannot be empty";
    }

    foreach ($error as $key => $value) {
      if (empty($value)) {
        unset($error[$key]);
      }
    }

    if (empty($error)) {
      global $connection;

      $title = mysqli_real_escape_string($connection, $title);
      $body = mysqli_real_escape_string($connection, $body);
      $user_id = mysqli_real_escape_string($connection, $user_id);

      $query = "INSERT INTO `posts`(`title`, `body`, `user_id`) VALUES ('$title','$body','$user_id')";

      $result = mysqli_query($connection, $query);

      if (!$result) {
        die("Query Failed" . mysqli_error($connection));
      }

      header("Location: posts_view.php");
    }
  }

  if (isset($_POST['delete_post'])) {
    $id = $_POST['posts_id'];
    $query = "DELETE FROM `posts` WHERE `id` = $id";

    $delete_post_query = mysqli_query($connection, $query);

    if (!$delete_post_query) {
      die("Query Failed" . mysqli_error($connection));
    }
  }
}
?>


<style>
  .modal{
    z-index: 1000;
  }
</style>

<main class="container-fluid bg-dark text-white min-vh-100 p-4">
  <div class="px-4 py-5 my-5 text-center">
  <h1 class="display-5 fw-bold text-body-emphasis animate__animated animate__rubberBand" style="color: white !important;">Posts View</h1>

  </div>

  <div class="card bg-dark text-white animate__animated animate__bounceInDown">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Posts Table</h4>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Post
      </button>
    </div>
    <div class="card-body">
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
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
                <th>Title</th>
                <th>Body</th>
                <th>Student Name</th>
        
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th></th>
                <th>ID</th>
                <th>Title</th>
                <th>Body</th>
                <th>Student Name</th>
              
              </tr>
            </tfoot>
            <tbody>
              <?php
              $query = "SELECT posts.id, posts.title, posts.body, users.name
          FROM `posts`
          INNER JOIN users ON posts.user_id = users.id;";

              $select_posts_query = mysqli_query($connection, $query);

              while ($row = mysqli_fetch_assoc($select_posts_query)) :
                $posts_id = $row['id'];
                $posts_title = $row['title'];
                $posts_body = $row['body'];
                $user_name = $row['name'];
              ?>
                <tr>
                  <td>
                    <input type="checkbox" class="checkItem form-check-input" value="<?php echo $posts_id; ?>" name="id[]">
                  </td>
                  <td class="text-white"><?php echo $posts_id; ?></td>
                  <td class="text-white"><?php echo $posts_title; ?></td>
                  <td class="text-white"><?php echo $posts_body; ?></td>
                  <td class="text-white"><?php echo $user_name; ?></td>

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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="addUserForm" enctype="multipart/form-data">
        <div class="modal-header bg-dark text-white">
        <h1 class="modal-title fs-5 text-white" id="exampleModalLabel">Add New Post</h1>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-dark">
          <div class="mb-3">
            <label for="title" class="form-label text-white">Title</label>
            <input type="text" class="form-control <?php echo (!empty($error['title'])) ? 'is-invalid' : ''; ?>" id="title" name="title">
            <span class="invalid-feedback"><?php echo isset($error['title']) ? $error['title'] : '' ?></span>
          </div>

          <div class="mb-3">
            <label for="body" class="form-label text-white">Body</label>
            <input type="text" class="form-control <?php echo (!empty($error['body'])) ? 'is-invalid' : ''; ?>" id="body" name="body">
            <span class="invalid-feedback"><?php echo isset($error['body']) ? $error['body'] : '' ?></span>
          </div>

          <div class="mb-3">
            <label for="user_id" class="form-label text-white">Student Name</label>
            <select class="form-select <?php echo (!empty($error['user_id'])) ? 'is-invalid' : ''; ?>" aria-label="Default select example" id="user_id" name="user_id" form="addUserForm">
              <option selected disabled>Select Student Name</option>
              <?php
              $query = "SELECT * FROM `users`";

              $select_user_query = mysqli_query($connection, $query);

              while ($row = mysqli_fetch_assoc($select_user_query)) :
                $user_id = $row['id'];
                $user_name = $row['name'];
              ?>
                <option value="<?php echo $user_id; ?>" class="text-white"><?php echo $user_name; ?></option>
              <?php endwhile; ?>
            </select>
            <span class="invalid-feedback"><?php echo isset($error['user_id']) ? $error['user_id'] : '' ?></span>
          </div>
        </div>
        <div class="modal-footer bg-dark">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_post" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
