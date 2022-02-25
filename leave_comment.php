<html>

<?php
$db = new PDO('sqlite:db/weblog.sqlite3');

if (isset($_POST["post_id"], $_POST["body"], $_POST["name"])) {
    $post_id = $_POST["post_id"];
    $body = $_POST["body"];
    $author = $_POST["name"];

    $stmt = $db->prepare("INSERT INTO comments (post_id, body, author) VALUES (:post_id, :body, :author)");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':body', $body);
    $stmt->bindParam(':author', $author);

    $stmt->execute();
}
?>

<head>
    <title>Leave a Comment</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<h1>Your Web Journal</h1>
<div class="leave-comment">

    <?php
    if (isset($_GET["post_id"])) {
        $curr_post_id = $_GET["post_id"];

        $get_stmt = $db->prepare("SELECT
slug as post_slug,
title as post_title,
curr_post.body as post_body,
comments.id as comment_id,
comments.body as comment_body,
comments.author as comment_author
FROM
(
    SELECT
    *
    FROM posts
    WHERE id = $curr_post_id
) curr_post
LEFT JOIN comments ON curr_post.id = comments.post_id
ORDER BY comments.id");

        $get_stmt->execute();
        $post_comments_data = $get_stmt->fetchAll();

        $curr_post_slug = htmlspecialchars($post_comments_data[0][0]);
        $curr_post_title = htmlspecialchars($post_comments_data[0][1]);
        $curr_post_body = htmlspecialchars($post_comments_data[0][2]);

        if (isset($post_comments_data[0][3])) {
            $comments_count = sizeof($post_comments_data);
        } else {
            $comments_count = 0;
        }

        $post_html_script = <<<EOD
<h2>
    Leave a Comment on
    <a href="weblog.php#$curr_post_slug">$curr_post_title</a>
</h2>

<div class="post-body">
    $curr_post_body
</div>

<h3>$comments_count Comments</h3>
    <div class="comment-block">
EOD;

        echo $post_html_script;

        if (isset($post_comments_data[0][3])) {
            foreach ($post_comments_data as $comments) {
                $comment_body = htmlspecialchars($comments[4]);
                $comment_author = htmlspecialchars($comments[5]);

                $comment_html_script = <<<EOD
<div class="comment">
    <div class="comment-body">
        $comment_body
    </div>
    <div class="comment-author">
        $comment_author
    </div>
</div>
EOD;
                echo $comment_html_script;
            }

        }
        echo "</div>";

    }
    ?>

    <form method="post">
        <label for="body">Comment</label>
        <textarea name="body"></textarea>
        <label for="name">Your name</label>
        <input name="name"></input>
        <input type="hidden" name="post_id" value=<?php if (isset($_GET["post_id"])) {
            echo $_GET["post_id"];
        } ?>></input>
        <input type="submit" name="submit" value="Leave Comment"></input>
    </form>
</div>
</body>
</html>
