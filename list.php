<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Public Preview</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Public Preview</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <h2>User Lists</h2>
      <ul>
        <li><a href="view-item.php">Places I want to go</a></li>
        <li>Things I want to eat</li>
      </ul>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
