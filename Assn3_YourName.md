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

```

```
```

### Testing (include one test for each dynamic version of your menu)

## Register

### HTML/PHP

```

```

### Testing

## Edit Account

### HTML/PHP

```

```

### Testing

## Delete Account

### HTML/PHP

```php+HTML

```

### Testing

## Login

### HTML/PHP

```

```

### Testing

## Logout

### HTML/PHP

```

```

### Testing

## Forgot Password (collect email, send mail)

### HTML/PHP

```

```

## Forgot Password (use url value to change password)

### HTML/PHP

```

```

### Testing

## Main Page

### HTML/PHP

```

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
