<?php
require './includes/library.php';
// declare error array
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // connect to db
    $pdo = connectDB();
    // delcate defaults
    $name = $_POST['name'] ?? "";
    $gender = $_POST['gender'] ?? "";
    $username = $_POST['username'] ?? "";
    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";
    $confirmPassword = $_POST['confirm_password'] ?? "";
    
    $title = $_POST['list_name'] ?? "";
    $description = $_POST['list_description'] ?? "";
    $public = $_POST['public_view'] ?? "Private";

    // Validate password match
    if ($password !== $confirmPassword) {
      $errors['match'] = true;
        //$error_message = "Password and Confirm Password do not match.";
    } else {
        // Check if username is unique
        $stmt = $pdo->prepare("SELECT id FROM 3420_assg_users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
          $errors['unique'] = true;
          //$error_message = "Username already exists. Please choose another one.";
        } 
      }

        if (count($errors) === 0) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $stmt = $pdo->prepare("INSERT INTO 3420_assg_users (name, gender, username, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $gender, $username, $email, $hashedPassword]);

            $stmt = $pdo->prepare("INSERT INTO 3420_assg_lists (title, description, publicity) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, $public]);
            
            // Redirect to login page
            header("Location: login.php");
            exit();
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
    <title>Register</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Create An Account</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <form action="" method="post">
        <fieldset>
          <legend>Account Information</legend>
          <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div>
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
              <option value="">Please Choose One</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="gnc">Gender Queer/Non-Conforming</option>
              <option value="notsay">Prefer not to say</option>
            </select>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>

          <div class="container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>

          <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
          </div>
          <div>
            <label for="confirm_password">Confirm Password:</label>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              required
            >
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>
        </fieldset>
        <fieldset>
          <legend>Create your first List</legend>
          <div>
            <label for="list_name">List Name:</label>
            <input type="text" id="list_name" name="list_name" required>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>
          <div>
            <label for="list_description">List Description:</label>
            <textarea
              id="list_description"
              name="list_description"
              required
            ></textarea>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>
          <div>
            <label for="public_view">Make List Public?:</label>
            <input type="checkbox" id="public_view" name="public_view">
            <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
          </div>
        </fieldset>
        <input type="submit" value="Create Account">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
