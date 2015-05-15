<?php
session_start();
/**
 * Редактировать галерею(альбом)
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
    <link rel="stylesheet" type="text/css" href="./styles/task.css">
    <link rel="stylesheet" type="text/css" href="./styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="./styles/galleryStyle-1.css">
</head>
<body>
<?php
include_once __DIR__ . '/topMenu.php';
include_once __DIR__ . '/galleryService.php';
include_once __DIR__ .'/topMenu.php' ;
$dbSuccessful = include(__DIR__ . '/dbConnect.php');
if (!$dbSuccessful) {
    die('EXIT');
}

$imgFiles = [];
$currentGallery = $_SESSION['currentGallery'];
$galleryId = $currentGallery['galleryid'];
$userName = $_SESSION['userName'] ;
$userStat = $_SESSION['userStatus'] ;
$imgFiles = getImages($pdo,$galleryId); // [ galleryid => [
$dirPict = $_SESSION['htmlDir'].'/pictureHeap';
echo 'imgFiles:'.LINE_FEED ;
var_dump($imgFiles) ;
?>
<div id="contentShow">
    <?php
    if (false === $imgFiles) {

    }else {
        foreach ($imgFiles as $imgFile) {
            $file = $imgFile['file'];
            $comment = $imgFile['comment'];
            echo '<div class="imgBlock">' . LINE_END;
            echo '<img src="' . $dirPict . '/' . $file . '" class="imgGal" title="'.$file.'" alt="'.$file.'" >' . LINE_END;
            echo '<div >' . $comment . '</div>' . LINE_END;
            echo '</div>';
        }
    }
    ?>

</div>
</body>
</html>