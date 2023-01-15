<?php
session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['flash'] = 'Please sign in first';
    header('Location: /login');
}
$topic_id = $params['topicSlug'];

$query = "SELECT * FROM topics WHERE topic_id = $topic_id";
$topic = mysqli_fetch_assoc(mysqli_query($link, $query));

$query = "SELECT * FROM users WHERE user_id = $topic[user_id]";
$creatorUser = mysqli_fetch_assoc(mysqli_query($link, $query));

$content = "<a class='main__redirect-button' href='/main'>Return</a>";

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

return [
    'title' => $topic['topic_head'] ,
    'content' => $content
];
?>