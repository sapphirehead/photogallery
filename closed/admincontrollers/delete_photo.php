<?php
use Models\ {
       Session,
       Photograph,
       ModelsException,
       ModelsPDOException
};

require_once ("../../initialize.php");

$session = new Session();
if (!$session->isLoggedIn()) { redirectTo("login.php"); }
try {
    if (empty($_GET['id'])) {
        throw new ModelsException("ID данного изображения не передан.");
    }
    if (!$photo = Photograph::findById($_GET['id'])) {
        throw new ModelsException("Такого изображения не существует.");
    }
    if ($photo->destroy()) {
        throw new ModelsException("Изображение {$photo->filename} было удалено.");
    }

} catch (ModelsPDOException $e) {
    $session->message($e->getMessage());
    redirectTo("adminindex.php");
} catch (ModelsException $e) {
    $session->message($e->getMessage());
    redirectTo('list_photos.php');
}
