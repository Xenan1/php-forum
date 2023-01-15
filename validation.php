<?

/**
 * @param String $pass
 * @return Boolean
 */
function isValidUser($login, $pass, $passConfirm, $link) {
    if (!preg_match('#^[a-zA-Z0-9-_]{6,}$#', $pass)) {
        $_SESSION['flash'] = '<p class="flash-message">Login and password must be over 6 characthers and consist of letters, digits, "-" or "_" symbols</p>';
        return false;
    }
    if (!preg_match('#^[a-zA-Z0-9-_]{4,}$#', $login)) {
        $_SESSION['flash'] = '<p class="flash-message">Login and password must be over 6 characthers and consist of letters, digits, "-" or "_" symbols</p>';
        return false;
    }
    if ($pass != $passConfirm) {
        return false;
    }

    $query = "SELECT * FROM users WHERE login = '$login'";
    $userSQL = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($userSQL);
    if (!empty($user)) {
        $_SESSION['flash'] = '<p class="flash-message">This username already used</p>';
        return false;
    }

    return true;


}

?>