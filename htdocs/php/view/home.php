<?hh
  require('vendor/hh_autoload.php');
  require_once('php/view/indexPlaceholder.php');
  require('php/controller/defense.php');
  require('php/view/head.php');
  require('php/view/footer.php');

  function indexPage() {
    $contentIndex = contentIndex();
    $head = headData("Home");
    $footer = footerData();
    return (<html lang="en">
        {$head}
        <body id="myBody">
            <main>
              {$contentIndex}
              {$footer}
              </main>
          </body>
        </html>);
      
}