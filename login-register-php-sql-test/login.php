<?php
session_start();
if (isset($_SESSION["user"])) {
  header("Location: index.php");
}

if (isset($_POST["login"])) {
  require_once "connection.php";

  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT * FROM employeetable WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user"] = true;
    header("Location: index.php");
    exit();
  } else {
    $error_msg = "Email or password is incorrect";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="" />
  <meta name="author" content="Reymar Mirante" />
  <title>Register</title>
  <link rel="stylesheet" href="./styles/login.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
</head>

<body class="text-center">
  <main class="form-signin w-100 m-auto">

    <?php if (isset($error_msg)) : ?>
      <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form action="./login.php" method="post">
      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
      <div class="form-floating">
        <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" />
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" />
        <label for="floatingPassword">Password</label>
      </div>

      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me" /> Remember me
        </label>
      </div>
      <div>
        <p>
          Not registered yet? <a href="./registration.php">Register Here</a>
        </p>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">
        Sign in
      </button>

      <p class="mt-5 mb-3 text-muted">&copy; 2017–2022</p>
    </form>
  </main>
</body>

</html>