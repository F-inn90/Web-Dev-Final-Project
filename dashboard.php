<?php
// Database connection
$host = 'localhost';
$user = 'root'; // change if needed
$pass = '';     // change if needed
$dbname = 'university_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $rank = intval($_POST['rank']);
        $name = $conn->real_escape_string($_POST['name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $class = intval($_POST['class']);
        $conn->query("INSERT INTO students (name, gender, class, rank) VALUES ('$name','$gender','$class','$rank')");
    }
    if (isset($_POST['update_student'])) {
        $id = intval($_POST['id']);
        $rank = intval($_POST['rank']);
        $name = $conn->real_escape_string($_POST['name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $class = intval($_POST['class']);
        $conn->query("UPDATE students SET name='$name', gender='$gender', class='$class', rank='$rank' WHERE id='$id'");
    }
    if (isset($_POST['delete_student'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM students WHERE id='$id'");
    }
    // Redirect back to the same page (including query string)
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit;
}

// Determine active page
$active_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Fetch students
$students_result = $conn->query("SELECT * FROM students ORDER BY rank ASC");

// Fetch news
$news_result = $conn->query("SELECT * FROM news_feed ORDER BY id DESC");
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
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f5f5f5;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: white;
      height: 100vh;
      position: fixed;
      padding-top: 20px;
    }
    .sidebar-header {
      text-align: center;
      padding: 20px;
      border-bottom: 1px solid #34495e;
    }
    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar-menu li {
      padding: 15px 20px;
      border-bottom: 1px solid #34495e;
      transition: all 0.3s;
    }
    .sidebar-menu li:hover {
      background-color: #34495e;
    }
    .sidebar-menu li.active {
      background-color: #3498db;
    }
    .sidebar-menu a {
      color: white;
      text-decoration: none;
      display: block;
    }
    .main-content {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 20px;
    }
    .header {
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      text-align: center;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .section {
      background-color: white;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .main-content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-header">
    <h2>University System</h2>
  </div>
  <ul class="sidebar-menu">
    <li class="<?php echo $active_page == 'dashboard' ? 'active' : ''; ?>">
      <a href="?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <li class="<?php echo $active_page == 'students' ? 'active' : ''; ?>">
      <a href="?page=students"><i class="fas fa-users"></i> Students</a>
    </li>
    <li class="<?php echo $active_page == 'news' ? 'active' : ''; ?>">
      <a href="?page=news"><i class="fas fa-newspaper"></i> News Feed</a>
    </li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="header">
    <h1>
      <?php
        switch ($active_page) {
          case 'dashboard': echo 'Dashboard Overview'; break;
          case 'students': echo 'Student Management'; break;
          case 'news': echo 'University News'; break;
          default: echo 'University Dashboard'; break;
        }
      ?>
    </h1>
  </div>

  <?php if ($active_page == 'dashboard' || $active_page == 'students'): ?>
  <!-- Students Section -->
  <div class="section">
    <h2 class="section-title">Top Students</h2>
    <?php if ($active_page == 'students'): ?>
      <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-plus"></i> Add Student
      </button>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Rank</th>
          <th>Name</th>
          <th>Gender</th>
          <th>Class</th>
          <?php if ($active_page == 'students'): ?><th>Actions</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($students_result->num_rows > 0) {
            while ($row = $students_result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['rank']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['gender']}</td>
                      <td>{$row['class']}</td>";
              if ($active_page == 'students') {
                echo "<td>
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editStudentModal'
                          data-id='{$row['id']}' data-rank='{$row['rank']}' data-name='{$row['name']}'
                          data-gender='{$row['gender']}' data-class='{$row['class']}'>
                          Edit
                        </button>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteStudentModal'
                          data-id='{$row['id']}'>
                          Delete
                        </button>
                      </td>";
              }
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='" . ($active_page == 'students' ? 5 : 4) . "'>No students found</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <?php if ($active_page == 'dashboard' || $active_page == 'news'): ?>
  <!-- News Section -->
  <div class="section">
    <h2 class="section-title">University News</h2>
    <?php
      if ($news_result->num_rows > 0) {
        while ($row = $news_result->fetch_assoc()) {
          echo "<div class='mb-3'>
                  <h5>{$row['title']}</h5>
                  <p>{$row['description']}</p>
                </div>";
        }
      } else {
        echo "<p>No news available</p>";
      }
    ?>
  </div>
  <?php endif; ?>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Add New Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="number" name="rank" class="form-control mb-2" placeholder="Rank" required>
          <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
          <select name="gender" class="form-control mb-2" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
          </select>
          <input type="number" name="class" class="form-control mb-2" placeholder="Class" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_student" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <input type="number" name="rank" id="edit_rank" class="form-control mb-2" required>
          <input type="text" name="name" id="edit_name" class="form-control mb-2" required>
          <select name="gender" id="edit_gender" class="form-control mb-2" required>
            <option>Male</option>
            <option>Female</option>
          </select>
          <input type="number" name="class" id="edit_class" class="form-control mb-2" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_student" class="btn btn-warning">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Delete Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this student?</p>
          <input type="hidden" name="id" id="delete_id">
        </div>
        <div class="modal-footer">
          <button type="submit" name="delete_student" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Edit Modal fill data
    var editModal = document.getElementById('editStudentModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_rank').value = button.getAttribute('data-rank');
        document.getElementById('edit_name').value = button.getAttribute('data-name');
        document.getElementById('edit_gender').value = button.getAttribute('data-gender');
        document.getElementById('edit_class').value = button.getAttribute('data-class');
    });

    // Delete Modal fill data
    var deleteModal = document.getElementById('deleteStudentModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('delete_id').value = button.getAttribute('data-id');
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
