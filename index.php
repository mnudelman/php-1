<?php
session_start();
/**
 *
 */
?>
<?php
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
  include_once __DIR__ .'/topMenu.php' ;
$dbSuccessful = include(__DIR__ . '/dbConnect.php');

if (!$dbSuccessful) {
    die('EXIT');
}
?>
<div id="content">


</div>
<div id="footer">



</div>
</body>
</html>