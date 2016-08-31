<?php
/**
 * Created by PhpStorm.
 * User: Lal
 * Date: 30.06.2016
 * Time: 19:45
 */
namespace App\Controllers;

use App\Models\UserModel;
use Core\Controller;
use Core\View;
use Core\Session;

class UserController extends Controller {

    public function registerAction($args){

        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            View::renderTemplate('User/register.html', [
                'url' => $args['url']
            ]);
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['signup'])){
                $errors = UserModel::checkFields($_POST['name'],$_POST['email'],$_POST['password'],$_POST['cpassword']);
                $success = false;
                if(empty($errors)){
                    if(UserModel::insert($_POST['name'], $_POST['email'], $_POST['password'])){
                        $success = true;
                    }
                }
                View::renderTemplate('User/register.html',[
                    'errors' => $errors,
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'success' => $success,
                    'url' => $args['url']
                ]);
            }
        }
    }

    public function loginAction($args){

        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            View::renderTemplate('User/login.html', [
                'url' => $args['url']
            ]);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['login'])){

                if(!$user = UserModel::get($_POST['email'], $_POST['password'])){
                    View::renderTemplate('User/login.html', [
                        'login_error' => true
                    ]);
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    View::renderTemplate('Home/index.html');
                }





//                echo "<pre>";
//                echo $_POST['email'];
//                //echo UserModel::get($_POST['email'], $_POST['password']);
//                var_dump(UserModel::get($_POST['email'], $_POST['password']));
//                echo "</pre>";
            }
        }
    }

    public function logoutAction(){

        if(isset($_SESSION['user_id'])){
            session_destroy();
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
        }

        View::renderTemplate('Home/index.html');
    }
}