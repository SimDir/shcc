<?php

require_once 'common.php';
Auth\Session::grantAccess();
httpResponse::addHeader('<script src="/js/controllers.js"></script>');
httpResponse::showHtmlHeader('Панель управления');
chdir('../custom/dashboard/');
include_once '../functions.php';
$page=filter_input(INPUT_GET,'page');
if(!$page) {
    if(file_exists('index.php')) {
        require 'index.php';        
    } else {
        require 'index.sample.php';
    }
} else {
    if(preg_match('/^[a-zA-Z0-9-_]*$/', $page)) {        
        if(file_exists($page.'.php')) {
            require $page.'.php';
        } else {
            echo "<p>Страницы не существует</p>";
        }
    } else {
        echo "<p>Неверное имя страницы</p>";        
    }
}
httpResponse::showHtmlFooter();