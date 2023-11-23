<?php
require_once "includes/library.php";
$search = $_POST['search'] ?? "";
$errors = array();

if(isset($_POST['submit'])){

  $search = strip_tags($search);
  if($search == ""){
    $errors['search'] = true;
  }

  if (!count($errors)){
    $pdo = connectDB();

    $query = "SELECT * FROM 3420_assg_lists WHERE title LIKE ? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Search</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Search</h1>
      
      <?php include './includes/nav.php' ?>
    </header>
    <div>
      <form id="search-form" class="search-form">
        <input
          type="text"
          class="search_input"
          placeholder="Search for lists in the form"
          name="search_query" 
          value="<?=$search?>"
        />
        <span class ="<?php echo isset($errors['search']) ? "" : "hidden"; ?>">
      You must enter a search 
    </span>
         <!--Added name attribute for the search input ^-->
        <button type="button" class="search_button">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <button type="button" class="feelin_lucky">Feelin Lucky?</button>
      </form>
    </div>

    <div>

    <?php if (isset($_POST['submit']) && !count($errors)) : ?>
      <h2>Searching for matching lists <?= $search; ?></h2>
      <?php if ($stmt->rowCount() <= 0) : ?>
        <p>No Results found</p>
        <?php else : ?>
      <ul>
        <?php foreach ($stmt as $row) :
        ?>
        <li>Lists: <?php echo "$row[title]"; ?> </li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>


      <?php endif ?>
    </div>

    <fieldset>
    <h2>Search Results</h2>
    <div>
      <ul>
        <li><a href="view-item.php">Places I want to go</a></li>
        <li>Things I want to eat</li>
      </ul>
    </div>
  </fieldset>
  <?php include './includes/footer.php' ?>
  </body>
</html>
