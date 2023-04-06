<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="stylesheet" href="./styles/main.css" />
  <link rel="stylesheet" href="./styles/register.css" />
</head>

<body>
  <div class="container">
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
      $email = $_POST["email"];
      $password = $_POST["password"];
      $passwordRepeat = $_POST["repeatpassword"];

      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      $errors = [];

      if (empty($accountType) || empty($firstName) || empty($lastName) || empty($dateHired) || empty($position) || empty($salary) || empty($department) || empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
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
          mysqli_stmt_bind_param($stmt, "ssssssssss", $accountType, $firstName, $lastName, $dateHired, $position, $salary, $department, $username, $email, $passwordHash);
          mysqli_stmt_execute($stmt);
          echo "<div class='alert alert-success'>You are registered successfully.</div>";
        } else {
          die("Something went wrong");
        }
      }
    }
    ?>
    <form actioin="./register.php" method="post" class="form">
      <p class="title">Register</p>
      <p class="message">
        Lorem ipsum dolor amet, consectetur adipisicing elit.
      </p>

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
      <label>
        <input required="" placeholder="" type="date" name="dateHired" class="input" />
        <!-- <span>Date Hired</span> -->
      </label>
      <div class="flex">
        <label>
          <input required="" placeholder="" type="text" name="position" class="input" />
          <span>Position</span>
        </label>
        <label>
          <input required="" placeholder="" type="text" name="salary" class="input" />
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
          <input required="" placeholder="" type="password" name="repeatPassword" class="input" />
          <span>Confirm password</span>
        </label>
      </div>
      <button class="submit" name="submit">Submit</button>
      <p class="signin">
        Already have an acount ? <a href="./login.html">Signin</a>
      </p>
    </form>
  </div>
</body>

</html>