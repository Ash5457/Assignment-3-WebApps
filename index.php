<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
  <title>Index</title>
  <!-- include javascript and css-->
  <link rel="stylesheet" href="styles/main.css">
  <script defer src="js/scripts.js"></script>
</head>

<body>
  <header>
    <!--This will be the main heading of the page so users know what page they're on-->
    <h1>Welcome to the Main Page</h1>

    <?php include './includes/nav.php' ?>
  </header>

  <main>
    <h2>My Lists</h2>
    <ul>
      <li><a href="view-item.php">Places I want to go</a> - <a href="edit-item.php"><i
            class="fa-solid fa-pen-to-square"></i></a>
        <button class="btn btn-delete">
          <span class="mdi mdi-delete mdi-24px"></span>
          <span class="mdi mdi-delete-empty mdi-24px"></span>
          <span><i class="fa-solid fa-trash"></i></span>
        </button>
      </li>

      <li>Things I want to eat - <a href="edit-item.php"><i class="fa-solid fa-pen-to-square"></i></a>
        <button class="btn btn-delete">
          <span class="mdi mdi-delete mdi-24px"></span>
          <span class="mdi mdi-delete-empty mdi-24px"></span>
          <span><i class="fa-solid fa-trash"></i></span>
        </button>
      </li>
    </ul>
    <h2>Add New Entry:</h2>
    <form action="#" method="post">
      <div>
        <label for="list_name">List Name:</label>
        <input type="text" id="list_name" name="list_name" required>
      </div>
      <div>
        <label for="list_description">List Description:</label>
        <textarea id="list_description" name="list_description" required></textarea>
      </div>
      <div>
        <label for="public_view">Make List Public?:</label>
        <input type="checkbox" id="public_view" name="public_view">
      </div>
      <input type="submit" value="Make List">
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>