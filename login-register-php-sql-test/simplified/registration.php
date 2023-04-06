<?php
/* Checking if the user is logged in. If not it will redirect the user to the index page. */
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
  <title>Registration</title>
  <!-- CSS -->
  <link rel="stylesheet" href="./styles/main.css">
  <!-- Boostrap CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body class="text-center">
  <?php
  if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = [];

    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
      $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Email is not valid";
    }

    if (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $passwordRepeat) {
      $errors[] = "Password does not match";
    }

    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
      $errors[] = "Email already exists!";
    }

    if (!empty($errors)) {
      foreach ($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
      }
    } else {
      $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
      $stmt = mysqli_stmt_init($conn);
      $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
      if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>You are registered successfully.</div>";
      } else {
        die("Something went wrong");
      }
    }
  }
  ?>

  <div class="container center">
    <form action="./registration.php" method="post" class="form form-register w-100 m-auto">
      <!-- <img class="mb-4" src="" alt="" width="72" height="57"> -->
      <h1 class="h3 mb-3">Registration</h1>
      <div class="form-floating mb-3">
        <input type="text" name="fullname" class="form-control" id="floatingInput" placeholder="Full Name">
        <label for="floatingInput">Full Name</label>
      </div>
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="repeat_password" class="form-control" id="floatingPassword" placeholder="Confirm Password">
        <label for="floatingPassword">Confirm Password</label>
      </div>
      <!-- <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div> -->
      <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Register</button>
    </form>
    <div class="mt-2">
      <p>Already Registered <a href="./login.php">Login Here</a></p>
    </div>
  </div>
</body>

</html>