<?php
if (isset($_POST['btn-login'])) {
  csrfCheck();
  controller("Auth");
  $auth = new Auth();
  $user = $auth->login($_POST['email'], $_POST['password']);
  echo json_encode(($user));
} else {
  if (App::getSession())
    header("Location:" . home());
}
?>
<!doctype html>
<html lang="en">
<title>Login | GraphenePHP</title>

<?php include("views/partials/head.php"); ?>

<style>
  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin-top: 2vh !important;
    margin: auto;
  }

  .form-signin .checkbox {
    font-weight: 400;
  }

  .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
  }

  .form-signin .form-control:focus {
    z-index: 2;
  }

  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

  #eye {
    cursor: pointer;
  }

  .logo img {
    max-height: 12vh;
  }
</style>

<body class="text-center">
  <?php require('views/partials/nav.php'); ?>
  <div class="logo mt-5 pt-5">
    <a href="<?php echo home() ?>"><img src="<?php echo home() . $config['APP_ICON']; ?>" alt="graphene"
        class="img-fluid"></a>
  </div>
  <form method="POST" name="Login" class="form-signin">
    <h2 class="mb-3 fw-bolder">Log In</h1>
      <?php if ($_GET['loggedout']) { ?>
        <div class="alert alert-success" role="alert">
          <?php echo "Logged Out Successfully"; ?>
        </div>
      <?php } ?>
      <?php if (!empty($auth->errors)) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $auth->errors; ?>
        </div>
      <?php } ?>

      <?php csrf() ?>
      <div class="mb-3">
        <input name="email" type="email" id="email" class="form-control" placeholder="Email" required>
        <strong id="emailMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>

      <div class="text-end">
        <span class="text-graphene user-select-none" id="eye"></span>
      </div>
      <div class="mb-3">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <strong id="passwordMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>

      <button class="btn btn-lg btn btn-graphene btn-block" id="btn-login" name="btn-login" type="login">Sign
        in</button>

      <p class="mt-3">Don't Have an account? <a href="<?php echo route('register').queryString(); ?>">Register Now</a></p>
  </form>

  <script>

    let emailError = true;
    let passwordError = true;

    let email = document.querySelector("#email");
    let password = document.querySelector("#password");
    checkErrors();


    let eye = document.querySelector('#eye')
    eye.innerHTML = '<i class="bi bi-eye-fill"></i> Show Password'
    eye.addEventListener('click', passwordToggle)
    function passwordToggle() {
      if (password.type == "password") {
        password.type = "text";
        eye.innerHTML = '<i class="bi bi-eye-slash-fill"></i> Hide Password'
      } else {
        password.type = "password";
        eye.innerHTML = '<i class="bi bi-eye-fill"></i> Show Password'
      }
    }


    function checkEmailPattern(email) {
      const re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }

    function validateEmail() {
      let emailValue = email.value.trim()
      let emailMsg = document.querySelector("#emailMsg")
      if (emailValue == "") {
        emailError = true
        checkErrors()
        emailMsg.innerText = "Email can't be empty"
        email.classList.add("is-invalid")
      } else if (!checkEmailPattern(emailValue)) {
        emailError = true
        checkErrors()
        emailMsg.innerText = "Email is invalid"
        email.classList.add("is-invalid")
      }
      else {
        emailError = false
        checkErrors()
        email.classList.remove("is-invalid")
        email.classList.add("is-valid")
        emailMsg.innerText = ""
      }
    }

    email.addEventListener("focusout", function () {
      validateEmail()
    })
    email.addEventListener("keyup", function () {
      validateEmail()
    })






    function validatePassword() {
      let passwordValue = password.value
      let passwordMsg = document.querySelector("#passwordMsg")
      if (passwordValue.length <= 5) {
        passwordError = true
        checkErrors()
        passwordMsg.innerHTML = "Password must be atleast 6 charecters"
        password.classList.add("is-invalid")
      } else if (passwordValue.search(/[a-z]/i) < 0) {
        passwordError = true
        checkErrors()
        passwordMsg.innerHTML = "Must contain atleast one lowercase alphabet"
        password.classList.add("is-invalid")
      } else if (passwordValue.search(/[0-9]/) < 0) {
        passwordError = true
        checkErrors()
        passwordMsg.innerHTML = "Must contain atleast one number"
        password.classList.add("is-invalid")
      } else if (passwordValue.search(/\W|_/g) < 0) {
        passwordError = true
        checkErrors()
        passwordMsg.innerHTML = "Must contain atleast one special character"
        password.classList.add("is-invalid")
      } else if (passwordValue.search(/[A-Z]/) < 0) {
        passwordError = true
        checkErrors()
        passwordMsg.innerHTML = "Must contain atleast one uppercase alphabet"
        password.classList.add("is-invalid")
      } else {
        passwordError = false
        checkErrors()
        password.classList.remove("is-invalid")
        password.classList.add("is-valid")
        passwordMsg.innerText = ""
      }
    }

    password.addEventListener("focusout", function () {
      validatePassword()
    })
    password.addEventListener("keyup", function () {
      validatePassword()
    })


    function checkErrors() {
      errors = emailError + passwordError
      if (errors) {
        document.querySelector("#btn-login").disabled = true;
      } else {
        document.querySelector("#btn-login").disabled = false;
      }
    }

  </script>
</body>

</html>