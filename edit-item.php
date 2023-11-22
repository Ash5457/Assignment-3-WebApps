<?php
// declare error array
$errors = array();

// set default values
$title  = $_POST['title'] ?? "";
$description  = $_POST['description'] ?? "";
$status  = $_POST['status'] ?? null;
$details  = $_POST['details'] ?? "";
$proof    = $_FILES['proof'] ?? "No Image Uploaded.";
$rating   = $_POST['rating'] ?? "0";
$comp_date  = $_POST['completionDate'] ?? 0000-00-00;
$public   = $_POST['public_view'] ?? "Private";

//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();


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
    <title>Edit Item</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Edit Bucket List Items</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <form id="edit-form" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>List Info</legend>
        <div>
          <label for="title">Title:</label>
          <input
            type="text"
            id="title"
            name="title"
            value="Bucket List Item Title"
          >
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea id="description" name="description">
Bucket List Item Description</textarea
          >
        </div>
      </fieldset>
      <fieldset>
        <legend>Status</legend>
        <div>
          <input type="radio" name="Status" id="onhold" value="o">
          <label for="onhold">On Hold</label>
          <?= $status == 'onhold' ? 'checked' : '' ?>
        </div>
        <div>
          <input type="radio" name="Status" id="progressing" value="p">
          <label for="progressing">In Progress</label>
          <?= $status == 'progressing' ? 'checked' : '' ?>
        </div>
        <div>
          <input type="radio" name="Status" id="complete" value="c">
          <label for="complete">Completed</label>
          <?= $status == 'complete' ? 'checked' : '' ?>
        </div>
      </fieldset>
      <fieldset>
        <legend>Validation</legend>
        <div>
          <label for="details">Details:</label>
          <textarea id="details" name="details"></textarea>
        </div>
        <div>
          <label for="proof">Proof (Image upload):</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="1500000">
          <input type="file" id="proof" name="proof">
        </div>
      </fieldset>
      <fieldset>
        <legend>Completed</legend>
        <div>
          <label for="rating">Score:</label>
          <input type="range" id="rating" name="rating" min="1" max="100">
          <output for="rating"></output>
        </div>
        <div>
          <label for="completionDate">Completion Date:</label>
          <input type="date" id="completionDate" name="completionDate">
        </div>
      </fieldset>
      <fieldset>
        <legend>Options</legend>
        <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private">
          <input type="checkbox" id="public_view" name="public_view" value ="Public" checked>
        </div>
      </fieldset>
      <input type="submit" value="Submit">
    </form>
    <?php include './includes/footer.php' ?>
  </body>
</html>
