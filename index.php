<?php
session_start();
require_once 'connection.php';

// Function to get content from the database
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

// Fetch content
$content = get_content($conn);

// Function to fetch comments for a specific content
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
    <title>Content Viewer</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Add Google Font Helvetica -->
    <link href="https://fonts.googleapis.com/css2?family=Helvetica&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }
        .content-preview {
            max-height: 200px; /* Adjust as needed */
            overflow: hidden;
        }
        .read-more {
            cursor: pointer;
            color: blue;
        }
        .full-content {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Content Viewer</h1>
        <a href="index.php">Home</a> <!-- Home button -->
        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </header>

    <main>
        <h2>Content</h2>
        <?php foreach ($content as $item): ?>
            <div>
                <h3><?php echo $item['title']; ?></h3>
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                <div class="content-preview"><?php echo substr($item['body'], 0, 200); ?></div>
                <div class="full-content"><?php echo $item['body']; ?></div>
                <button class="read-more">Continue Reading</button>
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="" method="post">
                        <input type="hidden" name="content_id" value="<?php echo $item['id']; ?>">
                        <input type="text" name="comment" placeholder="Leave a comment...">
                        <button type="submit" name="submit_comment">Post</button>
                    </form>
                    <h4>Comments:</h4>
                    <?php $comments = get_comments($conn, $item['id']); ?>
                    <?php foreach ($comments as $comment): ?>
                        <p><?php echo $comment['comment']; ?></p> <!-- Display comments -->
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>

    <script>
        // Toggle full content on "Continue Reading" click
        const readMoreButtons = document.querySelectorAll('.read-more');
        readMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const contentPreview = this.parentElement.querySelector('.content-preview');
                const fullContent = this.parentElement.querySelector('.full-content');
                contentPreview.style.display = 'none';
                fullContent.style.display = 'block';
                this.style.display = 'none';
            });
        });
    </script>
</body>
</html>
