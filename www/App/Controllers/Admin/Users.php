<?php
namespace App\Controllers\Admin;

class Users extends \Core\Controller
{
  protected function before(){
    echo 'lol';
  }

  protected function after() {

  }

  public function indexAction(){
    echo 'Admin index';
  }
}
