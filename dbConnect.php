<?php
/**
 * Created by PhpStorm.
 * User: mnudelman@yandex.ru
 * Date: 30.04.15
 * Time: 20:32
 */
$host = 'localhost' ;
$dbname = 'gallery' ;
$user = 'root' ;
$password = 'root' ;
$charset = "utf-8" ;
$dbSuccessful = true ; // успех подключения к БД

// делаем по phpfaq.ru
//$dsn = "mysql:host=$host;dbname=$dbname" ;
// след конструкция не работает почему ???
$dsn = 'mysql:host='.$host.';dbname='.$dbname ;
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC ] ;
//phpinfo();
try {
    $pdo = new PDO($dsn, $user, $password, $opt) ;
}catch (PDOException $e) {
    $dbSuccessful = false ;
    echo 'ERROR:подключение:'.$e->getMessage().LINE_FEED ;


}
/** раз не умерли, надо запомнить */
$_SESSION['dbDataBase'] = $dbname ;
$_SESSION['dbUserName'] = $user ;
$_SESSION['dbPassword'] = $password ;
$_SESSION['dbSuccessful'] = $dbSuccessful ; // успех подключения к БД
return $dbSuccessful ;