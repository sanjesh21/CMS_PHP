<?php
session_start();
require_once 'connection.php';
require_once 'validate.php';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_content'])) {
        $image = upload_image($_FILES["image"]);
        if ($image) {
            add_content($conn, $_POST['title'], $_POST['body'], $image);
        }
    }

    // Handle adding comments
    if (isset($_POST['submit_comment'])) {
        $content_id = $_POST['content_id'];
        $comment = $_POST['comment'];
        add_comment($conn, $content_id, $comment);
    }
}

// Fetch content
$content = get_content($conn);

// Fetch comments
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS - Content Management</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Anonymous'; ?>!</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </header>

    <main style="display:grid;">
        <h2>Add Content</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/jpeg" required><br>
            <input type="text" name="title" placeholder="Title" required><br>
            <textarea name="body" placeholder="Body" required></textarea><br>
            <button type="submit" name="add_content">Add Content</button>
        </form>

        <h2>Content</h2>
        <?php foreach ($content as $item): ?>
            <div>
                <h3><?php echo $item['title']; ?></h3>
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                <p><?php echo $item['body']; ?></p>
                <form action="" method="post">
                    <input type="hidden" name="content_id" value="<?php echo $item['id']; ?>">
                    <input type="text" name="comment" placeholder="Leave a comment...">
                    <button type="submit" name="submit_comment">Post</button>
                </form>
                <h4>Comments:</h4>
                <?php $comments = get_comments($conn, $item['id']); ?>
                <?php foreach ($comments as $comment): ?>
                    <p><?php echo $comment['comment']; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>
</body>
</html>
