<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticketing Code</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>Ticketing Code System</h1>
</header>
<section class="fixed-form">
  <h2>Enter number of codes to process (1–50):</h2>
  <form method="post">
    <label for="tix_code">Number of Codes:</label>
    <input type="number" name="tix_code" id="tix_code" min="1" max="50" required>
    <button type="submit">Process</button>
  </form>
</section>


<div class="results-wrapper">
  <section class="result">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tix_code"])) {
      $tix_code = intval($_POST["tix_code"]);

      if ($tix_code < 1 || $tix_code > 51) {
        echo "<div class='log termination'>Please enter a number between 1 and 50.</div>";
      } else {
        for ($code = $tix_code; $code <= 50; $code++) {
          if ($code == 50) {
            echo "<div class='log termination'>Processing special termination code: $code</div>";
            break;
          }

          if ($code % 15 == 0) {
            echo "<div class='log vvip'>Processing VVIP event ticket: $code</div>";
            echo "<div class='log vvip'>End of processing for code: $code</div>";
            continue;
          }

          if ($code % 3 != 0 && $code % 5 != 0) {
            echo "<div class='log general'>General inquiry for code: $code. Skipping...</div>";
            continue;
          }

          if ($code % 3 == 0) {
            echo "<div class='log regular'>Processing regular event ticket: $code</div>";
          }

          if ($code % 5 == 0) {
            echo "<div class='log vip'>Processing VIP event ticket: $code</div>";
          }

          echo "<div class='log'>End of processing for code: $code</div>";
        }
      }
    }
    ?>
  </section>
</div>

</body>
</html>
