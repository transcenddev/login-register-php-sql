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
  <title>Register</title>
</head>

<body>

</body>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="" />
  <meta name="author" content="Reymar Mirante" />
  <title>Register</title>
  <link rel="stylesheet" href="./styles/register.css" />
  <!-- Bootstrap CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
</head>

<body class="text-center">
  <main class="form-register w-100 m-auto">
    <?php
    if (isset($_POST["submit"])) {
      $firstName = $_POST["firstname"];
      $lastName = $_POST["lastname"];
      $dateHired = $_POST["datehired"];
      $position = $_POST["position"];
      $salary = $_POST["salary"];
      $department = $_POST["department"];
      $username = $_POST["username"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $passwordRepeat = $_POST["repeatpassword"];

      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      $errors = [];

      if (empty($firstName) || empty($lastName) || empty($dateHired) || empty($position) || empty($salary) || empty($department) || empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
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

      require_once "connection.php";
      $sql = "SELECT * FROM employeetable WHERE email = '$email'";
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
        $sql = "INSERT INTO employeetable (first_name, last_name, date_hired, position, salary, department, username, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
          mysqli_stmt_bind_param($stmt, "sssssssss", $firstName, $lastName, $dateHired, $position, $salary, $department, $username, $email, $passwordHash);
          mysqli_stmt_execute($stmt);
          echo "<div class='alert alert-success'>You are registered successfully.</div>";
        } else {
          die("Something went wrong");
        }
      }
    }
    ?>
    <form action="./registration.php" method="post">
      <h1 class="h3 mb-3">Registration</h1>
      <div class="form-floating">
        <input type="text" class="form-control first-child" name="firstname" id="floatingInput" placeholder="First Name" />
        <label for="floatingInput">First Name</label>
      </div>

      <div class="form-floating">
        <input type="text" class="form-control" name="lastname" id="floatingInput" placeholder="Last Name" />
        <label for="floatingInput">Last Name</label>
      </div>

      <div class="form-floating">
        <input type="date" class="form-control" name="datehired" id="floatingInput" placeholder="Date Hired" />
        <label for="floatingInput">Date Hired</label>
      </div>

      <div class="form-floating">
        <input type="text" class="form-control" name="position" id="floatingInput" placeholder="Position" />
        <label for="floatingInput">Position</label>
      </div>

      <div class="form-floating">
        <input type="text" class="form-control" name="salary" id="floatingInput" placeholder="Salary" />
        <label for="floatingInput">Salary</label>
      </div>

      <div class="form-floating">
        <input type="text" class="form-control" name="department" id="floatingInput" placeholder="Department" />
        <label for="floatingInput">Department</label>
      </div>

      <div class="form-floating">
        <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" />
        <label for="floatingInput">Username</label>
      </div>

      <div class="form-floating">
        <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" />
        <label for="floatingInput">Email address</label>
      </div>

      <div class="form-floating">
        <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" />
        <label for="floatingPassword">Password</label>
      </div>

      <div class="form-floating">
        <input type="password" class="form-control last-child" name="repeatpassword" id="floatingPassword" placeholder="Password" />
        <label for="floatingPassword">Confirm Password</label>
      </div>
      <div>
        <p>
          Already registered? <a href="./login.php">Login Here</a>
        </p>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">
        Register
      </button>
    </form>
  </main>
</body>

</html>

</html>