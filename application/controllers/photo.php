<?php
use models\ {
        Session,
        Photograph,
        Comment,
        SmartyClass,
        Rank,
        Pagination,
        ExeptionMy,
        ExeptionPDOMy
};

require_once("../../index.php");
require_once("../models/ExeptionMy.php");

$session = new Session();
$message = $session->message();
try {
    if (!empty($_GET['id'])) {
        $id = $_SESSION['one_ph_id'] = escapeIntValue($_GET['id']);
        Rank::getRankObj($id);
    } elseif(empty($_GET['id'])) {
        $id = $_SESSION['one_ph_id'];
    } else {
        throw new ExeptionMy("Не существует изображения с таким индексом.");
    }
    $photo = Photograph::findById($id);
    if(!$photo) {
        redirectTo('gallery.php');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $author = escapeValue($_POST['author']);
        $body = escapeValue($_POST['body']);

        $new_comment = Comment::make($photo->id, $author, $body);
        $new_comment->saveDB();
        $new_comment->tryToSendNotification();
        redirectTo("gallery.php");

    } else {
        $author = "";
        $body = "";
    }

    $path = $_SERVER['HTTP_REFERER'];

    /**
     * Блок задания параметров пагинации
     */
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;//
    $page = escapeIntValue($page);
    $per_page = 10;
    $total_count = count($photo->forGettingOfNumberComments());
    $pagination = new Pagination($page, $per_page, $total_count);

    $comments = $photo->comments($pagination);

} catch (ExeptionPDOMy $e) {
    $action = " Error on the {$e->getLine()}-lines. Info about";
    $body = "\n{$e->getMessage()}.\nPath: {$e->getFile()}\n\n";
    logAction(LOG_PATH, $action, $body);
    redirectTo('/posts_cap.html');
}catch (ExeptionMy $e) {
    $session->message($e->getMessage());
    $action = " Error on the {$e->getLine()}-lines. Info about";
    $body = "\n{$e->getMessage()}.\nPath: {$e->getFile()}\n\n";
    logAction(LOG_PATH, $action, $body);
    redirectTo('photo.php');
}
/**
 * Block output template
 */

$smarty = new SmartyClass();
$smarty->assign('photo', $photo);
$smarty->assign('path', $path);
$smarty->assign('author', $author);
$smarty->assign('body', $body);
$smarty->assign('message', $message);// тут ошибки комментов их надо показывать юзеру
$smarty->assign('pagination', $pagination);
$smarty->assign('comments', $comments);
$smarty->display('C:\OpenServer\domains\photogallery\application\smartemplates\photo.tpl');