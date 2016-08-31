<?php

namespace App\Controllers;

use App\Models\UserModel;
use Core\Session;
use \Core\View;

class HomeController extends \Core\Controller
{
  public function indexAction($args){
    View::renderTemplate('Home/index.html', [
      'name'  =>  'Dave',
      'colours' => ['redh','green', 'blue'],
      'url' => $args['url']
    ]);
  }

  public function loginAction(){

  }

  public function infoAction(){
        phpinfo();
  }

  protected function before(){
    //echo 'before--- ';
    //return false;
  }

  protected function after() {
    //echo ' --after';
  }
}
