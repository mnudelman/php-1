<?php
/**
 * Ввод переменных сессии
 * Created by PhpStorm.
 * User: mnudelman@yandex.ru
 * Date: 27.04.15
 * Time: 16:38
 */

define("LINE_FEED", "<br>");
define("LINE_END", "\n");
define("COOKIE_LIFE_TIME",3600) ; // время жизни куки для автоАвторизации
define("MAX_LOGIN",5) ;           // Max число попыток подключения
define("ADMIN_LOGIN","ADMIN") ;
/** статус определяет функциональные возможности */
define("USER_STAT_ADMIN",99) ;     // создание новых разделов, групповое добавление картинок
define("USER_STAT_USER",10) ;      // добавление картинок по одной
define("USER_STAT_GUEST",5) ;      // только просмотр

define("GALLERY_STAT_SHOW",1) ;    // только просмотр
define("GALLERY_STAT_EDIT",2) ;    // редактирование

$sessionVarList = ['dirStart',       // стартовый директорий
                   'htmlDir' ,       // относительный адрес по отношению к host
                   'dirPictureHeap', // куча картинок
                   'userList',       // массив - список зарегистрированных пользователей
                   'userName',       // Имя пользователя
                   'userLogin',      // login
                   'userPassword',   // пароль
                   'userStatus',     // статус пользователя (определяет доступные операции)
                   'currentGallery', // текущая галерея ['id'=> ,'name' =>,'owner'=>,'editStat'
                   'imgFileExt',     // допустимые расширения графических файлов
                   'enterSuccessful', // успешный вход
                   'dbDataBase',      // имя схемы БД
                   'dbUserName',      //
                   'dbPassword',      //
                   'dbPDO',            // объект PDO через который выполнено подключение к хостуБД
                   'sqlLines'
] ;

foreach ($sessionVarList as $var ) {
    if ( !isset($_SESSION[$var] ) ) {
        $_SESSION[$var] = false ;
    }

}
if (empty( $_SESSION['userName'] )){
    $_SESSION['userName'] = 'Гость' ;
    $_SESSION['userLogin'] = 'guest' ;
    $_SESSION['userPassword'] = '12345' ;
    $_SESSION['userStatus'] = USER_STAT_GUEST ;
}

if (empty( $_SESSION['dirStart'])) {
    $_SESSION['dirStart'] = __DIR__ ;
}
if (empty( $_SESSION['htmlDir'])) {
    $pi = pathinfo($_SERVER['PHP_SELF']) ;
    $_SESSION['htmlDir'] = $pi['dirname'] ;
}
$_SESSION['dirPictureHeap'] = 'pictureHeap' ;
if (empty($_SESSION['imgFileExt'])) {
    $_SESSION['imgFileExt'] = ['png','jpg','gif','jpeg'] ;
}
$currentGalary = $_SESSION['currentGallery'] ;
if (!$currentGalary){
    $currentGalary = ['id' => false,'name' => false,'owner' => false,'editStat' => false] ;

}