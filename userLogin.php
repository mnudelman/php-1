<?php
session_start();
/**
 *  Меню
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);

include_once __DIR__ . '/sessionVars.php';
$htmlDir = $_SESSION['htmlDir'];

?>
<html>
<head>
    <meta charset="utf-8">
    <title>php-1-lesson-7</title>
    <meta name="description" content="ШП-php-1-lesson_7 ">
    <meta name="author" content="mnudelman@yandex.ru">
    <link rel="stylesheet" type="text/css" href="styles/task.css">
    <link rel="stylesheet" type="text/css" href="styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="styles/galleryStyle.css">
</head>
<body>
<?php
 include_once __DIR__ . '/topMenu.php' ;
$dbSuccessful = include(__DIR__ . '/dbConnect.php');

if (!$dbSuccessful) {
    die('EXIT');
}

include_once __DIR__ . '/userService.php' ; // функции авторизации

?>

<?php
$isGoFlag = true ;
$errors = [] ;
if (isset($_POST['exit'])) {
    header("Location: index.php") ;
}
if (isset($_POST['profile']) ) {
   if( $_SESSION['userStatus'] >= USER_STAT_USER &&
      !empty($_SESSION['userLogin'])) {
      header("Location: userProfile.php?edit=1") ;
   }
}
if (isset($_POST['exec']) || isset($_POST['profile']) ) {
    $login = $_POST['login'] ;
    $password = $_POST['password'] ;
    var_dump($_POST) ;
    if (empty($login) || empty($password)) {
        $errors[] = 'ERROR:Поля "Имя:" и "Пароль:" должны быть заполнены !' ;
    }else {
        $userPassw = getUser($pdo,$login) ;
        if (!$userPassw) { // $login отсутствует в БД
            $errors[] = 'ERROR: Недопустимое имя пользователя.Повторите ввод !' ;
        }else {  // проверяем пароль
            $fromDBPassw = $userPassw['password'] ;
            if ( $fromDBPassw != md5($password)) {
                $errors[] = 'ERROR: Неверный пароль.Повторите ввод !' ;
            }else {
                $_SESSION['userLogin'] = $login ;      // login
                $_SESSION['userName'] = $login ;      // login
                $_SESSION['userPassword'] = $password ;
                $_SESSION['enterSuccessful'] = true ;
                $_SESSION['userStatus'] = USER_STAT_USER ;
                if ('admin' == $login) {
                    $_SESSION['userStatus'] = USER_STAT_ADMIN ;
                }
                header("Location: index.php") ;
            }
        }
    }
}
?>


<div id="content">
    <?php
    foreach ($errors as $erTxt) {
        echo $erTxt.LINE_FEED ;
    }
    ?>
</div>
<div id="footer">
    <?php

    if ( $isGoFlag  ) {
        ?>
        <div>
            ВХОД. Войдите под своим login,password или
            <a href="userProfile.php"> пройдите регистрацию</a><br>
        </div>
        <form action="userLogin.php" method="post">

                <label ><span class="label"><strong>Имя:</strong></span>
                 <input class="field" type="text" name="login"> </label> </br>

                <label><span class="label"> <strong>Пароль:</strong></span>
                <span> <input class="field" type="password" name="password"></label> </br>


            <label>
                <input type="checkbox" name="savePassword" class="bt">Запомнить пароль </label><br>
            <button name="exec" class="bt">ВОЙТИ</button>
            <button name="profile" >ПРОФИЛЬ</button>
            <button name="exit">ПРЕРВАТЬ</button>

        </form>

    <?php
    }         // конец if(числоПопыток)*/
    ?>

</div>
</body>
</html>