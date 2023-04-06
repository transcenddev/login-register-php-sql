<?php
session_start();
if (isset($_SESSION["user"])) {
  header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- CSS -->
  <link rel="stylesheet" href="./styles/main.css">
  <!-- Boostrap CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body class="text-center">
  <main class="form-signin w-100 m-auto">

    <?php
    if (isset($_POST["login"])) {
      require_once "database.php";

      $email = $_POST["email"];
      $password = $_POST["password"];
      $sql = "SELECT * FROM users WHERE email = '$email'";
      $result = mysqli_query($conn, $sql);
      $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

      if ($user && password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["user"] = "yes";
        header("Location: index.php");
        die();
      } else {
        echo "<div class='alert alert-danger'>Email or Password is incorrect</div>";
      }
    }
    ?>

    <div class="container">
      <form action="login.php" method="post">
        <h1 class="h3 mb-3">Please sign in</h1>
        <div class="form-floating">
          <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Sign in</button>
      </form>
      <div>
        <p>Not registered yet? <a href="./registration.php">Register Here</a></p>
      </div>
    </div>
  </main>





</body>

</html>