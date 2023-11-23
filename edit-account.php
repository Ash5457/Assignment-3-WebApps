<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Edit Account</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Edit Your Account</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <?php
      require './includes/library.php';

      // Assuming you have a function to get the user data by user ID
      $pdo = connectDB();
      $user_id = $_SESSION['user_id'];
      $stmt = $pdo->prepare("SELECT * FROM 3420_assg_users WHERE id = ?");
      $stmt->execute([$user_id]);
      $user = $stmt->fetch();

      if ($user) {
      ?>
        <form action="update-account.php" method="post">
          <fieldset>
            <legend>Account Information</legend>
            <div>
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div>
              <label for="gender">Gender</label>
              <select name="gender" id="gender" required>
                <option value="male" <?php echo ($user['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($user['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="gnc" <?php echo ($user['gender'] === 'gnc') ? 'selected' : ''; ?>>Gender Queer/Non-Conforming</option>
                <option value="notsay" <?php echo ($user['gender'] === 'notsay') ? 'selected' : ''; ?>>Prefer not to say</option>
              </select>
            </div>
            <div class="container">
              <label for="username">Username:</label>
              <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div>
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div>
              <label for="password">Password:</label>
              <input type="password" id="password" name="password">
              <span>Leave blank to keep the current password.</span>
            </div>
          </fieldset>

          <input type="submit" value="Update Account">
        </form>
      <?php
      } else {
        echo "User not found.";
      }
      ?>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
