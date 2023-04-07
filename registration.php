<?php
session_start();
if (isset($_SESSION["user"])) {
  // Redirect to the appropriate dashboard based on the user's account type
  if ($_SESSION["account_type"] == "Admin") {
    header("Location: dashboard_admin.php");
  } else {
    header("Location: dashboard_user.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="icon" type="image/x-icon" href="./assets/img/favicon.ico">
  <link rel="stylesheet" href="./styles/main.css" />
  <link rel="stylesheet" href="./styles/register.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
</head>

<body>
  <div class="container flex">
    <?php
    if (isset($_POST["submit"])) {
      $accountType = $_POST["accountType"];
      $firstName = $_POST["firstName"];
      $lastName = $_POST["lastName"];
      $dateHired = $_POST["dateHired"];
      $position = $_POST["position"];
      $salary = $_POST["salary"];
      $department = $_POST["department"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $passwordRepeat = $_POST["passwordRepeat"];

      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      $errors = [];

      if (empty($accountType) || empty($firstName) || empty($lastName) || empty($dateHired) || empty($position) || empty($salary) || empty($department) || empty($username) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required";
      }

      if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
      }

      if ($password !== $passwordRepeat) {
        $errors[] = "Password does not match";
      }

      require_once "connection.php";
      $sql = "SELECT * FROM employeetable WHERE username = '$username'";
      $result = mysqli_query($conn, $sql);
      $rowCount = mysqli_num_rows($result);
      if ($rowCount > 0) {
        $errors[] = "Username already exists!";
      }

      if (!empty($errors)) {
        foreach ($errors as $error) {
          // echo "<div class='errors'>$error</div>";
          $errors_msg = $error;
        }
      } else {
        $sql = "INSERT INTO employeetable (account_type, first_name, last_name, date_hired, position, salary, department, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
          mysqli_stmt_bind_param($stmt, "sssssssss", $accountType, $firstName, $lastName, $dateHired, $position, $salary, $department, $username, $passwordHash);
          mysqli_stmt_execute($stmt);

          $success_msg = "You are registered successfully. Redirecting to login page in 3 seconds... <meta http-equiv='refresh' content='3;url=login.php'>";
        } else {
          die("Something went wrong");
        }
      }
    }
    ?>
    <form action="./registration.php" method="post" class="form">
      <p class="title">Register</p>
      <p class="message">
        Lorem ipsum dolor amet, consectetur adipisicing elit.
      </p>

      <?php if (isset($success_msg)) : ?>
        <div class="success"><?php echo $success_msg; ?></div>
      <?php endif; ?>

      <?php if (isset($errors_msg)) : ?>
        <div class="errors"><?php echo $errors_msg; ?></div>
      <?php endif; ?>

      <label for="account-type" class="dropdown">
        <select name="accountType" id="accountType">
          <option value="">-- Account Type --</option>
          <option value="User">User</option>
          <option value="Admin">Admin</option>
        </select>
      </label>

      <div class="flex">
        <label>
          <input required="" placeholder="" type="text" name="firstName" class="input" />
          <span>Firstname</span>
        </label>
        <label>
          <input required="" placeholder="" type="text" name="lastName" class="input" />
          <span>Lastname</span>
        </label>
      </div>
      <label class="input-date">
        <input required="" placeholder="" type="date" name="dateHired" class="input" />
        <!-- <div class="material-icons-sharp date-icon">
          calendar_month
        </div> -->
        <!-- <span>Date Hired</span> -->
      </label>
      <div class="flex">
        <label>
          <input required="" placeholder="" type="text" name="position" class="input" />
          <span>Position</span>
        </label>
        <label>
          <input required="" placeholder="" type="number" name="salary" class="input" />
          <span>Salary</span>
        </label>
      </div>
      <div class="flex">
        <label>
          <input required="" placeholder="" type="text" name="department" class="input" />
          <span>Department</span>
        </label>
        <label>
          <input required="" placeholder="" type="username" name="username" class="input" />
          <span>Username</span>
        </label>
      </div>
      <div class="flex">
        <label>
          <input required="" placeholder="" type="password" name="password" class="input" />
          <span>Password</span>
        </label>
        <label>
          <input required="" placeholder="" type="password" name="passwordRepeat" class="input" />
          <span>Confirm password</span>
        </label>
      </div>
      <button class="submit" name="submit">Submit</button>
      <p class="signin">
        Already have an acount ? <a href="./login.php">Signin</a>
      </p>
    </form>
  </div>
</body>

</html>