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
        >
         <!--Added name attribute for the search input ^-->
        <button type="button" class="search_button">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <button type="button" class="feelin_lucky">Feelin Lucky?</button>
      </form>
    </div>
    <?php
    // Check if a search query is present in the URL
    if (isset($_GET['search_query'])) {
        // Perform your filtered query here
        $search_query = $_GET['search_query'];
        // You can modify the query based on your database structure and fields
        // For example, if you are searching in a table named 'lists' and a column named 'list_name'
        // $sql = "SELECT * FROM lists WHERE list_name LIKE '%$search_query%'";
        
        // Dummy search results for demonstration
        $search_results = [
            'Places I want to go',
            'Things I want to eat',
        ];
    ?>
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
