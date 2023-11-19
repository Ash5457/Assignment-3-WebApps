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
      <form action="create-account.php" method="post">
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
          </div>
          <div class="container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
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
          </div>
        </fieldset>
        <fieldset>
          <legend>Create your first List</legend>
          <div>
            <label for="list_name">List Name:</label>
            <input type="text" id="list_name" name="list_name" required>
          </div>
          <div>
            <label for="list_description">List Description:</label>
            <textarea
              id="list_description"
              name="list_description"
              required
            ></textarea>
          </div>
          <div>
            <label for="public_view">Make List Public?:</label>
            <input type="checkbox" id="public_view" name="public_view">
          </div>
        </fieldset>
        <input type="submit" value="Create Account">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
