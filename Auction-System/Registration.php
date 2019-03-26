<?php

require 'includes/server.php';

$_SESSION = array();
session_destroy();
session_start();

$errors = array(); 

if (isset($_POST['register-button'])) {

    $username = mysqli_real_escape_string($db, htmlspecialchars($_POST['username']));
    $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email']));
    $password_1 = htmlspecialchars($_POST['password_1']);
    if (strlen($password_1)<8) {
        array_push($errors, "Sorry, your password shoul be at least 8 digits");
    }
    if (!preg_match("#[0-9]+#", $password_1)) {
        array_push($errors, "Password must include at least one number");
    }
    if (!preg_match("#[a-zA-Z]+#", $password_1)) {
        array_push($errors, "Password must include at least one letter");
    }

    $role = $_POST['RoleOptions'];
 
    $user_check_query = "SELECT * FROM User WHERE Username='$username' LIMIT 1";
    $checkresult = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($checkresult);
  
    if ($user) {
        if ($user['Username'] === $username) {
            array_push($errors, "Username already exists");
        }
    }

    if (count($errors) == 0) {

        $password = password_hash($password_1, PASSWORD_DEFAULT);
        $insertquery = "INSERT INTO User (`Username`, `Email`, `Password`, `Role`) 
                VALUES('$username', '$email', '$password', '$role')";
        mysqli_query($db, $insertquery);
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $db->insert_id;
        $_SESSION['role'] = $user['Role'];
        $_SESSION["loggedin"] = true;
        $_SESSION['success'] = "You are now logged in";
        
        if($role == "Buyer"){
            header('location: BuyerPortal.php');
        }else{
            header('location: SellerPortal.php');
        }
        
    }
}

require 'includes/header.php';

?>


<body>

<div class='container mt-3' style="text-align:center;">

<?php  if (count($errors) > 0) : ?>
    
        <?php foreach ($errors as $error) : ?>
            <div class="row justify-content-center">
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            </div>
        <?php endforeach ?>       
    
<?php  endif ?>
    

    <div class="row justify-content-center">
        <h1>Welcome to the Auction System!</h1>
    </div>
    <div class="row justify-content-center">
        <h2>Register</h2>
    </div>

    <form method="post" action="Registration.php" class="mt-3" oninput='password_2.setCustomValidity(password_2.value != password_1.value ? "Passwords do not match." : "")'>
        <div class="form-group" >
            <div class="mx-auto" style="width: 300px;">

                <label for="InputUsername">Username:</label>
        
                <input type="text" class="form-control" id="InputUsername" aria-describedby="emailHelp" name="username" placeholder="Enter username" required>

            </div>
        </div>
        <div class="form-group" >
            <div class="mx-auto" style="width: 300px;">

                <label for="InputEmail">Email address:</label>
        
                <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" name="email" placeholder="Enter email" required>

            </div>
        </div>
        <div class="form-group">
            <div class="mx-auto" style="width: 300px;">

                <label for="InputPassword1">Password</label>
                <input type="password" class="form-control" id="InputPassword1" placeholder="Password" name="password_1" required>

            </div>
            </div>
        <div class="form-group">
            <div class="mx-auto" style="width: 300px;">

                <label for="InputPassword2">Condirm Password</label>
                <input type="password" class="form-control" id="InputPassword2" placeholder="Confrim Password" name="password_2" required>

            </div>
        <br />

        <small class="form-text text-muted">Select your role:</small>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="RoleOptions" id="inlineRadio1" value="Seller" required>
                <label class="form-check-label" for="inlineRadio1">Seller</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="RoleOptions" id="inlineRadio2" value="Buyer" required>
                <label class="form-check-label" for="inlineRadio2">Buyer</label>

            </div>
        </div>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-outline-primary" name="register-button">Register</button>
        </div>
    </form>

    <div class="row mt-3 justify-content-center">
        <a href="login.php"><button type="button" class="btn btn-outline-info">Already registered? Click here to login</button></a>
    </div>

</body>