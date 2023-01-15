<?php
session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['flash'] = 'Please sign in first';
    header('Location: /login');
}

$topic_id = $params['topicSlug'];

if (!empty($_POST)) {
    if (strlen($_POST['comment_text']) != '') {
        var_dump($_POST['comment_text']);
        unset($_POST);
        header("Location: /main/topic$topic_id");
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO comments (comment_text, user_id, topic_id, comment_date) VALUES ('$_POST[comment_text]', $_SESSION[user_id], $topic_id, '$date')";
        mysqli_query($link, $query);
        unset($_POST);
        header("Location: /main/topic$topic_id");
    }
    
}

$query = "SELECT * FROM topics WHERE topic_id = $topic_id";
$topic = mysqli_fetch_assoc(mysqli_query($link, $query));

$query = "SELECT * FROM users WHERE user_id = $topic[user_id]";
$creatorUser = mysqli_fetch_assoc(mysqli_query($link, $query));

$content = "<a class='main__redirect-button' href='/main'>Return</a>";

if (isset($_SESSION['flash'])) {
    $content .= "<p class='flash-message'>$_SESSION[flash]</p>";
    unset($_SESSION['flash']);
}

$content .= "
        <article class='main__topic'>
        <div class='main__topic-head'>
            <h3 class='main__topic-head-text'>$topic[topic_head]</h3>
            <p class='main_topic-head-user'>$creatorUser[login]</p>
        </div>
        <p class='main__topic-text'>$topic[topic_text]</p>
        <time datetime='$topic[topic_date]' class='main__topic-date'>$topic[topic_date]</time>
        </article>
    ";

$query = "SELECT * FROM comments WHERE topic_id = $topic_id";
$commentsSQL = mysqli_query($link, $query);
for ($comments = []; $comment = mysqli_fetch_assoc($commentsSQL); $comments[] = $comment);

$content .= '<section class="main__comments comments">';

$content .= '
    <p class="comments__title">COMMENTS</p>
    <form method="POST">
        <input name="comment_text" class="main__form comments__form">
        <input type="submit" class="button comments__submit" value="SUBMIT">
    </form>
';

foreach ($comments as $comment) {
    $query = "SELECT * FROM users WHERE user_id = $comment[user_id]";
    $author = mysqli_fetch_assoc(mysqli_query($link, $query));
    $content .= "
        <article class='comments__item'>
            <p class='comments__user'>$author[login]</p>
            <p class='comments__text'>$comment[comment_text]</p>
            <time datetime='$comment[comment_date]' class='comments__date'>$comment[comment_date]</time>
        </article>
    ";
}

$content .= '</section>';



return [
    'title' => $topic['topic_head'] ,
    'content' => $content,
    'css' => 'topic.css'
];
?>