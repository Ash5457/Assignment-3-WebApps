<?php
// makes sure the user is logged in before they can edit something
session_start();
if (!isset($_SESSION['username'])){
header("Location: login.php");
exit();
}


// declare error array
$errors = array();

// set default values
$title  = $_POST['title'] ?? "";
$description  = $_POST['description'] ?? "";
$status  = $_POST['status'] ?? null;
$details  = $_POST['details'] ?? "";
$proof    = $_FILES['proof'] ?? "";
$rating   = $_POST['rating'] ?? "0";
$comp_date  = $_POST['completionDate'] ?? 0000-00-00;
$public   = $_POST['public_view'] ?? "Private";

//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();

//validate the form
if (isset($_POST['submit'])) {

  if (strlen($title) === 0) {
    $errors['title'] = true;
  }

  if (strlen($description) === 0) {
      $errors['description'] = true;
  }

  $valid_status = ['onhold', 'progressing', 'complete'];
  if (!in_array($status, $valid_status)) {
    $errors['perspective'] = true;
  }

  if (strlen($details) === 0) {
    $errors['details'] = true;
  }
  if (isset($_FILES[$proof])){
  // Check for corruption
  if(!isset($_FILES[$proof]['error']) || is_array($_FILES[$proof]['error'])) {
    $errors['proof'] = true;
  }
  // Check if anything is saved
  elseif (strlen($proof) === 0) {
    $errors['proof'] = true;
  } // Check if any errors occured
  elseif ($_FILES[$proof]['error'] > 0) {
    $errors['prooferror'] = true;
    }
  } // Make sure file is within the desired size
  elseif ($_FILES[$proof]['size'] > 1500000) {
    $errors['proofsize'] = true;
  }

    // Check the File type
  elseif (exif_imagetype( $_FILES[$proof]['tmp_name']) != IMAGETYPE_JPEG
     and exif_imagetype( $_FILES[$proof]['tmp_name']) != IMAGETYPE_PNG){
      $errors['prooftype'] = true;
  }

  elseif(is_uploaded_file($_FILES[$proof]['tmp_name'])){
      $query = "SELECT a.list_id
      FROM 3420_assg_lists AS a
      LEFT JOIN 3420_assg_users AS b ON b.id = a.user_id
      WHERE b.username = ? AND a.title = ?";
      $uid_stmt = $pdo->prepare($query);
      $uid_stmt->execute([$username, $title]);

      $uniqueID =  $uid_stmt->fetch();
      $path = '/uploads';
      $fileroot = "ListImage";
  }
      //get the original file name for extension, where 'fileToProcess' was the name of the
      //file upload form element
      $filename = $_FILES[$proof]['name'];
      $exts = explode(".", $filename); // split based on period
      $ext = $exts[count($exts)-1]; //take the last split (contents after last period)
      $filename = $fileroot.$uniqueID.".".$ext;  //build new filename
      $newname = $path.$filename; //add path the file name
    
    }
  
    // If no errors, update database
    if (count($errors) === 0) {
    // Edit the list in Database`:
    $getid = "SELECT id FROM 3420_assg_lists WHERE `user_id` = ?";
    $getid_stmt = $pdo->prepare($getid);
    $getid_stmt ->execute([$uniqueID]); // idk if I did this part right
    $query = "UPDATE `3420_assg_lists` SET `title` = ?, `description` = ?, `status`= ?, `details`= ?, `image_url`= ?, `completion_date` = ?, `publicity` = ?
    WHERE `id` = ? AND `user_id` = ?";
    $edit_stmt = $pdo->prepare($query);
    $edit_stmt->execute([$title, $description, $status, $details, $proof, $comp_date, $public, $getid_stmt, $uniqueID]);

    // Redirect:
    header("Location: edited.php");
    exit;
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
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea id="description" name="description">
Bucket List Item Description</textarea
          >
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
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

      <span class="error <?= !isset($errors['status']) ? 'hidden' : '' ?>">Please Choose List Status.</span>

      <fieldset>
        <legend>Validation</legend>
        <div>
          <label for="details">Details:</label>
          <textarea id="details" name="details"></textarea>
          <span class="error <?= !isset($errors['details']) ? 'hidden' : '' ?>">Please Describe Your Entry.</span>
        </div>
        <div>
          <label for="proof">Proof (Image upload):</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="1500000">
          <input type="file" id="proof" name="proof">
          <span class="error <?= !isset($errors['proof']) ? 'hidden' : '' ?>">Please Upload Your File.</span>
          <span class="error <?= !isset($errors['prooferror']) ? 'hidden' : '' ?>">Something Went Wrong With The Image.</span>
          <span class="error <?= !isset($errors['proofsize']) ? 'hidden' : '' ?>">The File Is Too Large (Max 1.5MB).</span>
          <span class="error <?= !isset($errors['prooftype']) ? 'hidden' : '' ?>">The File Is The Wrong Format (PNG, JPG, JPEG).</span>
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
