<?php
session_start();
require_once 'connection.php';

// Function to handle file upload
function upload_image($file)
{
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        // Check file size
        if ($file["size"] > 500000) {
            echo "Sorry, your file is too large.";
            return false;
        }
        // Allow only JPG format
        if ($imageFileType != "jpg") {
            echo "Sorry, only JPG files are allowed.";
            return false;
        }
        // Upload file
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    } else {
        echo "File is not an image.";
        return false;
    }
}

// Function to get comments for a content item
function get_comments($conn, $content_id)
{
    $query = "SELECT * FROM comments WHERE content_id='$content_id'";
    $result = mysqli_query($conn, $query);

    $comments = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }

    return $comments;
}

// Function to add comment
function add_comment($conn, $content_id, $comment)
{
    $comment = mysqli_real_escape_string($conn, $comment);

    $query = "INSERT INTO comments (content_id, comment) VALUES ('$content_id', '$comment')";
    mysqli_query($conn, $query);
}

// User authentication
function validate_login($conn, $username, $password)
{
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header('Location: content_management.php');
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}

function logout()
{
    unset($_SESSION['user']);
    header('Location: login.php');
    exit();
}

// Content management
function get_content($conn)
{
    $query = "SELECT * FROM content";
    $result = mysqli_query($conn, $query);

    $content = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $content[] = $row;
    }

    return $content;
}

function add_content($conn, $title, $body, $image)
{
    $title = mysqli_real_escape_string($conn, $title);
    $body = mysqli_real_escape_string($conn, $body);

    $query = "INSERT INTO content (title, body, image) VALUES ('$title', '$body', '$image')";
    mysqli_query($conn, $query);
}

function update_content($conn, $id, $title, $body)
{
    $title = mysqli_real_escape_string($conn, $title);
    $body = mysqli_real_escape_string($conn, $body);

    $query = "UPDATE content SET title='$title', body='$body' WHERE id=$id";
    mysqli_query($conn, $query);
}

function delete_content($conn, $id)
{
    $query = "DELETE FROM content WHERE id=$id";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>

    <main>
        <form action="validate.php" method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </main>

    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>
</body>
</html>
