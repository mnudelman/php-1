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
    <link rel="stylesheet" type="text/css" href="styles/task.css">
    <link rel="stylesheet" type="text/css" href="styles/formStyle-1.css">
    <link rel="stylesheet" type="text/css" href="styles/galleryStyle-1.css">
</head>
<body>
<?php
include_once __DIR__ . '/topMenu.php';

?>
<?php
$error = false ;
?>
<div id="contentShow">


    <?php
    include_once __DIR__ . '/galleryService.php';
    $dbSuccessful = include(__DIR__ . '/dbConnect.php');
    if (!$dbSuccessful) {
        die('EXIT');
    }

    ?>
    <?php
    /**
     * из общего списка файлов выбирает отмеченные в форме
     * @param $listFiles - общий список
     * @return array - списокОтмеченных
     */
    function getCheckedList($listFiles) {   // список отмеченных файлов
        $checkedFiles = [] ;     // отмеченные файлы
        if (empty($listFiles)){
            return $checkedFiles ;
        }
        foreach ($listFiles as $imgFile) {  // оставим только отмеченные check-<file>

            $file = $imgFile['file'];
            $file_ = str_replace('.', '_', $file);
            $chkName = 'check-' . $file_;

            if (isset($_POST[$chkName])) {
                $cmtName = 'comment-' . $file_;    // имя поля комментария
                $comment = $_POST[$cmtName];
                $checkedFiles[] = ['file' => $file, 'comment' => $comment];
            }

        }
        return $checkedFiles ;
    }
    ?>


    <?php


    define('NEW_COMMENT','новое!') ;  // комментарий для вновь добавленных изображений
    $imgFiles = [];
    $currentGallery = $_SESSION['currentGallery'];
    $galleryId = $currentGallery['galleryid'];
    $userName = $_SESSION['userName'];
    $userStat = $_SESSION['userStatus'];
    if ($userStat < USER_STAT_USER) {
        $error = true ;
        ?>

        <a href="index.php">У вас нет полномочий для редактирования альбома!</a>

    <?php
    }
    ?>
    <?php
    if (empty($galleryId)) {
        ?>
        <a href="index.php">Выберите альбом и повторите действие</a>
    <?php
    }
    ?>


    <?php
    if (isset($_POST['save'])) {   // сохранить и выйти
// сохраняем только отмеченные по  checkbox
        $imgFiles = getImages($pdo,$galleryId); //
        $fileForSave = getCheckedList($imgFiles) ;
        putImages($pdo,$galleryId, $fileForSave);
    }

    if (isset($_POST['add'])) {   // добавить картинки

        $addImages = []; // ['file' => file,'comment' => comment] -  для загрузки в БД
        $errors = [];
        $filesNorm = filesTransform('pictures');  // преобразовать в нормальную форму

        $dirHeap = $_SESSION['dirStart'] . '/' . $_SESSION['dirPictureHeap'];

        $nLoaded = 0;

        foreach ($filesNorm as $fdes) {
            $name = $fdes['name'];
            $tmpName = $fdes['tmp_name'];
            $error = $fdes['error'];

            if (!0 == $error) {
                $errors[] = "ERROR: Ошибка выбора файла:" . $name . " код ошибки: " . $error . LINE_FEED;
                continue;
            }
            $addImages[] = ['file' => $name, 'comment' => NEW_COMMENT];
            if (doubleLoad($dirHeap, $name)) {
                $errors[] = "INFO: Попытка повторной загрузки файла :" . $name . LINE_FEED;

            }
            $fileTo = $dirHeap . '/' . basename($name);
            if (is_uploaded_file($tmpName)) {
                $res = move_uploaded_file($tmpName, $fileTo);
                $nLoaded++;
            }

        }


        $newOnly = true ;   // блокирует изменение имеющихся в БД
        putImages($pdo,$galleryId, $addImages,$newOnly);    // добавить в БД/обновить комментарий

        foreach ($errors as $errTxt) {
            echo $errTxt;
        }
        echo "Загружено  файлов:" . $nLoaded . LINE_FEED;


    }
    if (isset($_POST['addFrom'])) {   // добавить картинки из буфера
        $newOnly = true ;   // блокирует изменение имеющихся в БД
        $addImages = $_SESSION['galleryBuffer'] ;
        putImages($pdo,$galleryId, $addImages,$newOnly);    // добавить в БД/обновить комментарий
    }





    if (isset($_POST['show'])) {   // просмотр
       header("location: galleryShow.php") ;
    }
    if (isset($_POST['del'])) {   // удалить отмеченные
        $imgFiles = getImages($pdo,$galleryId); //
        $fileForSave = getCheckedList($imgFiles) ;
        delImages($pdo,$galleryId, $fileForSave);
    }

    if (isset($_POST['copyTo'])) {   // копировать отмеченные в буфер
        $imgFiles = getImages($pdo,$galleryId); //
        $_SESSION['galleryBuffer'] = getCheckedList($imgFiles) ;
    }
    $imgFiles = getImages($pdo,$galleryId); // [ galleryid => [
    if (!$error) {
        ?>

        <form action="galleryEdit.php" method="post" enctype="multipart/form-data">

            <table border="4"
                   cellspacing="1"
                   cellpadding=“1” class="galFformEdit">

                <tr>
                    <th>Изображение</th>
                    <th>Комментарий</th>
                    <th>отметка</th>
                </tr>
                <?php
                $dirPict = $_SESSION['dirPictureHeap'];
                if (!empty($imgFiles)) {
                    foreach ($imgFiles as $imgFile) {
                        $file = $imgFile['file'];
                        $comment = $imgFile['comment'];
                        echo '<tr>' . LINE_END;
                        echo '<td>' . LINE_END;
                        echo '<img src=" ' . $dirPict . '/' . $file . '" class="imgGal" name="file-' . $file . '">';
                        echo '</td>' . LINE_END;
                        echo '<td class="comment">' . LINE_END;
                        echo '<input type="text" class="commentGal"
                      name="comment-' . $file . '" value="' . $comment . '"">';
                        echo '</td>' . LINE_END;
                        echo '<td>' . LINE_END;
                        echo '<input type="checkbox" class="checkGal" name="check-' . $file . '">';
                        echo '</td>' . LINE_END;
                        echo '</tr>' . LINE_END;
                    }
                }
                ?>

            </table>
            <br>
            <label>

                Выбор изображения
                <input type="file" name="pictures[]" accept="image/jpeg,image/png" multiple>
            </label>
        <span style="margin-left:52px">
        <button class="btGalEdit" name="add">Добавить в альбом</button>
        </span>
            <?php

            if (!empty($_SESSION['galleryBuffer'])) {
                echo '<button class="btGalEdit" name="addFrom">Добавить из буфера</button>' . LINE_END;
            }
            ?>
            <br>

            <button class="btGalEdit" name="save">Сохранить</button>
            <button class="btGalEdit" name="del">Удалить отмеченные</button>
            <button class="btGalEdit" name="copyTo">Копировать в буфер</button>

            <button class="btGalEdit" name="show">В просмотр</button>
        </form>
    <?php
    }
    ?>
</div>
<!--<div id="footer">


</div> -->
</body>
</html>