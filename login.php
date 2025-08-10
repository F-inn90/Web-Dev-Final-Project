<?php
session_start();
// Database connection
$servername = "localhost";
$dbusername = "root"; // MySQL username
$dbpassword = "";     // MySQL password
$dbname = "university_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepared statement to prevent SQL injection
    $sql = "SELECT * FROM tblusers WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login success
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];

        header("Location: dashboard.php");
        exit();
    } else {
        $message = "❌ Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://paragoniu.edu.kh/wp-content/uploads/2020/09/Paragon.U-Campus-min-800x533.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: inherit;
            filter: blur(8px);
            z-index: -1;
        }
        .login-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 350px;
            overflow: hidden;
        }
        .header {
            background: #2236e9;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .login-form { padding: 30px; }
        .input-group { margin-bottom: 20px; }
        .input-group input {
            width: 100%; padding: 15px;
            border: 1px solid #ddd; border-radius: 5px;
            font-size: 16px; outline: none;
        }
        .signin-btn {
            width: 100%; background: #2236e9;
            color: white; border: none;
            padding: 15px; border-radius: 5px;
            font-size: 16px; cursor: pointer;
        }
        .message { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="header"><h1>Sign In</h1></div>
        <form class="login-form" method="POST">
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="signin-btn">Sign in</button>
        </form>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.querySelector('form'); // your login form
  if (!form) return;
  form.addEventListener('submit', function(e){
    const username = form.querySelector('input[name="username"]').value.trim();
    const password = form.querySelector('input[name="password"]').value.trim();
    if (!username || !password) {
      e.preventDefault();
      alert('This field is required'); // exact message from PDF
      return false;
    }
  });
});
</script>

</body>
</html>

