<?php require_once('php/view/indexPlaceholder.php') ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- by Correy WInke -->
  <!-- 10/29/16 -->
  <!-- info to understand the page -->
  <meta name="author" content="Correy Winke">
  <meta name="language" content="english">
  <meta name="description" content="Messager app">
  <meta name="abstract" content="Main page">
  <!-- mobile friendly -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- links to rescoures -->
  <link rel="stylesheet" type="text/css" href="public/dist/myStyle.css">
  <link rel="icon", type="image/x-icon", href="public/images/favicon.ico">
  <link rel="shortcut icon", type="image/x-icon", href="public/images/favicon.ico">
  <title>Home</title>
</head>
<body>
  <main>
    <?php echo contentIndex(); ?>
  </main>
<!-- add the recouse for nav click -->
<script src="public/dist/hello.dart.js" type="text/javascript"></script>
</body>
</html>
