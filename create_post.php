<html>
<head>
    <title>Create a Post</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<h1>Your Web Journal</h1>
<div class="create-post">
    <h2>Create a Post</h2>

    <?php
    $authentic_password = "qwerty123";

    if (isset($_POST["password"])) {
        $password = $_POST["password"];

        if ($password == $authentic_password) {
            $db = new PDO('sqlite:db/weblog.sqlite3');

            $stmt = $db->prepare("INSERT INTO posts (slug, title, body) VALUES (:slug, :title, :body)");
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':body', $body);

            $title = $_POST["title"];
            $slug = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', strtolower($title));
            $body = $_POST["body"];

            $stmt->execute();
        }
    }
    ?>

    <form action="create_post.php" method="post">
        <label for="title">Title</label>
        <input name="title"></input>
        <label for="body">Post Body</label>
        <textarea name="body"></textarea>
        <label for="password">Secret Password</label>
        <input type="password" name="password"></input>
        <input type="submit" name="submit" value="Create Post"></input>
    </form>
</div>
</body>
</html>
