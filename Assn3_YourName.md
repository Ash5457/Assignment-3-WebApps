# 3420 Assignment #3 - Fall 2023

Name(s): Akash Bahl (0470368) Farzad Imran (0729901)

Live Loki link(s): <https://loki.trentu.ca/~demiimran/3420/assn/assn3/>

Credentials for a test account already in your database: Username: Cement
            Password: moleman
            Another Account: w, e

## Rubric

| Component                                                    | Grade |
| :----------------------------------------------------------- | ----: |
| Dynamic Nav                                                  |    /1 |
| Include Page Template Files (header, nav, etc)               |    /1 |
| Register (account and list)                                  |    /4 |
| Edit Account (account and list)                              |    /5 |
| Delete Account                                               |    /2 |
| Login                                                        |    /3 |
| Logout                                                       |    /1 |
| Forgot Password                                              |    /3 |
| Main Page                                                    |    /7 |
| Public List                                                  |    /2 |
| Edit List Item                                               |    /5 |
| Delete Item                                                  |    /2 |
| View List Item                                               |    /3 |
| Search                                                       |    /3 |
|                                                              |       |
| Meaningful Validation                                        |    /2 |
| Security Considerations(hashing, encoding & escaping, etc, page lockdown) |    /3 |
| Code Quality (tidyness, validity, etc)                       |    /3 |
| Documentation                                                |    /5 |
| Testing                                                      |    /5 |
|                                                              |       |
| Bonus                                                        |       |
| Deductions (readability, submission guidelines, originality) |       |
|                                                              |       |
| Total                                                        |   /60 |

## Things to consider for Bonus Marks (if any)

## Wrappers (header, nav, footer)

### HTML/PHP

```xml
<nav>
    <ul>
        <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
        <li><a href="./list.php"><i class="fa-solid fa-clipboard-list"></i></a></li>
        <?php
        if (isset($_SESSION['username'])) {
            // Display user-specific links when logged in
            echo '<li><a href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>';
            echo '<li><a href="./edit-account.php"><i class="fa-solid fa-user-edit"></i></a></li>';
            echo '<li><a href="./delete-account.php"><i class="fa-solid fa-user-times"></i></a></li>';
        } else {
            // Display login and register links when logged out
            echo '<li><a href="./login.php"><i class="fa-solid fa-right-to-bracket"></i></a></li>';
            echo '<li><a href="./register.php"><i class="fa-solid fa-user-plus"></i></a></li>';
        }
        ?>
        <li><a href="./search.php"><i class="fa-solid fa-magnifying-glass"></i></a></li>
    </ul>
</nav>

<footer>&copy; 2023 - Farzad Imran & Akash Bahl</footer>

```


### Testing (include one test for each dynamic version of your menu)

## Register

### HTML/PHP

