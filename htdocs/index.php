<?hh
  require('vendor/hh_autoload.php');
  require('php/view/home.php');
  require('php/view/groups.php');
  require('php/view/posts.php');
  // require('php/controller/action.php');

  $router = new \Bramus\Router\Router();
  $router->get('/', function() {
    echo indexPage();
  });

  $router->get('/groups', function() {
    echo mainPage();
  });

  $router->get('/posts', function() {
    echo master();
  });
  /* $router->get('/controller', function() {
      main();
  }); */
  $router->run();
