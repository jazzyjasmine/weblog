<?php
$db = new PDO('sqlite:db/weblog.sqlite3');
?>

<html>
<head>
    <title>Web Journal</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<div class="compose-button">
    <a href="create_post.php" title="create post">
        <i class="material-icons">create</i>
    </a>
</div>

<h1>Your Web Journal</h1>

<div id="posts">

    <?php
    $posts_stmt = $db->prepare("SELECT * FROM posts ORDER BY id DESC");
    $posts_stmt->execute();
    $posts_data = $posts_stmt->fetchAll();

    foreach ($posts_data as $post) {
        // for each post, get its id, slug, title and boy
        $curr_post_id = $post[0];
        $curr_post_slug = htmlspecialchars($post[1]);
        $curr_post_title = htmlspecialchars($post[2]);
        $curr_post_body = htmlspecialchars($post[3]);

        // get all comments for a given post
        $comments_stmt = $db->prepare("SELECT * FROM comments WHERE post_id = $curr_post_id ORDER BY id");
        $comments_stmt->execute();
        $comments_data = $comments_stmt->fetchAll();

        // get the number of comments
        $comments_count = sizeof($comments_data);

        // generate the html for post
        $part1 = <<<EOD
<post class="post" id=$curr_post_id>
      <h2 class=post-title id=$curr_post_slug>
        $curr_post_title
        <a href="#$curr_post_slug">
          <i class="material-icons">link</i>
        </a>
      </h2>

      <div class="post-body">
        $curr_post_body
      </div>

      <h3>$comments_count Comments</h3>
      <div class="comment-block">
EOD;

        echo $part1;

        // generate the html for the corresponding comments
        foreach ($comments_data as $comment) {
            $curr_comment_body = htmlspecialchars($comment[2]);
            $curr_comment_author = htmlspecialchars($comment[3]);

            $part2 = <<<EOD
<comment>
      <div class="comment-body">
        $curr_comment_body
      </div>
      <div class="comment-author">
        $curr_comment_author
      </div>
</comment>
EOD;

            echo $part2;
        }

        // generate the html for "leave_comment"
        $part3 = <<<EOD
<a href="leave_comment.php?post_id=$curr_post_id">
          <i class="material-icons">create</i>
          Leave a comment
        </a>
    </div>
</post>
EOD;

        echo $part3;
    }

    ?>

</div> <!-- end of posts block -->
</body>
