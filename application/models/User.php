<?php
namespace models;

/**
 * Class User
 * @package models
 */
class User extends DBObject
{
    /**
     * @staticvar string
     */
    protected static $table_name="users";
    /**
     * @staticvar array
     */
    protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
    /**
     * @var
     */
    public static $username;
    /**
     * @staticvar
     */
    public static $hash_password;
    /**
     * @var
     */
    public $password;
    /**
     * @var
     */
    public $old_password;
    /**
     * @var
     */
    public $new_password;
    /**
     * @var
     */
    public $first_name;
    /**
     * @var
     */
    public $last_name;
    /**
     * @param string $user
     * @param string $pass
     * @param string $firstname
     * @param string $lastname
     * @return User
     * @throws ExeptionMy
     */
    public function getUserPropertys(string $user, string $pass, string $firstname="", string $lastname=""): User
    {
        if (!empty($user) && !empty($pass)) {
        //солим и хешируем пароль одной функцией по умолчанию, можно и настраивать соль меняя константу
            $salt = "24akjJ0340LJafkri3409jag";
            $options = [
                'cost' => 12,
                'salt' => $salt
            ];
            $reg_obj = new self;
            $reg_obj->id = $_SESSION['user_id'];
            $reg_obj->username = $user;
            $reg_obj->password = password_hash($pass, PASSWORD_BCRYPT, $options);
            $reg_obj->first_name = $firstname;
            $reg_obj->last_name = $lastname;
            return $reg_obj;
        } else {
            throw new ExeptionMy("Заполните все обязательные поля");
        }
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        if (isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . " " . $this->last_name;
        } else {
            return "";
        }
    }
/**
 * @param string $username
 * @param string $password
 * @param $part_sql
 * @return mixed
 * @throws ExeptionMy
 */
public function getAuthenticFromBD(string $username="", string $password="", string $part_sql)
{
        self::$username = $username;
        self::$hash_password = myHash($password);
        $sql  = "SELECT id, username, password FROM users ";
        $sql .= "WHERE BINARY username = :username ";// BINARY делает запрос для этого поля чувствительным к регистру
        //  сделать поле юзер уникальным в ДБ
        $sql.= $part_sql;
        $sql .= " password = :password ";
        $sql .= "LIMIT 1";
        $result_array = self::findBySql($sql);
        if (!empty($result_array)) {
            $cut_array = array_shift($result_array);
        } else {
            return false;//only false
        }
        return $cut_array;
    }
}
