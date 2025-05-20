<?php
session_start();
include("connection.php");

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Compare password (plain check - use password_verify for hashed)
        if ($password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        } else {
            echo '<script>
                alert("Invalid password");
                window.location.href = "adminLogin.php";
            </script>';
        }
    } else {
        echo '<script>
            alert("Username not found");
            window.location.href = "adminLogin.php";
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CCTE THIRD YEAR SYSTEM - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="loginSignup.css" />
</head>
<body>
    <div class="background"></div>

    <div class="container" id="signIn">
        <h1 class="form-title">T R I C O M</h1>

        <form method="post" action="adminLogin.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="" required />
                <label for="username">Admin</label>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="" required />
                <label for="password">Password</label>
            </div>

            <button type="submit" name="submit" class="btn" id="submitSignIn">Log In</button>
        </form>
    </div>

    <script>
  document.addEventListener('mousemove', (e) => {
    const bg = document.querySelector('.background');
    const x = (e.clientX / window.innerWidth - 0.5) * 30; // adjust 30 for stronger effect
    const y = (e.clientY / window.innerHeight - 0.5) * 30;
    bg.style.transform = `translate(-50%, -50%) translate(${x}px, ${y}px)`;
  });
</script>
</body>
</html>
