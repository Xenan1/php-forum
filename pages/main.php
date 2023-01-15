<?php

session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['flash'] = 'Please sign in first';
    header('Location: /login');
}

if (!empty($_POST)) {

    $date = date('Y-m-d');
    $query = "INSERT INTO topics (topic_head, topic_text, user_id, topic_date) VALUES ('$_POST[topic_head]', '$_POST[topic_text]', $_SESSION[user_id], '$date')";
    mysqli_query($link, $query) or die(mysqli_error($link));
    unset($_POST);
    header('Location: /main');
}

$query = "SELECT * FROM topics ORDER BY topic_date DESC";
$topicsSQL = mysqli_query($link, $query);

for ($topics = []; $topic = mysqli_fetch_assoc($topicsSQL); $topics[] = $topic);

$content = '';

$createTopic = "
    <form action='' method='POST' class='main__form '>
        <input name='topic_head' placeholder='HEAD'>
        <textarea name='topic_text' placeholder='TEXT'></textarea>
        <input type='submit' value='SUBMIT' class='button main__form-submit-button'>
    </form>
";

if (empty($topics)) {
    $content .= '<p>There are no topics, be first!</p>';
} else {
    
    foreach ($topics as $topic) {
        $query = "SELECT * FROM users WHERE user_id = $topic[user_id]";
        $creatorSQL = mysqli_query($link, $query);
        $creatorUser = mysqli_fetch_assoc($creatorSQL);
        $content .= "
            <article class='main__topic'>
                <div class='main__topic-head'>
                    <h3><a class='main__topic-head-text' href='/main/topic$topic[topic_id]'>$topic[topic_head]</a></h3>
                    <p class='main__topic-head-user'>$creatorUser[login]</p>
                </div>
                <time datetime='$topic[topic_date]' class='main__topic-date'>$topic[topic_date]</time>
            </article>
        ";
    }
}

$content .= $createTopic;

return [
    'title' => 'Main page',
    'content' => $content,
    'css' => 'main.css'
];


?>