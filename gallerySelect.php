<?php
session_start();
/**
 * Выбор галереи для просмотра/редактирования
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);

include_once __DIR__ . '/sessionVars.php';
$htmlDir = $_SESSION['htmlDir'];
include_once __DIR__ . '/galleryService.php';

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
include_once __DIR__ .'/topMenu.php' ;
$dbSuccessful = include(__DIR__ . '/dbConnect.php');
if (!$dbSuccessful) {
    die('EXIT');
}
?>
<?php
define('STAT_SHOW_NAME','только просмотр') ;
define('STAT_EDIT_NAME','редактирование') ;
// режим редактирования сохраняется во временной переменной
$galleryEditStat = ( !isset($_SESSION['tmp_galleryEditStat'])) ? GALLERY_STAT_SHOW  :
                      $_SESSION['tmp_galleryEditStat'] ;      // режим редактирования

$currentStat = 'только просмотр ' ;
$currentGallery = ( empty($_SESSION['currentGallery'] ) ? '' : $_SESSION['currentGallery'])  ;   // массив - дескриптор галереи

if (!$_POST && !$_GET) {
    $galleryEditStat = GALLERY_STAT_SHOW ;
}
if (isset($_POST['exit'])) {

    header("Location: ".$htmlDir."/index.php");
}
if (isset($_POST['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
    $statName = $_POST['statName'] ;
    $userName = $_SESSION['userName'];
    $userStat = $_SESSION['userStatus'];
    if ($userStat < USER_STAT_USER) {
        $galleryEditStat = GALLERY_STAT_SHOW;
    }else {
        $galleryEditStat = ($statName == STAT_SHOW_NAME) ? GALLERY_STAT_EDIT : GALLERY_STAT_SHOW ;
    }
    $_SESSION['tmp_galleryEditStat'] = $galleryEditStat ;
}




// строим список доступных галерей
$owner = (GALLERY_STAT_SHOW == $galleryEditStat ) ? '' : $_SESSION['userName'] ;
$galleryList = getGallery($pdo,$owner) ;

if(isset($_POST['currentGalleryId'])){
    $currentGallery = $galleryList[$_POST['currentGalleryId']] ;
    $_SESSION['currentGallery'] = $currentGallery ;

}

if (isset($_POST['goShow'])) {   //   в просмотр
    unset($_SESSION['tmp_galleryEditStat']) ;
    header("Location: ".$htmlDir."/galleryShow.php") ;
}

if (isset($_POST['editGallery'])) {   //   редактировать
    unset($_SESSION['tmp_galleryEditStat']) ;
    header("Location: ".$htmlDir."/galleryEdit.php") ;
}
if (!empty($_POST['addGallery'])) {   //   добавить в список новыйАльбом
   $owner = $_SESSION['userLogin'] ;
   $newG = $_POST['addGallery'] ;
   putGallery ($pdo,$owner,$newG) ;
   $galleryList = getGallery($pdo,$owner) ;
}




?>
<div id="content">

</div>
<div id="footer">
    <?php
    $galleryStatName = ($galleryEditStat == GALLERY_STAT_SHOW) ? STAT_SHOW_NAME : STAT_EDIT_NAME ;
    $editFlag = ($galleryEditStat == GALLERY_STAT_EDIT) ;
    ?>
    <form action="<?php echo $htmlDir ?>/gallerySelect.php" method="post">
        <label>
            <span class="label">текущий режим:</span>
            <input type="text" readonly="readonly" name="statName" class="field"
                value="<?php echo $galleryStatName ?>">
        </label>&nbsp;&nbsp;
        <button name="changeStat" class="btGal">изменить режим</button>
        <br>
        <label>
            <span class="label">выбрать альбом:</span>
            <select name="currentGalleryId" class="field">
                <?php
                $currentGalleryId = (!empty($currentGallery['galleryid'])) ?
                                  $currentGallery['galleryid'] : '' ;
                foreach($galleryList as $gallery) {
                    $owner      = $gallery['owner'] ;
                    $galleryid  = $gallery['galleryid'] ;
                    $galleryName= $gallery['galleryname'] ;
                    $text = $owner.':'.$galleryName ;
                    $selected = ( $galleryid == $currentGalleryId ) ? 'selected' : '' ;
                    echo '<option value="'.$galleryid.'"  '.$selected.' >'.$text.'</option>'.LINE_END ;
                }
                ?>
           <!-- <option value="1" selected >mnudelman:space</option> -->
            </select>
        </label>&nbsp;&nbsp;
        <button name="goShow" class="bt btGal">Просмотр</button>&nbsp;&nbsp;
        <?php
        if ($editFlag) {
            ?>
            <button name="editGallery" class="btGal">Редактировать</button><br>
            <label>
                <span class="label">Новый альбом:</span>
                <input type="text" name="addGallery" class="field">
            </label>&nbsp;&nbsp;
            <button name="addGalleryExec" class="btGal">Добавить</button>
        <?php
        }
        ?>
        <br>
        <div style="margin-left:451px;">
        <button name="exit" class="btGal">Прервать</button
        </div>
    </form>




</div>
</body>
</html>