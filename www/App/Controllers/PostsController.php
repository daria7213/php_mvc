<?php

namespace App\Controllers;

use \Core\View;
use App\Models\PostModel;

class PostsController extends \Core\Controller
{
  public function indexAction($args){
    $posts = PostModel::getAll();
    View::renderTemplate('Posts/index.html', [
        'posts'=>$posts,
        'url' => $args['url']
    ]);
  }

  public function addNewAction() {
    echo "Hello addNew in Posts";
  }

  public function editAction(){
    echo 'edit posts';
    echo '<p>route params:</p><pre>'.htmlspecialchars(print_r($this->route_params,true)).'</pre>';
  }
}
