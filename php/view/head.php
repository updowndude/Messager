<?php
  function headData($strNewTitle) {
    return "
    <!-- by Correy WInke -->
    <!-- 10/31/16 -->

    <!doctype html>
    <html lang=\"en\">
    <head>
      <meta charset=\"UTF-8\">
      <!-- by Correy WInke -->
      <!-- info to understand the page -->
      <meta name=\"author\" content=\"Correy Winke\">
      <meta name=\"language\" content=\"english\">
      <meta name=\"description\" content=\"Bootstrap assigments\">
      <meta name=\"abstract\" content=\"Main page\">
      <meta http-equiv=\"refresh\" content=\"30\"> 
      <!-- mobile friendly -->
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
      <!-- links to rescoures -->
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../public/dist/myStyle.css\">
      <link rel=\"icon\" type=\"image/x-icon\" href=\"../../public/images/favicon.ico\">
      <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"../../public/images/favicon.ico\">
      <title>{$strNewTitle}</title>
    </head>
    <body id=\"myBody\">
      <main>";
  }
?>
