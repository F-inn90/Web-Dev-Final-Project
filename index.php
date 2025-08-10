<?php
// Start a session to check for logged-in user
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'university_db';

// Create a new mysqli object to connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    // If connection fails, stop the script and display the error
    die("Connection failed: " . $conn->connect_error);
}

// Fetch news items from the database, ordered by creation date in descending order
$news_result = $conn->query("SELECT title, description, created_at FROM news_feed ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>University</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /*
     * The original style block from Home.html
     */
    body {
      margin: 0;
      font-family: 'Open Sans', sans-serif;
      line-height: 1.6;
    }

    header {
      background-color: #007ACC;
      color: white;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    /* Style for the new image logo */
    .header-logo {
        height: 40px; /* Adjust the height to fit the header */
        width: auto;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin-left: 25px;
    }

    .hero {
      position: relative;
      height: 400px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      /* Using a placeholder filename for the user's specified background image */
      background: url('background.png') center center / cover no-repeat;
      /* Applying a subtle blur effect */
      filter: blur(3px);
      z-index: 1;
    }

    .hero h1 {
      position: relative;
      z-index: 2;
      font-size: 36px;
      color: white;
      background-color: rgba(0, 0, 0, 0.5);
      padding: 20px;
      border-radius: 8px;
    }

    .section {
      padding: 60px 20px;
      max-width: 1100px;
      margin: auto;
    }

    .section h2 {
      color: #007ACC;
      text-align: center;
    }

    .welcome-section {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-top: 30px;
    }

    .welcome-text {
      flex: 2;
    }

    .notice-board {
      flex: 1;
    }

    .notice-board p {
      margin-bottom: 15px;
    }

    .notice-board p b {
      color: #007ACC;
    }

    .features {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }

    .feature {
      flex: 1;
      min-width: 220px;
    }

    .feature img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 5px;
    }

    .feature h4 {
      color: #007ACC;
      margin-top: 10px;
    }

    .top-news {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      margin-top: 40px;
    }

    .news-item {
      flex: 1;
      min-width: 250px;
      border-left: 3px solid #007ACC;
      padding-left: 15px;
    }

    .news-item span {
      display: block;
      font-size: 22px;
      font-weight: bold;
      color: #007ACC;
    }

    footer {
      text-align: center;
      background: #007ACC;
      color: white;
      padding: 10px 0;
      margin-top: 60px;
      width: 100%;
    }
    
    .user-menu-container {
        position: relative;
        display: inline-block;
        margin-left: 25px;
    }

    .user-menu-toggle {
        color: white;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 5px;
        background-color: #0066A2;
        transition: background-color 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .user-menu-toggle:hover {
        background-color: #00598A;
    }

    .user-menu-toggle i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .user-menu-toggle.active i {
        transform: rotate(180deg);
    }
    
    .user-dropdown {
        position: absolute;
        right: 0;
        background-color: white;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1001;
        min-width: 160px;
        border-radius: 5px;
        overflow: hidden;
        margin-top: 5px;
        opacity: 0;
        transform: translateY(-10px);
        visibility: hidden;
        transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
    }

    .user-dropdown.show {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
    }
    
    .dropdown-item {
        color: #007ACC !important;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
        margin-left: 0;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1;
    }
    
    /* --- Media Queries for Responsiveness from uploaded style.css --- */
    @media (max-width: 768px) {
        header {
            flex-direction: column;
            padding: 10px;
        }
        nav {
            margin-top: 10px;
            text-align: center;
        }
        nav a {
            margin: 0 10px;
            font-size: 14px;
        }
        .hero h1 {
            font-size: 24px;
            padding: 10px;
        }
        .section {
            padding: 30px 10px;
        }
        .welcome-section {
            flex-direction: column;
            gap: 20px;
        }
        .features {
            flex-direction: column;
            gap: 15px;
        }
        .top-news {
            flex-direction: column;
            gap: 20px;
        }
        .user-menu-container {
            margin-top: 10px;
            margin-left: 0;
        }
    }
  </style>
</head>
<body>

  <header>
    <img src="paragon-logo.png" alt="University Logo" class="header-logo">
    <nav>
      <a href="index.php">Home</a>
      <a href="#">About</a>
      <a href="#">Admissions</a>
      <a href="#">Academics</a>
      <a href="#">Contact</a>
      <?php if (isset($_SESSION['username'])): ?>
        <div class="user-menu-container">
          <a href="#" class="user-menu-toggle">
            Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>
            <i class="fas fa-caret-down"></i>
          </a>
          <div class="user-dropdown">
            <a href="dashboard.php" class="dropdown-item">Go to Dashboard</a>
            <a href="logout.php" class="dropdown-item">Sign Out</a>
          </div>
        </div>
      <?php else: ?>
        <a href="dashboard.php">Sign In</a>
      <?php endif; ?>
    </nav>
  </header>

  <div class="hero">
    <h1>Welcome to Paragon International University</h1>
  </div>

  <div class="section welcome-section">
    <div class="welcome-text">
      <h2>Welcome to the Paragon International University Cambodia</h2>
      <p>Paragon International University Cambodia, formerly known as Zaman University, is a top-ranked university in Cambodia. We offer a world-class education with a focus on preparing students for successful careers in a globalized world.</p>
    </div>
    <div class="notice-board">
      <p><b>Official Announcements:</b></p>
      <p><b>Upcoming Events:</b></p>
      <p><b>Holiday Notice:</b></p>
    </div>
  </div>

  <div class="section features">
     <div class="feature">
      <img src="https://images.pexels.com/photos/1106468/pexels-photo-1106468.jpeg" alt="Student Life">
      <h4>Future Ready</h4>
      <p>Your gateway to growth, inspiration, and success.</p>
    </div>
    <div class="feature">
      <img src="https://images.pexels.com/photos/1595385/pexels-photo-1595385.jpeg" alt="Academic">
      <h4>Ideas in Action</h4>
      <p>Fuel your passion with purpose and direction.</p>
    </div>
    <div class="feature">
      <img src="https://images.pexels.com/photos/3762800/pexels-photo-3762800.jpeg" alt="Library">
      <h4>Campus Life</h4>
      <p>Connect, thrive, and discover your place in our diverse campus community.</p>
    </div>
  </div>

  <div class="section">
    <h2>Latest News</h2>
    <div class="top-news">
      <?php
      // Check if there are news items to display
      if ($news_result && $news_result->num_rows > 0) {
          // Loop through each row of the news_result
          while ($row = $news_result->fetch_assoc()) {
              // Extract the date from the created_at timestamp and format it.
              // This is a more standard day/month/year format.
              $date = new DateTime($row['created_at']);
              $formatted_date = $date->format('d/m/Y');

              // Echo the HTML for a single news item, populating it with data from the current row
              echo "<div class='news-item'>
                      <span>$formatted_date</span>
                      <strong>" . htmlspecialchars($row['title']) . "</strong><br>
                      " . nl2br(htmlspecialchars($row['description'])) . "
                    </div>";
          }
      } else {
          // If no news items are found, display a message
          echo "<p>No news available at the moment.</p>";
      }
      ?>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Paragon International University. All Rights Reserved.</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuToggle = document.querySelector('.user-menu-toggle');
        const userDropdown = document.querySelector('.user-dropdown');

        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', function(event) {
                event.preventDefault();
                userDropdown.classList.toggle('show');
                userMenuToggle.classList.toggle('active');
            });
            
            // Close the dropdown if the user clicks outside of it
            window.addEventListener('click', function(event) {
                if (!event.target.closest('.user-menu-container') && userDropdown.classList.contains('show')) {
                    userDropdown.classList.remove('show');
                    userMenuToggle.classList.remove('active');
                }
            });
        }
    });
  </script>

</body>
</html>
