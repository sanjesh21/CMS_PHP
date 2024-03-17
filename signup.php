<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "User registered successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Signup</title>
</head>
<body>
    <header><h1>Signup</h1></header>
    <main>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </main>
    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>
</body>
</html>
