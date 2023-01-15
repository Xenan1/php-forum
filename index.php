<?php
    # ⊗ppPmSDPrm №2

session_start();

require 'connect.php';

$url = $_SERVER['REQUEST_URI'];
$layout = file_get_contents('layout.html');
$header = file_get_contents('html/header.html');
$footer = file_get_contents('html/footer.html');

$route = '/login$';
if (preg_match("#$route#", $url, $params)) {
    $page = require 'pages/login.php';
}

$route = '/registration$';
if (preg_match("#$route#", $url, $params)) {
    $page = require 'pages/register.php';
}

$route = '/main$';
if (preg_match("#$route#", $url, $params)) {
    $page = require 'pages/main.php';
}

$route = '/main/topic(?<topicSlug>[0-9]+)$';
if (preg_match("#$route#", $url, $params)) {
    $page = require 'pages/topic.php';
}

$route = '/logout$';
if (preg_match("#$route#", $url, $params)) {
    unset($_SESSION['auth']);
    header('Location: /login');
}

$route = '/admin$';
if (preg_match("#$route#", $url, $params)) {
    $page = require 'pages/admin.php';
}
$layout = preg_replace('#{{ content }}#', $page['content'], $layout);
$layout = preg_replace('#{{ title }}#', $page['title'], $layout);
$layout = preg_replace('#{{ header }}#', $header, $layout);
$layout = preg_replace('#{{ footer }}#', $footer, $layout);
$layout = preg_replace('#{{ css }}#', $page['css'], $layout);

echo $layout;



?>