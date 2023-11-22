<!--PHP section-->
<?php
session_start(); // Start the session

// Check if the user is already logged in, if so, redirect to the Main Page
if (isset($_SESSION['username'])) {
    header("Location: main-page.php");
    exit();
}

// Check if a cookie exists, if yes, pre-populate the username box
if (isset($_COOKIE['remember_me'])) {
    $prepopulatedUsername = $_COOKIE['remember_me'];
} else {
    $prepopulatedUsername = '';
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user credentials against the database (replace this with your actual validation logic)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example validation logic (replace this with your actual validation logic)
    // $isValid = validateUser($username, $password);
    // For simplicity, we'll assume the validation is successful

    // Dummy validation for demonstration purposes
    $isValid = true;

    if ($isValid) {
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = 1; // Replace with the actual user ID from the database

        // Check if "remember me" is checked, if yes, create a cookie
        if (isset($_POST['remember_me'])) {
            setcookie('remember_me', $username, time() + (86400 * 30), "/"); // 30 days
        }

        // Redirect to the Main Page
        header("Location: main-page.php");
        exit();
    } else {
        // Handle invalid credentials (display an error message, redirect, etc.)
        $error_message = "Invalid username or password";
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
    <title>Login</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Login</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
    <?php
      if (isset($error_message)) {
          echo "<p>$error_message</p>";
      }
      ?>
      No account? You can <a href="register.php">sign up now!</a>
      <form action="login.php" method="post" class="login">
        <fieldset>
          <legend>Login Information</legend>
          <div>
            <label for="username">Username:</label>
            <input
              type="text"
              id="username"
              name="username"
              maxlength="32"
              placeholder="ex. JohnDoe123"
              required
            >
          </div>
          <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
          </div>

          <div>
            <label for="remember_me">Remember me:</label>
            <input type="checkbox" id="remember_me" name="remember_me">
          </div>
        </fieldset>
        <div>
          <a href="forgot.php">Forgot Password?</a>
        </div>
        <input type="submit" value="Login">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>