```xml
<?php

// declare error array
$errors = array();


// delcare defaults
$name               = $_POST['name'] ?? "";
$gender             = $_POST['gender'] ?? "";
$username           = $_POST['username'] ?? "";
$email              = $_POST['email'] ?? "";
$password           = $_POST['password'] ?? "";
$confirmPassword    = $_POST['confirm_password'] ?? "";
$title              = $_POST['title'] ?? "";
$description        = $_POST['description'] ?? "";
$public             = $_POST['public_view'] ?? 'Public';



//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();

//validate the form
if (isset($_POST['submit'])) {
  // Sanitize all text inputs
  $name               = htmlspecialchars($name);
  $gender             = htmlspecialchars($gender);
  $username           = htmlspecialchars($username);
  $email              = htmlspecialchars($email);
  $password           = htmlspecialchars($password);
  $confirmPassword    = htmlspecialchars($confirmPassword);
  $title              = htmlspecialchars($title);
  $description        = htmlspecialchars($description);
  
  //basic form validation
  if (strlen($name) == 0) {
    $errors['name'] = true;
  }
  if (strlen($gender) == 0) {
    $errors['gender'] = true;
  }
  if (strlen($username) == 0) {
    $errors['username'] = true;
  }else {
    // Check if username is unique
    $query ="SELECT id FROM 3420_assg_users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
      $errors['username'] = null;
      $errors['unique'] = true;
    }
  }
  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors['email'] = true;
  }
  if (strlen($password) == 0) {
    $errors['password'] = true;
  }
   // Validate password match
   if ($password !== $confirmPassword) {
    $errors['match'] = true;
  }
  if (strlen($title) == 0) {
    $errors['title'] = true;
  }
  if (strlen($description) == 0) {
    $errors['description'] = true;
  }


  if (count($errors) === 0) { 

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $userquery = "INSERT INTO 3420_assg_users (`name`, `gender`, `username`, `email`, `password`) VALUES (?, ?, ?, ?, ?)";
    $users_stmt = $pdo->prepare($userquery);
    $users_stmt->execute([$name, $gender, $username, $email, $hashedPassword]);

    $get_uid = "SELECT `id` FROM `3420_assg_users` WHERE `3420_assg_users`.`username` = ?";
    $uid = $pdo->prepare($get_uid);
    $uid->execute([$username]);
    $result = $uid->fetch(PDO::FETCH_ASSOC);
    $userid = $result['id'];
    $listquery = "INSERT INTO 3420_assg_lists (`user_id`, `title`, `description`, `publicity`) VALUES (?, ?, ?, ?)";
    $list_stmt = $pdo->prepare($listquery);
    $list_stmt->execute([$userid, $title, $description, $public]);

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
  <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
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
    <form method="post" action="">
      <fieldset>
        <legend>Account Information</legend>
        <div>
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" value="<?= $name ?>">
          <span class="error <?= !isset($errors['name']) ? 'hidden' : '' ?>">Please Enter a Name.</span>
        </div>
        <div>
          <label for="gender">Gender</label>
          <select name="gender" id="gender">
            <option value="">Please Choose One</option>
            <option value="male"<?php if($gender == "male") echo "selected='selected'"; ?>>Male</option>
            <option value="female"<?php if($gender == "female") echo "selected='selected'"; ?>>Female</option>
            <option value="gnc"<?php if($gender == "gnc") echo "selected='selected'"; ?>>Gender Queer/Non-Conforming</option>
            <option value="notsay"<?php if($gender == "notsayy") echo "selected='selected'"; ?>>Prefer not to say</option>
          </select>
          <span class="error <?= !isset($errors['gender']) ? 'hidden' : '' ?>">Please Choose a Gender.</span>
        </div>

        <div class="container">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" value="<?= $username ?>" >
          <span class="error <?= !isset($errors['username']) ? 'hidden' : '' ?>">Please Enter a Username.</span>
          <span class="error <?= !isset($errors['unique']) ? 'hidden' : '' ?>">Invalid Username!</span>
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?= $email ?>" >
          <span class="error <?= !isset($errors['email']) ? 'hidden' : '' ?>">Enter a Valid Email.</span>
        </div>

        <div>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" value="<?= $password ?>" >
          <span class="error <?= !isset($errors['password']) ? 'hidden' : '' ?>">Please Enter a Password.</span>
        </div>
        <div>
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" value="<?= $confirmPassword ?>">
          <span class="error <?= !isset($errors['match']) ? 'hidden' : '' ?>">Passwords Do Not Match.</span>
        </div>
      </fieldset>
      <fieldset>
        <legend>Create Your First List</legend>
        <div>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" value="<?= $title ?>">
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea id="description" name="description" ><?= $description ?></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
        </div>
        <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private">
          <input type="checkbox" id="public_view" name="public_view" value="Public" checked>
        </div>
      </fieldset>
      <button id="submit" name="submit">Register</button>
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>
```

### Testing

## Edit Account

### HTML/PHP

