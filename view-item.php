<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>View Item</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>View Item</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <h2>Visiting Mecca</h2>
      <pre>
Parent List:
    <a href="">Places I want to go</a>
Completion Date
    <input type="date" value="2019-03-08" disabled>
      </pre>
      <p>
        After being stuck in Peterborough all my life I decided I should go
        travel while I could, there's a few places I want to go. One of them has
        always been Mecca, with such great religious importance alongside such
        unique architecture it was a great change from plain old Peterborough.
      </p>
      <p>
        As a Muslim once in your life you need to perform an act called Hajj,
        and I thought this was the perfect chance to explore. Luckily during my
        visit I was able to get close enough to take a good picture of the
        Kaaba. The entire experience was fascinating to me as being in Canada my
        whole life I never could imagine a world so vastly different just a
        flight away. I shaved my head and tried new foods, but also saw a lot of
        interesting things like a hoarde of pigeons or a camel in the middle of
        the road but just the geography and culture as a whole was a new
        eye-opening experience. The streets were so clean in places it made
        Peterborough look like the slums... which is not something I ever
        expected I would say.
      </p>
      <img
        src="images/kaaba.jpg"
        alt="An image of the Kaaba from a close distance, a Green clocktower can be seen in the backgroudn against the night sky"
        width="299"
        height="531"
      >
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>
