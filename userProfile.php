<?php
session_start();
/**
 * Регистрация нового пользователя
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
    <link rel="stylesheet" type="text/css" href="styles/formStyle.css">
</head>
<body>
<?php
include_once __DIR__ .'/topMenu.php' ;
include_once __DIR__ .'/userService.php' ;
$dbSuccessful = include(__DIR__ . '/dbConnect.php');
if (!$dbSuccessful) {
    die("EXIT") ;
}

?>
<?php

?>
<?php
if (isset($_POST['exit'])){
   header("Location: index.php") ;
}
$errors = [] ;
$successfulRegistration = false ;
$profile = [] ;
$profileError = false ;
if (isset($_GET['edit'])) {  // Изменить существующий профиль
    if (empty($_SESSION['enterSuccessful'])) {
        $profileError = true ;
       echo'<a href="index.php">Не пройдена регистрация.Профиль не доступен!</a>' ;
    }else {      // читаем существующий профиль
        $_SESSION['tmp_profileEditFlag'] = true;
        $userLogin = $_SESSION['userLogin'];
        $profile = getProfile($pdo,$userLogin);
    }
}
if (isset($_POST['exit'])) {    // выйти
    if (isset($_SESSION['tmp_profileEditFlag'])){
        unset($_SESSION['tmp_profileEditFlag']) ;
    }
    header("location: index.php") ;
}
if (isset($_POST['exec'])){    // заполнено

   $profile = profileIni() ;   // массив - список полей

    $login = $_POST['login'] ;
    $password = $_POST['password'] ;
    $profileEditFlag = isset($_SESSION['tmp_profileEditFlag']) ;
    if ( (empty($login) || empty($password)) &&  !$profileEditFlag ) { // при редактировании не учитывается
       $errors[] = 'ERROR: Поля "login", "password" обязательны для заполнения! '.LINE_FEED ;
   }else {
        $userPassw = getUser($pdo,$login) ;
        if ( !$userPassw || $profileEditFlag  ) {    // новый пользователь - это хорошо
            //'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday'
            if ( !$profileEditFlag ) {
                putUser($pdo,$login, md5($password));    // пользователя занести в БД

                $_SESSION['userLogin'] = $login;      // login
                $_SESSION['userName'] = $login;      // login
                $_SESSION['userPassword'] = $password;
                $_SESSION['enterSuccessful'] = true;
                $_SESSION['userStat'] = USER_STAT_USER;
            }else {
                $login = $_SESSION['userLogin'] ;       // login
            }
            $profile['firstname'] = $_POST['firstname'] ;
            $profile['middlename'] = $_POST['middlename'] ;
            $profile['lastname'] = $_POST['lastname'] ;
            $profile['email'] = $_POST['email'] ;
            $profile['sex'] = $_POST['sex'] ;
            $year = $_POST['birthday_year'] ;
            $month = $_POST['birthday_month'] ;
            $day = $_POST['birthday_day'] ;
            $tm = mktime(0,0,0,$month,$day,$year) ;
            $profile['birthday'] = date('c',$tm) ;


            $successfulRegistration = putProfile($pdo,$login,$profile) ;   // profile БД
        } else {   //
            $errors[] = 'ERROR: Введенный "login" зарегистрирован ранее. Измените "login" !' ;
        }
    }
}
?>

<div id="content-1">
    <?php
    foreach ($errors as $errTxt) {
        echo $errTxt.LINE_FEED ;
    }
    ?>
    <?php
    if ($successfulRegistration) {
        if (isset($_SESSION['tmp_profileEditFlag'])){
            unset($_SESSION['tmp_profileEditFlag']) ;
        }
        if (isset($_SESSION['tmp_profileEditFlag'])){
            unset($_SESSION['tmp_profileEditFlag']) ;
        }

      ?>
    <a href="index.php"><strong>Регистрация завершена успешно</strong></a>
    <?php
    } else {   // выводить форму
        ?>
        <?php
        if (!empty($_SESSION['tmp_profileEditFlag'])  ){
            echo '<h3>Ваш профиль</h3>'.LINE_END ;
        } else{
            echo '<h3>Заполните карту регистрации</h3>'.LINE_END ;
        }
        ?>


        <form action="userProfile.php" method="post" class="formColor">

            <label> <span class="label"><strong>Ваша фамилия:</strong></span>
                <input class="field" type="text" name="lastname"
                <?php echo 'value="' . $profile['lastname'] . '"> ' ?>
            </label> <br>

            <label> <span class="label"> <strong>имя:</strong> </span>
                <input class="field" type="text" name="firstname"
                <?php echo 'value="' . $profile['firstname'] . '"> ' ?>
            </label> <br>

            <label> <span class="label"> <strong>Отчество:</strong></span>
                <input class="field" type="text" name="middlename"
                <?php echo 'value="' . $profile['middlename'] . '"> ' ?>
            </label> <br>


            <label> <span class="label"><strong>Эл.почта:</strong>  </span>
                <input class="field1" type="email" name="email"
                <?php echo 'value="' . $profile['email'] . '"> ' ?>
            </label> <br>
            <?php
            if ( !isset($_SESSION['tmp_profileEditFlag']) ) { // при проосмотре профиля login,passw убираю
                // для простоты
                ?>
            <label> <span class="label"><strong>login*:</strong></span>
                <input class="field1" type="text" name="login"
                <?php echo 'value="' . $profile['login'] . '"> ' ?>
            </label> <br>
            </label>    <span class="label"><strong>пароль*:</strong></span>
            <input class="field1" type="password" name="password"
            <?php echo 'value="' . $profile['password'] . '"> ' ?>
            </label> <br>
            <?php
            }
            ?>
            <p><span class="label"><strong>пол:</strong></span>

            <div class="group_item">
                <label>
                    <input type="radio" name="sex" value="m"
                        <?php echo ("m" == $profile['sex']) ? "checked" : '' ; ?>
                        >
                           мужской </label>
                <label>
                    <input type="radio" name="sex" value="w"
                        <?php echo ("w" == $profile['sex']) ? "checked" : ''  ; ?>
                        >
                           женский</label></br>
            </div>
            </p>
            <p><span class="label"><strong>Дата рождения:</strong></span>

            <div class="group_item">
                &nbsp;&nbsp;год:
                <select name="birthday_year">
                    <?php
                    for ($i = 1920; $i <= 2010; $i++) {
                        $selected = ($i == $profile['birthday_year']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $i . '</option>';
                    }
                    ?>
                </select>
                &nbsp;&nbsp;месяц:
                <select name="birthday_month">
                    <?php
                    $monthList = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь',
                        'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
                    foreach ($monthList as $i => $month) {
                        $selected = ($i == $profile['birthday_month']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $month . '</option>';
                    }
                    ?>
                </select>
                &nbsp;&nbsp;день:
                <select name="birthday_day">
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = ($i == $profile['birthday_day']) ? "selected" : '';
                        echo '<option value="' . $i . '"  ' . $selected . '>' . $i . '</option>';
                    }
                    ?>

                </select><br>
            </div>
            <p>
                <strong>Добавьте произвольную информацию о себе:</strong> <br>
        <textarea width=“1000px” height=“150px” name="info" value="дополнительная информация">
            <?php echo $profile['info'] ?>
          </textarea>

            </p>
            <?php
            if (!$profileError) {   // при ошибке кнопки не выводятся
                ?>

                <p><input type="submit" name="exec" value="Отправить">
                    <input type="reset" value="Сбросить">
                    <button name="exit">Прервать</button>
                </p>
            <?php
            }
            ?>

        </form>
    <?php
    }
    ?>
</div>
<div id="footer">



</div>
</body>
</html>