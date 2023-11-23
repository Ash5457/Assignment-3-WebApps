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

    //basic form validation
    if (strlen($name) === 0) {
      $errors['name'] = true;
    } 
    if (strlen($gender) === 0) {
      $errors['gender'] = true;
    }
    if (strlen($username) === 0) {
      $errors['username'] = true;
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $errors['email'] = true;
    }
    if (strlen($password) === 0) {
      $errors['password'] = true;
    }
    if (strlen($title) === 0) {
      $errors['title'] = true;
    }
    if (strlen($description) === 0) {
      $errors['description'] = true;
    }

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
            <span class="error <?= !isset($errors['name']) ? 'hidden' : '' ?>">Please Enter a Name.</span>
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
            <span class="error <?= !isset($errors['gender']) ? 'hidden' : '' ?>">Please Choose a Gender.</span>
          </div>

          <div class="container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span class="error <?= !isset($errors['username']) ? 'hidden' : '' ?>">Please Enter a Username.</span>
            <span class="error <?= !isset($errors['unique']) ? 'hidden' : '' ?>">Invalid Username!</span>
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error <?= !isset($errors['email']) ? 'hidden' : '' ?>">Enter a Valid Email.</span>
          </div>

          <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error <?= !isset($errors['password']) ? 'hidden' : '' ?>">Please Enter a Password.</span>
          </div>
          <div>
            <label for="confirm_password">Confirm Password:</label>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              required
            >
            <span class="error <?= !isset($errors['match']) ? 'hidden' : '' ?>">Passwords Do Not Match.</span>
          </div>
        </fieldset>
        <fieldset>
          <legend>Create your first List</legend>
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
          <textarea id="description" name="description"></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
        </div>
          <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private">
          <input type="checkbox" id="public_view" name="public_view" value ="Public" checked>
        </div>
        </fieldset>
        <input type="submit" value="Create Account">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
