<?php
session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['flash'] = 'Please sign in first';
    header('Location: /login');
}

if ($_SESSION['status'] != 'admin') {
    header('Location: /main');
}

if (!empty($_POST)) {
    foreach ($_POST as $id => $command) {
        switch ($command) {
            case 'Block':
                $query = "UPDATE users SET status = 'blocked' WHERE user_id = $id";
                break;
            case 'Make moderator':
                $query = "UPDATE users SET status = 'moderator' WHERE user_id = $id";
                break;
            case 'Remove moderator':
                $query = "UPDATE users SET status = 'user' WHERE user_id = $id";
                break;
            case 'Unblock':
                $query = "UPDATE users SET status = 'user' WHERE user_id = $id";
                break;
        } 
        mysqli_query($link, $query);
        unset($_POST);
        header('Location: /admin');
    }
        

}

    #List of users

$query = "SELECT * FROM users WHERE status = 'user'";
$usersSQL = mysqli_query($link, $query);
for ($users = []; $user = mysqli_fetch_assoc($usersSQL); $users[] = $user);

$content = "<a href='/main' class='button main__return-button'>Return</a>";

$content .= '<p>Users:</p>';
$content .= '<ul class="main__users-list">';

foreach ($users as $user) {
    $content .= "<li class='main__user'><span class='main__user-name'>$user[login]</span><form action='' method='POST' class='main__admin-form'><input type='submit' value='Block' name='$user[user_id]' class='button main__admin-submit'><input type='submit' value='Make moderator' name='$user[user_id]' class='button main__admin-submit'></form></li>";
}

$content .= '</ul>';

    #List of blocked users

$query = "SELECT * FROM users WHERE status = 'blocked'";
$usersSQL = mysqli_query($link, $query);
for ($users = []; $user = mysqli_fetch_assoc($usersSQL); $users[] = $user);

$content .= '<p>Blocked:</p>';
$content .= '<ul class="main__users-list">';

foreach ($users as $user) {
    $content .= "<li class='main__user'><span class='main__user-name'>$user[login]</span><form action='' method='POST' class='main__admin-form'><input type='submit' value='Unblock' name='$user[user_id]' class='button' main__admin-submit></form></li>";
}

$content .= '</ul>';

    #List of moderators

$query = "SELECT * FROM users WHERE status = 'moderator'";
$usersSQL = mysqli_query($link, $query);
for ($users = []; $user = mysqli_fetch_assoc($usersSQL); $users[] = $user);

$content .= '<p>Moderators:</p>';
$content .= '<ul class="main__users-list">';

foreach ($users as $user) {
    $content .= "<li class='main__user'><span class='main__user-name'>$user[login]</span><form action='' method='POST' class='main__admin-form'><input type='submit' value='Block' name='$user[user_id]' class='button main__admin-submit'><input type='submit' value='Remove moderator' name='$user[user_id]' class='button main__admin-submit'></form></li>";
}

$content .= '</ul>';

return [
    'title' => 'Admin',
    'content' => $content,
    'css' => 'admin.css'
];



?>