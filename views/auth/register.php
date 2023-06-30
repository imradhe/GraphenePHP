<?php
$config['APP_TITLE'] = "Register | ".$config['APP_TITLE'];
DB::connect();
$customers = DB::select('users')->fetchAll();
DB::close();

if (isset($_POST['btn-register'])) {
  csrfCheck();
  controller("Auth");
  $user = new Auth();
  $register = $user->register($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['password'], 'user');
} else {
  if (App::getUser()['role'] == 'user')
    header("Location:" . home());
}

?>
<!doctype html>
<html lang="en">

  <style>
    html,
    body {
      height: 100%;
    }

    body {
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }

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

    .logo img {
      max-height: 12vh;
    }

    #eye {
      cursor: pointer;
    }
  </style>

<?php include("views/partials/head.php"); ?>

<body class="text-center">
  <?php require('views/partials/nav.php'); ?>
  <div class="logo mt-5 pt-5">
    <a href="<?php echo home() ?>"><img src="<?php echo home() . $config['APP_ICON']; ?>" alt="GraphenePHP"
        class="img-fluid"></a>
  </div>
  <form method="POST" name="Register" class="form-signin">
    <h2 class="mb-3 fw-bolder">User Registration </h1>

      <?php csrf() ?>

      <div class="mb-3">
        <input name="name" type="name" id="name" class="form-control" placeholder="Name"
          value="<?php echo (!empty($_POST['name'])) ? $_POST['name'] : ''; ?>" required>
        <strong id="nameMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>

      <div class="mb-3">
        <input name="email" type="email" id="email" class="form-control" placeholder="Email"
          value="<?php echo (!empty($_POST['email'])) ? $_POST['email'] : ''; ?>" required>
        <strong id="emailMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>


      <div class="mb-3">
        <input name="phone" type="phone" id="phone" class="form-control" placeholder="phone"
          value="<?php echo (!empty($_POST['phone'])) ? $_POST['phone'] : ''; ?>" required>
        <strong id="phoneMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>

      <div class="text-end">
        <span class="text-graphene user-select-none" id="eye"></span>
      </div>
      <div class="mb-3">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password"
          value="<?php echo (!empty($_POST['password'])) ? $_POST['password'] : ''; ?>" required>
        <strong id="passwordMsg" class="text-danger errorMsg my-2 fw-bolder"></strong>
      </div>


      <button class="btn btn-lg btn-graphene btn-block" id="btn-register" name="btn-register" type="register"
        disabled>Register</button>

      <p class="mt-3">Already Have an account? <a href="<?php echo route('login'); ?>">Login Now</a></p>
  </form>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>

  <script>

    let nameError = true;
    let emailError = true;
    let phoneError = true;
    let passwordError = true;

    let name = document.querySelector("#name");
    let email = document.querySelector("#email");
    let phone = document.querySelector("#phone");
    let password = document.querySelector("#password");

    let emails = []

    <?php
    foreach ($customers as $email) {
      echo "emails.push('" . md5($email['email']) . "')\n";
    }
    ?>
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

    checkErrors();


    function validateName() {
      let nameValue = name.value
      let nameMsg = document.querySelector("#nameMsg")
      if (nameValue == "") {
        nameError = true
        checkErrors()
        nameMsg.innerText = "Name can't be empty"
        name.classList.add("is-invalid")
      } else if (nameValue.length <= 5) {
        nameError = true
        checkErrors()
        nameMsg.innerText = "Name can't be less than 6 Characters"
        name.classList.add("is-invalid")
      } else {
        nameError = false
        checkErrors()
        name.classList.remove("is-invalid")
        name.classList.add("is-valid")
        nameMsg.innerText = ""
      }
    }

    name.addEventListener("focusout", function () {
      validateName()
    })
    name.addEventListener("keyup", function () {
      validateName()
    })

    function validateMobile(mobilenumber) {
      var regmm = "^([6-9][0-9]{9})$";
      var regmob = new RegExp(regmm);
      if (regmob.test(mobilenumber)) {
        return true;
      } else {
        return false;
      }
    }

    function validatephone() {
      let phoneValue = phone.value.trim();
      let phoneMsg = document.querySelector("#phoneMsg")
      if (phone.value.trim() == "") {
        phoneError = true;
        checkErrors();
        phoneMsg.innerText = "Mobile number can't be empty";
        phone.classList.add("is-invalid");
      }
      else if (!validateMobile(phoneValue)) {
        phoneError = true;
        checkErrors();
        phoneMsg.innerText =
          "Mobile number is invalid (10 digits only)";
        phone.classList.add("is-invalid");
      } else {
        phoneError = false;
        checkErrors();
        phone.classList.remove("is-invalid");
        phone.classList.add("is-valid");
        phoneMsg.innerText = "";
      }
    }

    phone.addEventListener("focusout", function () {
      validatephone();
    });
    phone.addEventListener("keyup", function () {
      validatephone();
    });

    function checkEmailPattern(email) {
      const re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }

    function validateEmail() {
      let emailValue = email.value.trim().toLowerCase()

      let emailMsg = document.querySelector("#emailMsg")
      if (emailValue == "") {
        emailError = true
        checkErrors()
        emailMsg.innerText = "Email can't be empty"
        email.classList.add("is-invalid")
      } else if (emails.includes(CryptoJS.MD5(emailValue).toString())) {
        emailError = true
        checkErrors()
        emailMsg.innerText = "Email already in use"
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
      errors = nameError + emailError + phoneError + passwordError
      if (errors) {
        document.querySelector("#btn-register").disabled = true;
      } else {
        document.querySelector("#btn-register").disabled = false;
      }
    }


      <?php if ($register['error']) { ?>
        validateName()
        validateEmail()
        validatephone()
        validatePassword()
        <?php } ?>

  </script>
</body>

</html>