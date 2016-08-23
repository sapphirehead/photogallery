<?php
use Models\ {
       Session,
       User,
       SmartyClass,
       ModelsPDOException,
       ModelsException
};

require_once ("../../initialize.php");

$session = new Session();
$message = $session->message();
if ($session->isLoggedIn()) {
  redirectTo("adminindex.php");
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = escapeValue($_POST['username']);
        $password = escapeValue($_POST['password']);
        if (!empty($username) && !empty($password)) {
            $part_sql = 'AND';
            $log_obj = new User();
            $found_user = $log_obj->getAuthenticFromBD($username, $password, $part_sql);
            if ($found_user) {
                $session->login($found_user);
                logAction( __DIR__ . '/../../logs/log.txt', 'Login', "{$found_user->username} logged in.");
                redirectTo("adminindex.php");
            } else {
                throw new ModelsException("Некорректная комбинация логин/пароль.");
            }
        } else {
            throw new ModelsException("Заполните все поля!");
        }
    } else {
        $username = "";
        $password = "";
    }

} catch (ModelsPDOException $e) {
    $session->message($e->getMessage());
    redirectTo("adminindex.php");
} catch (ModelsException $e) {
    $session->message($e->getMessage());
    redirectTo("login.php");
}
/**
 * Block template
 */

$smarty = new SmartyClass();
$smarty->assign('message', $message);
$smarty->assign('username', $username);
$smarty->assign('password', $password);
$smarty->display('C:\OpenServer\domains\photogallery\public\Smartemplates\mytemplates\login.tpl');