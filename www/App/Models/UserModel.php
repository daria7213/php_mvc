<?php
/**
 * Created by PhpStorm.
 * User: Lal
 * Date: 30.06.2016
 * Time: 18:07
 */
namespace App\Models;

use Core\Model;
use PDO;

class UserModel extends Model {

    public static function getAll() {
        try {
            $db = static::getDB();
            $request = $db->query('SELECT id, name, email FROM users ORDER BY id');
            $results = $request->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function insert($name, $email, $password){
        try {
            $db = static::getDB();

            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name,:email,:password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            return true;
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
        return false;
    }

    public static function checkFields($name, $email, $password, $cpassword){
        $errors =[];

        $db = static::getDB();
//        echo "<pre>";
//        var_dump($db);
//        echo "</pre>";
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->fetch()){
            $errors['email_taken_error'] = true;
        }

        if(!preg_match('/^[a-zA-Z0-9 ]+$/',$name)){
            $errors['name_error'] = true;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email_error'] = true;
        }

        if(strlen($password)<6){
            $errors['password_error'] = true;
        }

        if($cpassword != $password){
            $errors['cpassword_error'] = true;
        }
        return $errors;
    }

    public static function get($email, $password){
        try {
            $db = static::getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");

            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch();

            if(password_verify($password, $result['password'])){
                return $result;
            }
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
        return false;
    }
}