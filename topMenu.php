<?php
session_start();
/**
 *  Меню
 */
?>
<div id="topMenu">
    <strong>ШП. PHP-1.Занятие -7</strong> <br>

    <a href="<?php echo $htmlDir ?>/gallerySelect.php" class="menu">
        <img src="<?php echo $htmlDir ?>/images/folder-image.png" title="Альбом(владелец : имя)" alt="Альбом">
        <?php
        $currentG = $_SESSION['currentGallery'];

        $gName = $currentG['galleryname'] ;
        $owner = $currentG['owner'] ;
        echo  ( empty($gName)) ? 'альбом не выбран' : $owner,':'.$gName ;
        ?>
    </a>&nbsp;&nbsp;

    <a href="<?php echo $htmlDir ?>/userLogin.php" class="menu">
        <img src="<?php echo $htmlDir ?>/images/people.png"
             title="пользователь" alt="пользователь">
        <?php
           echo $_SESSION['userName'] ;
        ?>
    </a> &nbsp;&nbsp;
    <a href="<?php echo $htmlDir ?>/about.php" class="menu">
        <img src="<?php echo $htmlDir ?>/images/help-about.png" title="about" alt="about"></a>

</div>
&nbsp;&nbsp;
