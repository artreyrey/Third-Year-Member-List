<?php
require_once 'features.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CE 3rd Year Home</title>
  <link rel="stylesheet" href="membersPage.css"/>
  <!-- jQuery (Required for the sign-out AJAX) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- chart script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    
    :root {
    --orange-color: #ff7300;
    --gray-color: #5c5c5c;
    --pearl-color: #f2f8fc;
    --cream-color: #ebeaea;
    --white: #ffffff;
    --gray-light: #e0e0e0;
    --orange-light: rgba(255, 115, 0, 0.1);

    --font-size-s: 0.9rem;
    --font-size-n: 1rem;
    --font-size-m: 1.12rem;
    --font-size-l: 1.5rem;
    --font-size-xl: 2.0rem;

    --border-radius-s: 8px;
    --border-radius-m: 30px;
    --border-radius-circle: 50%;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}


    body {
        background-color: #ebeaea;
        color: #5c5c5c;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .navbar {
      background-color: var(--orange-color);
      padding: 0.8rem 2rem;
      box-shadow: 0 4px 10px rgba(255, 115, 0, 0.3);
      position: sticky;
      top: 0;
      z-index: 999;
      min-height: 80px;
      border-bottom-left-radius: 0px;
      border-bottom-right-radius: 0px;
      margin-bottom: 30px;

      display: flex;
      align-items: center;
      justify-content: space-between;
      font-weight: 600;
      font-size: var(--font-size-m);
      user-select: none;
      transition: background-color 0.3s ease;
    }

    /* Tricom label on left */
    .navbar-left {
      color: var(--white);
      font-size: 1.4rem;
      font-weight: 700;
      padding-right: 2rem;
    }

    /* Centered nav links */
    .nav-list {
      list-style: none;
      display: flex;
      gap: 2.5rem;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      padding: 0;
      flex-grow: 1;
    }

    /* Nav links styling */
    .nav-link {
      color: var(--white);
      text-decoration: none;
      padding: 0.5rem 0.8rem;
      border-radius: var(--border-radius-s);
      transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
      font-weight: 600;
      position: relative;
      overflow: hidden;
    }

    .nav-link:hover {
      background-color: var(--orange-light);
      color: var(--white);
      box-shadow: 0 0 10px var(--white);
      text-shadow: 0 0 8px var(--white);
      transform: scale(1.1);
    }

    .nav-link.active {
      background-color: var(--white);
      color: var(--orange-color);
      box-shadow: 0 0 15px var(--white);
      font-weight: 700;
    }

    /* Underline animation */
    .nav-link::after {
      content: "";
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: var(--white);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .sign-out-btn {
      background-color: var(--orange-color);
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: var(--border-radius-s);
      padding: 0.5rem 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
      box-shadow: 0 0 8px #ff7a00;
      font-size: 1rem;
      user-select: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sign-out-btn:hover {
      background-color: #fff;
      color: var(--orange-color);
      box-shadow: 0 0 15px #ff7a00;
      transform: scale(1.05);
    }

    main {
      max-width: 960px;
      margin: 3rem auto;
      padding: 0 1rem;
      text-align: center;
      animation: fadeIn 1s ease forwards;
    }

    main h1 {
      color: var(--orange-color);
      font-size: 2.8rem;
      margin-bottom: 1rem;
      text-shadow: 0 0 10px #ffa733;
      margin-bottom: 20px;
    }

    main p {
      color: #b35400;
      font-weight: 600;
      font-size: 1.4rem;
      letter-spacing: 0.03em;
      text-shadow: 0 0 6px #ffb84d;
      margin-bottom: 90px;
    }

    main h2 {
      margin-top: 150px;
      color: #cc6200;
      margin-bottom: 2rem;
      font-weight: 700;
      font-size: 2rem;
      text-shadow: 0 0 7px #ffa733aa;
      margin-bottom: 90px;
    }

    .feature-grid {
      display: flex;
      gap: 2rem;
      justify-content: center;
      flex-wrap: wrap;
      padding-bottom: 2rem;
    }

    .feature-box {
      flex: 1 1 180px;
      max-width: 220px;
      height: 220px;
      background: linear-gradient(145deg, #ff7300, #ff9b33);
      border-radius: 24px;
      box-shadow: 0 8px 20px rgba(255, 115, 0, 0.6);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: 1.3rem;
      transition: transform 0.35s ease, box-shadow 0.35s ease, background 0.35s ease;
      text-align: center;
      padding: 1.2rem;
      cursor: default;
      user-select: none;
      animation: popIn 0.8s ease forwards;
    }

    .feature-box:hover {
      background: linear-gradient(145deg, #e46300, #ff7f00);
      transform: scale(1.12) rotate(-1deg);
      box-shadow: 0 14px 30px rgba(255, 115, 0, 0.85);
    }

    .feature-box img {
      width: 72px;
      height: 72px;
      margin-bottom: 1rem;
      filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.4));
      animation: pulse 2.5s infinite ease-in-out;
    }

    @keyframes pulse {
      0%, 100% {
        filter: drop-shadow(0 0 4px rgba(0,0,0,0.4));
        transform: scale(1);
      }
      50% {
        filter: drop-shadow(0 0 10px rgba(255, 115, 0, 0.9));
        transform: scale(1.1);
      }
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(15px);}
      to {opacity: 1; transform: translateY(0);}
    }

    @keyframes popIn {
      from {opacity: 0; transform: scale(0.8);}
      to {opacity: 1; transform: scale(1);}
    }

    @media (max-width: 600px) {
      .nav-list {
        gap: 1.2rem;
        font-size: 0.9rem;
      }
      .feature-box {
        max-width: 160px;
        height: 180px;
        font-size: 1.1rem;
      }
      main h1 {
        font-size: 2rem;
      }
      main p {
        font-size: 1rem;
      }
    }

    /* Chart style */
.chart-section {
  display: flex;
  gap: 3rem; /* Reduced gap for better side-by-side display */
  justify-content: center;
  flex-wrap: wrap;
  padding: 2rem;
  width: 100%;
  scale: 150%;
  max-width: 1200px; 
  
}

.chart-container-gender, 
.chart-container-role {
  margin-top: 80px;
  background: white;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  width: 45%; /* Changed from 400% to 45% for side-by-side */
  min-width: 400px; /* Minimum width to prevent squeezing */
  transition: transform 0.3s ease;
  flex: 1; 
  margin-bottom: 110px;
}

.chart-container-gender:hover, 
.chart-container-role:hover {
  transform: translateY(-5px);
}


.chart-container-gender h2, 
.chart-container-role h2 {
  color: var(--orange-color);
  margin: 0 0 1rem 0; 
  font-size: 1.3rem; 
  text-align: center;
  padding-top: 0; 
}

/* Responsive adjustments */
@media (max-width: 1000px) {
  .chart-section {
    flex-direction: column;
    align-items: center;
  }
  
  .chart-container-gender, 
  .chart-container-role {
    width: 90%;
  }

  
}
  </style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-left">Tricom</div>
  <ul class="nav-list">
    <li><a href="home.php" class="nav-link active">Home</a></li>
    <li><a href="membersPage.php" class="nav-link">Members</a></li>
    <li><a href="profile.php" class="nav-link">Profile</a></li>
  </ul>
  <button class="sign-out-btn" id="signOutBtn">
    <i class="fas fa-sign-out-alt"></i> Sign Out
  </button>
</nav>

<main>
  <h1>Welcome to TRICOM!!</h1>
  <p>Your easy-to-use Members Management System!</p>
  <!-- Chart section -->
  <section class="chart-section">
  <div class="chart-container-gender">
    <h2>Gender Distribution</h2>
    <canvas id="genderChart"></canvas>
  </div>
  <div class="chart-container-role">
    <h2>Role Distribution</h2>
    <canvas id="roleChart"></canvas>
  </div>
</section>

  <h2>FEATURES</h2>

  <section class="feature-grid">
    <div class="feature-box">
      <img src="https://cdn-icons-png.flaticon.com/512/992/992651.png" alt="Add or Edit Members" />
      Add/Edit Members
    </div>
    <div class="feature-box">
      <img src="https://cdn-icons-png.flaticon.com/512/1214/1214428.png" alt="Delete Members" />
      Delete Members
    </div>
    <div class="feature-box">
      <img src="https://cdn-icons-png.flaticon.com/512/1144/1144760.png" alt="Customize Profile" />
      Customize Your Profile
    </div>
    <div class="feature-box">
      <img src="https://cdn-icons-png.flaticon.com/128/33/33308.png" alt="Filter Members" />
      Filter by Gender/Position
    </div>
    <div class="feature-box">
      <img src="https://cdn-icons-png.flaticon.com/128/3022/3022251.png" alt="Print Options" />
      Print by Group/Individual
    </div>
  </section>
</main>

<!-- Signout script -->
<script>
  $('#signOutBtn').click(function () {
    $.ajax({
      url: 'logout.php',
      type: 'POST',
      success: function () {
        window.location.href = 'adminLogin.php';
      },
      error: function () {
        window.location.href = 'adminLogin.php';
      }
    });
  });
</script>
<!-- Chart script -->
<script>
$(document).ready(function() {
  // Fetch chart data
  $.ajax({
    url: 'get_chart_data.php',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      // Gender Chart
      const genderCtx = document.getElementById('genderChart').getContext('2d');
      new Chart(genderCtx, {
        type: 'bar',
        data: {
          labels: ['Male', 'Female'],
          datasets: [{
            label: 'Gender Distribution',
            data: [data.male_count, data.female_count],
            backgroundColor: [
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
              'rgba(54, 162, 235, 1)',
              'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            title: {
              display: true,
              text: 'Members by Gender',
              font: {
                size: 16
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });

      // Role Chart
      const roleCtx = document.getElementById('roleChart').getContext('2d');
      new Chart(roleCtx, {
        type: 'bar',
        data: {
          labels: ['Students', 'Officers'],
          datasets: [{
            label: 'Role Distribution',
            data: [data.student_count, data.officer_count],
            backgroundColor: [
              'rgba(255, 159, 64, 0.7)',
              'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
              'rgba(255, 159, 64, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            title: {
              display: true,
              text: 'Members by Role',
              font: {
                size: 16
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    }
  });
});
</script>

</body>
</html>
