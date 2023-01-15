<?php

session_start();

if (empty($_POST)) {
    $content = "
    <form action='' method='POST'>
        <input name='login' placeholder='LOGIN'>
        <input name='password' type='password' placeholder='PASSWORD'>
        <input name='confirm_password' type='password' placeholder='CONFIRM PASSWORD'>
        <input type='submit' value='OK'>
    </form>
    ";
    if (isset($_SESSION['flash'])) {
        $content .= $_SESSION['flash'];
        unset($_SESSION['flash']);
    }

    $content .= "
        <a class='main__redirect-button' href='/login'>I already have an account</a>
    ";

    return [
        'title' => 'Registration',
        'content' => $content
    ];
} else {
    require '../php-forum/validation.php';

    if (isValidUser($_POST['login'], $_POST['password'], $_POST['confirm_password'], $link)) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (login, password, status) VALUES ('$_POST[login]', '$password', 'user')";
        mysqli_query($link, $query) or die(mysqli_error($link));
        unset($_POST);
        header('Location: /login');

    } else {
        header('Location: /registration');
    }
}

?>