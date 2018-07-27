<?hh 
  require('vendor/hh_autoload.php');
  require_once('php/view/indexPlaceholder.php');
  require('php/controller/defense.php');
  $contentIndex = contentIndex();
  echo (<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="author" content="Correy Winke"/>
  <meta name="language" content="english"/>
  <meta name="description" content="Messager app"/>
  <meta name="abstract" content="Main page"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="public/dist/myStyle.css" />
  <link rel="icon" type="image/x-icon" href="public/images/favicon.ico" />
  <link rel="shortcut icon" type="image/x-icon" href="public/images/favicon.ico" />
  <title>Home</title>
</head>
<body>
  <main>
    {$contentIndex}
  </main>
<!-- add the recouse for nav click -->
<!-- dartium -->
<script src="public/dist/hello.dart.js" type="text/javascript"></script>
<script type="application/dart" src="dart/hello.dart"></script>
</body>
</html>);
