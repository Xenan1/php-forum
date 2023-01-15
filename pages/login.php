<?php
session_start();

if (empty($_POST)) {
    $content = "
    <form action='' method='POST' class='main__form'>
        <input name='login' placeholder='LOGIN'>
        <input name='password' type='password' placeholder='PASSWORD'>
        <input type='submit' value='LOG IN' class='main__form-submit-button button'>
    </form>
    ";
    if (isset($_SESSION['flash'])) {
        $content .= $_SESSION['flash'];
        unset($_SESSION['flash']);
    }

    $content .= "<a class='main__redirect-button' href='/registration'>I do not have an account</a>";

    return [
        'title' => 'Log In',
        'content' => $content,
        'css' => 'login.css'
    ];
} else {
    $query = "SELECT * FROM users WHERE login = '$_POST[login]'";
    $userSQL = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($userSQL);
    if (empty($user)) {
        $_SESSION['flash'] = '<p class="flash-message">Login or password was not found</p>';
        header('Location: /login');
    }
    $hash = $user['password'];
    $password = $_POST['password'];
    if (!password_verify($password, $hash)) {
        $_SESSION['flash'] = '<p class="flash-message">Login or password was not found</p>';
        header('Location: /login');
    }
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['auth'] = true;
    $_SESSION['status'] = $user['status'];
    $_SESSION['user_id'] = $user['user_id'];
    header('Location: /main');

}

?>