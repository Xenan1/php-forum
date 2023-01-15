<?php

session_start();

if (empty($_POST)) {
    $content = "
    <form action='' method='POST' class='main__form'>
        <input name='login' placeholder='LOGIN' class='main__form-login'>
        <input name='password' type='password' placeholder='PASSWORD' class='main__form-password'>
        <input name='confirm_password' type='password' placeholder='CONFIRM PASSWORD' class='main__form-password'>
        <input type='submit' value='OK' class='button main__form-submit-button'>
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
        'content' => $content,
        'css' => 'registration.css'
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