```xml
<?php
session_start();

require './includes/library.php';

$pdo = connectDB();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmtUser = $pdo->prepare("SELECT * FROM 3420_assg_users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();

// Initialize variables with default values or retrieve them from $user
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : $user['gender'];
    $username = isset($_POST['username']) ? $_POST['username'] : $user['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Handle user information update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {

    // Initialize variables with default values or retrieve them from $user
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : $user['gender'];
    $username = isset($_POST['username']) ? $_POST['username'] : $user['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Sanitize all text inputs
    $name               = htmlspecialchars($name);
    $gender             = htmlspecialchars($gender);
    $username           = htmlspecialchars($username);
    $email              = htmlspecialchars($email);
    $password           = htmlspecialchars($password);
    $confirm_password    = htmlspecialchars($confirm_password);


    // Simple validation 
    if (empty($name) || empty($gender) || empty($username) || empty($email)) {
        $userUpdateError = 'All fields are required for user information update.';
    } else {
        // Update user information in the database
        $stmtUserUpdate = $pdo->prepare("UPDATE 3420_assg_users SET name = ?, gender = ?, username = ?, email = ? WHERE id = ?");
        $stmtUserUpdate->execute([$name, $gender, $username, $email, $user_id]);

        // Update password if provided
        if (!empty($password)) {
            // Validate password
            if ($password !== $confirm_password) {
                $userUpdateError = 'Password and confirm password do not match.';
            } else {
                // Update password in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtPasswordUpdate = $pdo->prepare("UPDATE 3420_assg_users SET password = ? WHERE id = ?");
                $stmtPasswordUpdate->execute([$hashedPassword, $user_id]);
            }
        }

        $userUpdateSuccess = 'User information updated successfully.';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
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
        <!-- User Information Update Form -->
        <form action="edit-account.php" method="post">
            <fieldset>
                <legend>Update User Information</legend>

                <?php
                if (isset($userUpdateError)) {
                    echo '<div class="error">' . $userUpdateError . '</div>';
                } elseif (isset($userUpdateSuccess)) {
                    echo '<div class="success">' . $userUpdateSuccess . '</div>';
                }
                ?>

                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div>
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="gnc" <?php echo ($gender === 'gnc') ? 'selected' : ''; ?>>Gender Queer/Non-Conforming</option>
                        <option value="notsay" <?php echo ($gender === 'notsay') ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                </div>
                <div class="container">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <span>Leave blank to keep the current password.</span>
            </fieldset>

            <input type="submit" name="updateUser" value="Update User Information">
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>
```

### Testing

## Delete Account

### HTML/PHP

```xml

<?php
require './includes/library.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pdo = connectDB();

// Fetch user ID from the database based on the current session
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT id FROM 3420_assg_users WHERE username = ?");
$stmt->execute([$username]);
$userID = $stmt->fetchColumn();
// Check if the user has confirmed the account deletion
if (isset($_POST['confirmDelete'])) {
    // Delete all data associated with the user
    $stmtDeleteLists = $pdo->prepare("DELETE FROM 3420_assg_lists WHERE user_id = ?");
    $stmtDeleteLists->execute([$userID]);

    $stmtDeleteUser = $pdo->prepare("DELETE FROM 3420_assg_users WHERE username = ?");
    $stmtDeleteUser->execute([$username]);

// Destroy the session
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Delete Account</title>
    <!-- include CSS-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>
    <header>
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Delete Account</h1>
        <?php include './includes/nav.php' ?>
    </header>
    <main>
        <form action="delete-account.php" method="post">
            <fieldset>
                <legend>Confirmation</legend>
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <div>
                    <button type="submit" name="confirmDelete" class="big-button">Yes, I'm sure. Delete my account</button>
                </div>
            </fieldset>
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>

```

### Testing

## Login

### HTML/PHP

```xml
<!--PHP section-->
<?php
require './includes/library.php';
session_start(); // Start the session

// Check if the user is already logged in, if so, redirect to the Main Page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
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
  $pdo = connectDB();
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Sanitize all text inputs
  $username= htmlspecialchars($username);
  $password= htmlspecialchars($password);

  $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;

  // Fetch user data from the database
  $stmt = $pdo->prepare("SELECT id, password FROM 3420_assg_users WHERE username = ?");
  $stmt->execute([$username]);
  $userData = $stmt->fetch();

  // Verify password
  if ($userData && password_verify($password, $userData['password'])) {
      // Password is correct, start a new session
      session_start();

      // Store user data in session variables
      $_SESSION['username'] = $username;
      $_SESSION['user_id'] = $userData['id'];

      // Create a cookie if "remember me" is checked
      if ($rememberMe) {
          setcookie('remember_me', $username, time() + (86400 * 30), "/"); // 30 days
      } else {
          // If "remember me" is not checked, clear the cookie
          setcookie('remember_me', '', time() - 3600, "/");
      }

      // Redirect to the Main Page
      header("Location: index.php");
      exit();
  } else {
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
```

### Testing

## Logout

### HTML/PHP

```xml

<?php
// Start or resume the current session
session_start();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Delete Account</title>
    <!-- include CSS-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>

  <header>
    <!-- This will be the main heading of the page so users know what page they're on -->
    <h1>You Have Successfully Logged Out</h1>
    <?php include './includes/nav.php'; ?>
  </header>

  <main>
      <p>Thank you for using our site. You have successfully logged out!</p>
  </main>

  <?php include './includes/footer.php'; ?>
</body>
</html>


```

### Testing

## Forgot Password (collect email, send mail)

