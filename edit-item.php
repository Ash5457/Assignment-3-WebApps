<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
if(isset($_GET['id'])){
  $list_id = $_GET['id'];}

$userid = $_SESSION['user_id'];
// declare error array
$errors = array();


// delcare defaults
$title              = $_POST['title'] ?? "";
$description        = $_POST['description'] ?? "";
$status             = $_POST['status'] ?? null;
$details            = $_POST['details'] ?? "";
$proof              = $_FILES['proof'] ?? null;
$rating             = $_POST['rating'] ?? "50";
$comp_date          = $_POST['completionDate'] ?? null;
$public             = $_POST['public_view'] ?? 'Public';



//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();
if (isset($_POST['submit'])) {
  echo 'The form says submit was hit! ';
  if (strlen($title) == 0) {
    $errors['title'] = true;
  }
  if (strlen($description) == 0) {
    $errors['description'] = true;
  }
  $valid_status = ['o', 'p', 'c'];
  if (!in_array($status, $valid_status)) {
    $errors['status'] = true;
  }
  if (strlen($details) === 0) {
    $errors['details'] = true;
  }
    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );
    
    // Get image file extension
    $file_extension = pathinfo($_FILES["proof"]["name"], PATHINFO_EXTENSION);
    $maxsize    = 1500000;
    
    // Validate file input to check if is not empty
    if (!file_exists($_FILES["proof"]["tmp_name"])) {
        $errors['proof'] = true;
    }    // Validate file input to check if is with valid extension
    else if (!in_array($file_extension, $allowed_image_extension)) {
        $errors['prooftype'] = true;
    }    // Validate image file size
    else if (($_FILES["proof"]["size"] >= $maxsize || ($_FILES["proof"]["size"] == 0))) {
        $errors['proofsize'] = true;
    }
    elseif(is_uploaded_file($_FILES["proof"]['tmp_name'])){
          $query = "SELECT a.list_id
          FROM 3420_assg_lists AS a
          LEFT JOIN 3420_assg_users AS b ON b.id = a.user_id
          WHERE b.id = ? AND a.title = ?";
          $uid_stmt = $pdo->prepare($query);
          $uid_stmt->execute([$userid, $title]);
    
          $uniqueID =  $uid_stmt->fetch();
          $path = '/uploads';
          $fileroot = "ListImage";

          //get the original file name for extension, where 'fileToProcess' was the name of the
          //file upload form element
          $filename = $_FILES["proof"]['name'];
          $exts = explode(".", $filename); // split based on period
          $ext = $exts[count($exts)-1]; //take the last split (contents after last period)
          $filename = $fileroot.$uniqueID.".".$ext;  //build new filename
          $newname = $path.$filename; //add path the file name
          echo ' it did stuff!!! ';
        }
      
    // If no errors, update database
    if (count($errors) === 0) {
    // Edit the list in Database`:
    $query = "UPDATE `3420_assg_lists` SET `title` = ?, `description` = ?, `status`= ?, `details`= ?, `image_url`= ?, `completion_date` = ?, `publicity` = ?
    WHERE `list_id` = ? AND `user_id` = ?";
    $edit_stmt = $pdo->prepare($query);
    $edit_stmt->execute([$title, $description, $status, $details, $proof, $comp_date, $public, $list_id, $userid]);

    // Redirect:
   header("Location: edited.php");
    exit;
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
    <form id="edit-form" method="post" action="" enctype="multipart/form-data">
      <fieldset>
        <legend>List Info</legend>
        <div>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" >
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
        </div>
        <div>
        <label for="description">Description:</label>
          <textarea id="description" name="description" ></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
        </div>
      </fieldset>
      <fieldset>
        <legend>Status</legend>
        <div>
          <input type="radio" name="Status" id="onhold" value="o"
          <?= $status == 'o' ? 'checked' : '' ?>>
          <label for="onhold">On Hold</label>
        </div>
        <div>
          <input type="radio" name="Status" id="progressing" value="p"
          <?= $status == 'p' ? 'checked' : '' ?>>
          <label for="progressing">In Progress</label>
        </div>
        <div>
          <input type="radio" name="Status" id="complete" value="c"
          <?= $status == 'c' ? 'checked' : '' ?>>
          <label for="complete">Completed</label>
        </div>
        <span class="error <?= !isset($errors['status']) ? 'hidden' : '' ?>">Please Choose List Status.</span>
      </fieldset>

      <fieldset>
        <legend>Validation</legend>
        <div>
          <label for="details">Details:</label>
          <textarea id="details" name="details"></textarea>
          <span class="error <?= !isset($errors['details']) ? 'hidden' : '' ?>">Please Describe Your Entry.</span>
        </div>
        <div>
          <label for="proof">Proof (Image upload):</label>
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
          <input type="checkbox" id="public_view" name="public_view" value="Public" checked>
        </div>
      </fieldset>
      <button id="submit" name="submit">Update</button>
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>
