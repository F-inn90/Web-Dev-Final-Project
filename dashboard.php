<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'university_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CRUD for Students
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Students
    if (isset($_POST['add_student'])) {
        $idcard = $conn->real_escape_string($_POST['idcard']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $address = $conn->real_escape_string($_POST['address']);
        $class = $conn->real_escape_string($_POST['class']);
        $ranks = intval($_POST['ranks']);

        $conn->query("INSERT INTO tblstudents (idcard, firstname, lastname, gender, address, class, ranks)
                      VALUES ('$idcard', '$firstname', '$lastname', '$gender', '$address', '$class', $ranks)");
    }
    if (isset($_POST['update_student'])) {
        $id = intval($_POST['id']);
        $idcard = $conn->real_escape_string($_POST['idcard']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $address = $conn->real_escape_string($_POST['address']);
        $class = $conn->real_escape_string($_POST['class']);
        $ranks = intval($_POST['ranks']);

        $conn->query("UPDATE tblstudents SET idcard='$idcard', firstname='$firstname', lastname='$lastname',
                      gender='$gender', address='$address', class='$class', ranks=$ranks WHERE id=$id");
    }
    if (isset($_POST['delete_student'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM tblstudents WHERE id=$id");
    }

    // News CRUD
    if (isset($_POST['add_news'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);
        $created_at = date('Y-m-d H:i:s');

        $conn->query("INSERT INTO news_feed (title, description, created_at) VALUES ('$title', '$description', '$created_at')");
    }
    if (isset($_POST['update_news'])) {
        $id = intval($_POST['id']);
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);

        $conn->query("UPDATE news_feed SET title='$title', description='$description' WHERE id=$id");
    }
    if (isset($_POST['delete_news'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM news_feed WHERE id=$id");
    }

    // Redirect after POST to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . '?page=' . ($_GET['page'] ?? 'dashboard'));
    exit;
}

// Determine active page
$active_page = $_GET['page'] ?? 'dashboard';

// Fetch students
if ($active_page === 'students' || $active_page === 'dashboard') {
    $students_result = $conn->query("SELECT * FROM tblstudents ORDER BY ranks ASC");
    $student_count = $students_result->num_rows;
}

// Fetch news
if ($active_page === 'news' || $active_page === 'dashboard') {
    $news_result = $conn->query("SELECT * FROM news_feed ORDER BY created_at DESC");
    $news_count = $news_result->num_rows;
}

?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>University Dashboard</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<style>
  body {
    display: flex;
    min-height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
  }
  .sidebar {
    width: 220px;
    background: #343a40;
    color: white;
    flex-shrink: 0;
    padding-top: 20px;
  }
  .sidebar h2 {
    text-align: center;
    padding-bottom: 15px;
    border-bottom: 1px solid #495057;
    margin-bottom: 20px;
  }
  .sidebar a {
    display: block;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    font-weight: 500;
  }
  .sidebar a.active, .sidebar a:hover {
    background-color: #495057;
  }
  .main-content {
    flex-grow: 1;
    padding: 20px;
  }
  .table thead th {
    background-color: #343a40;
    color: white;
  }
  .card {
    margin-bottom: 20px;
  }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>University</h2>
  <a href="?page=dashboard" class="<?= $active_page === 'dashboard' ? 'active' : '' ?>">
    <i class="fas fa-home"></i> Dashboard
  </a>
  <a href="?page=students" class="<?= $active_page === 'students' ? 'active' : '' ?>">
    <i class="fas fa-users"></i> Students
  </a>
  <a href="?page=news" class="<?= $active_page === 'news' ? 'active' : '' ?>">
    <i class="fas fa-newspaper"></i> News Feed
</a>
<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
  </a>
</div>
<?php if (isset($_SESSION['firstname'])): ?>
  <div class="mb-3">
    <h4>Welcome, <?= htmlspecialchars($_SESSION['firstname']) ?>!</h4>
    <a href="logout.php" class="btn btn-sm btn-outline-secondary">Sign Out</a>
  </div>
<?php endif; ?>


<!-- Main content -->
<div class="main-content">
    <?php if (isset($_SESSION['firstname'])): ?>
    <div class="alert alert-info">
        Welcome, <?= htmlspecialchars($_SESSION['firstname']) ?>!
    </div>
<?php endif; ?>
  <?php if ($active_page === 'dashboard'): ?>
    <h1>Dashboard Overview</h1>
    <div class="row">
      <div class="col-md-6">
        <div class="card border-primary">
          <div class="card-body">
            <h5 class="card-title">Total Students</h5>
            <p class="card-text display-4"><?= $student_count ?? 0 ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-success">
          <div class="card-body">
            <h5 class="card-title">Total News Items</h5>
            <p class="card-text display-4"><?= $news_count ?? 0 ?></p>
          </div>
        </div>
      </div>
    </div>

    <h3>Latest Students</h3>
    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>Rank</th>
          <th>ID Card</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Gender</th>
          <th>Class</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if ($students_result && $students_result->num_rows > 0) {
          $count = 0;
          while ($row = $students_result->fetch_assoc()) {
            $count++;
            if ($count > 5) break; // Show only top 5
            echo "<tr>
              <td>{$row['ranks']}</td>
              <td>" . htmlspecialchars($row['idcard']) . "</td>
              <td>" . htmlspecialchars($row['firstname']) . "</td>
              <td>" . htmlspecialchars($row['lastname']) . "</td>
              <td>" . htmlspecialchars($row['gender']) . "</td>
              <td>" . htmlspecialchars($row['class']) . "</td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No students found</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <h3>Recent News</h3>
    <?php 
    if ($news_result && $news_result->num_rows > 0) {
      $news_result->data_seek(0); // Reset pointer for news
      $count = 0;
      while ($row = $news_result->fetch_assoc()) {
        $count++;
        if ($count > 5) break; // Show only 5 latest news
        ?>
        <div class="mb-3">
          <h5><?= htmlspecialchars($row['title']) ?></h5>
          <small class="text-muted"><?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?></small>
          <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        </div>
      <?php
      }
    } else {
      echo "<p>No news available</p>";
    }
    ?>

  <?php elseif ($active_page === 'students'): ?>
    <h1>Student Management</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
      <i class="fas fa-plus"></i> Add Student
    </button>

    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>Rank</th>
          <th>ID Card</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Gender</th>
          <th>Address</th>
          <th>Class</th>
          <th style="width: 140px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($students_result->num_rows > 0): ?>
          <?php while ($row = $students_result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['ranks']) ?></td>
              <td><?= htmlspecialchars($row['idcard']) ?></td>
              <td><?= htmlspecialchars($row['firstname']) ?></td>
              <td><?= htmlspecialchars($row['lastname']) ?></td>
              <td><?= htmlspecialchars($row['gender']) ?></td>
              <td><?= htmlspecialchars($row['address']) ?></td>
              <td><?= htmlspecialchars($row['class']) ?></td>
              <td>
                <button
                  class="btn btn-warning btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#editStudentModal"
                  data-id="<?= $row['id'] ?>"
                  data-idcard="<?= htmlspecialchars($row['idcard'], ENT_QUOTES) ?>"
                  data-firstname="<?= htmlspecialchars($row['firstname'], ENT_QUOTES) ?>"
                  data-lastname="<?= htmlspecialchars($row['lastname'], ENT_QUOTES) ?>"
                  data-gender="<?= $row['gender'] ?>"
                  data-address="<?= htmlspecialchars($row['address'], ENT_QUOTES) ?>"
                  data-class="<?= htmlspecialchars($row['class'], ENT_QUOTES) ?>"
                  data-ranks="<?= $row['ranks'] ?>"
                >Edit</button>
                <button
                  class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteStudentModal"
                  data-id="<?= $row['id'] ?>"
                >Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-center">No students found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  <?php elseif ($active_page === 'news'): ?>
    <h1>University News</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNewsModal">
      <i class="fas fa-plus"></i> Add News
    </button>

    <?php if ($news_result->num_rows > 0): ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Created At</th>
            <th style="width: 140px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $news_result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
              <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
              <td>
                <button
                  class="btn btn-warning btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#editNewsModal"
                  data-id="<?= $row['id'] ?>"
                  data-title="<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>"
                  data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>"
                >Edit</button>
                <button
                  class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteNewsModal"
                  data-id="<?= $row['id'] ?>"
                >Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No news available</p>
    <?php endif; ?>
  <?php else: ?>
    <h1>Welcome to University Dashboard</h1>
  <?php endif; ?>

</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="idcard" class="form-control mb-2" placeholder="ID Card" required>
        <input type="text" name="firstname" class="form-control mb-2" placeholder="First Name" required>
        <input type="text" name="lastname" class="form-control mb-2" placeholder="Last Name" required>
        <select name="gender" class="form-select mb-2" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
        <input type="text" name="address" class="form-control mb-2" placeholder="Address" required>
        <input type="text" name="class" class="form-control mb-2" placeholder="Class" required>
        <input type="number" name="ranks" class="form-control mb-2" placeholder="Rank" min="1" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_student" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="idcard" id="edit_idcard" class="form-control mb-2" placeholder="ID Card" required>
        <input type="text" name="firstname" id="edit_firstname" class="form-control mb-2" placeholder="First Name" required>
        <input type="text" name="lastname" id="edit_lastname" class="form-control mb-2" placeholder="Last Name" required>
        <select name="gender" id="edit_gender" class="form-select mb-2" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
        <input type="text" name="address" id="edit_address" class="form-control mb-2" placeholder="Address" required>
        <input type="text" name="class" id="edit_class" class="form-control mb-2" placeholder="Class" required>
        <input type="number" name="ranks" id="edit_ranks" class="form-control mb-2" placeholder="Rank" min="1" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update_student" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this student?</p>
        <input type="hidden" name="id" id="delete_id">
      </div>
      <div class="modal-footer">
        <button type="submit" name="delete_student" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add News Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
        <textarea name="description" class="form-control mb-2" rows="5" placeholder="Description" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_news" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit News Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit_news_id">
        <input type="text" name="title" id="edit_news_title" class="form-control mb-2" placeholder="Title" required>
        <textarea name="description" id="edit_news_description" class="form-control mb-2" rows="5" placeholder="Description" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update_news" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete News Modal -->
<div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete News Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this news item?</p>
        <input type="hidden" name="id" id="delete_news_id">
      </div>
      <div class="modal-footer">
        <button type="submit" name="delete_news" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS and FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Fill edit student modal
  var editStudentModal = document.getElementById('editStudentModal');
  editStudentModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('edit_id').value = button.getAttribute('data-id');
    document.getElementById('edit_idcard').value = button.getAttribute('data-idcard');
    document.getElementById('edit_firstname').value = button.getAttribute('data-firstname');
    document.getElementById('edit_lastname').value = button.getAttribute('data-lastname');
    document.getElementById('edit_gender').value = button.getAttribute('data-gender');
    document.getElementById('edit_address').value = button.getAttribute('data-address');
    document.getElementById('edit_class').value = button.getAttribute('data-class');
    document.getElementById('edit_ranks').value = button.getAttribute('data-ranks');
  });

  // Fill delete student modal
  var deleteStudentModal = document.getElementById('deleteStudentModal');
  deleteStudentModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('delete_id').value = button.getAttribute('data-id');
  });

  // Fill edit news modal
  var editNewsModal = document.getElementById('editNewsModal');
  editNewsModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('edit_news_id').value = button.getAttribute('data-id');
    document.getElementById('edit_news_title').value = button.getAttribute('data-title');
    document.getElementById('edit_news_description').value = button.getAttribute('data-description');
  });

  // Fill delete news modal
  var deleteNewsModal = document.getElementById('deleteNewsModal');
  deleteNewsModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('delete_news_id').value = button.getAttribute('data-id');
  });
});
</script>

</body>
</html>

<?php $conn->close(); ?>