### HTML/PHP

```xml
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Forgot Password</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Forgot Password</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <form action="process-forgot-password.php" method="post">
        <div>
          <label for="usernameOrEmail">Username or Email:</label>
          <input
            type="text"
            id="usernameOrEmail"
            name="usernameOrEmail"
            value="<?php echo isset($_POST['usernameOrEmail']) ? htmlspecialchars($_POST['usernameOrEmail']) : ''; ?>"
            required
          >
        </div>
        <div>
          <label for="newPassword">New Password:</label>
          <input type="password" id="newPassword" name="newPassword" required>
        </div>
        <input type="submit" value="Reset Password">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>


```

## Forgot Password (use url value to change password)

### HTML/PHP

```xml 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Forgot Password</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Forgot Password</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <form action="process-forgot-password.php" method="post">
        <div>
          <label for="usernameOrEmail">Username or Email:</label>
          <input
            type="text"
            id="usernameOrEmail"
            name="usernameOrEmail"
            value="<?php echo isset($_POST['usernameOrEmail']) ? htmlspecialchars($_POST['usernameOrEmail']) : ''; ?>"
            required
          >
        </div>
        <div>
          <label for="newPassword">New Password:</label>
          <input type="password" id="newPassword" name="newPassword" required>
        </div>
        <input type="submit" value="Reset Password">
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>


```

### Testing

## Main Page

### HTML/PHP

```xml
<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
$userid = $_SESSION['user_id'];
// declare error array
$errors = array();


// delcare defaults
$title              = $_POST['title'] ?? "";
$description        = $_POST['description'] ?? "";
$public             = $_POST['public_view'] ?? 'Public';



//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();
if (isset($_POST['submit'])) {
  if (strlen($title) == 0) {
    $errors['title'] = true;
  }
  if (strlen($description) == 0) {
    $errors['description'] = true;
  }

  if (count($errors) === 0) { 
    // Sanitize all text inputs
    $title= htmlspecialchars($title);
    $description= htmlspecialchars($description);
    
    
    $listquery = "INSERT INTO 3420_assg_lists (`user_id`, `title`, `description`, `publicity`) VALUES (?, ?, ?, ?)";
    $list_stmt = $pdo->prepare($listquery);
    $list_stmt->execute([$userid, $title, $description, $public]);
    
    // refresh page
    header("Location: index.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
  <title>Index</title>
  <!-- include javascript and css-->
  <link rel="stylesheet" href="styles/main.css">
  <script defer src="js/scripts.js"></script>
</head>

<body>
  <header>
    <!--This will be the main heading of the page so users know what page they're on-->
    <h1>Welcome to the Main Page</h1>

    <?php include './includes/nav.php' ?>
  </header>

  <main>
    <h2>My Lists</h2>
    <ul>
    <?php
            // Fetch and display user's lists from the database
            $varpub ="Public";
            $varpriv ="Private";
            $query = "SELECT list_id, user_id, title FROM 3420_assg_lists WHERE publicity = ? OR user_id = ? AND publicity = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$varpub, $userid, $varpriv]);
            $user_lists = $stmt->fetchAll();

            foreach ($user_lists as $list) :
            ?>
      <li><a href="view-item.php?id=<?php echo $list["list_id"]; ?>"><?= $list["title"] ?></a> 
      <?php if ($list["user_id"] == $userid) { ?> 
        <a href="edit-item.php?id=<?php echo $list["list_id"]; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
          <a href="delete-item.php?id=<?php echo $list["list_id"]; ?>"><i class="fa-solid fa-trash"></i></a>
        </button>
      <?php } ?>
        <?php endforeach; ?>
      </li>
    </ul>
    <h2>Add New Entry:</h2>
    <form method="post" action="">
    <div>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" value="<?= $title ?>">
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea id="description" name="description" ><?= $description ?></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
        </div>
        <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private">
          <input type="checkbox" id="public_view" name="public_view" value="Public" checked>
        </div>
      </fieldset>
      <button id="submit" name="submit">Make List</button>
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>

```

### Testing

## Public View of List

### HTML/PHP

```

```

### Testing

## Edit Item

### HTML/PHP

```

```

### Testing

## Delete Item

### HTML/PHP

```

```

### Testing

## Display Item Details

### HTML/PHP

```

```

### Testing

## Search

### HTML/PHP

```

```

### Testing

## Styles

```css

```

_if for some reason you ended up with more then one stylesheet, they should be labelled_
