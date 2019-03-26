<?php
require 'includes/server.php';

$_SESSION = array();
session_destroy();
session_start();

$errors = array(); 

if (isset($_POST['login-button'])) {
        $username = mysqli_real_escape_string($db, htmlspecialchars($_POST['username']));
        $password = htmlspecialchars($_POST['password']);

        $checkuserquery = "SELECT * FROM User WHERE Username='$username'";
        $checkresult = mysqli_query($db, $checkuserquery);
        if (mysqli_num_rows($checkresult) == 1) {
            $user = mysqli_fetch_assoc($checkresult);
            if (password_verify($password, $user['Password'])) {
                $_SESSION['username'] = $user['Username'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['role'] = $user['Role'];
                $_SESSION['id'] = $user['UserID'];
                $_SESSION["loggedin"] = true;
                $_SESSION['success'] = "You are now logged in";
                if($_SESSION['role'] == "Buyer"){
                    header('location: BuyerPortal.php');
                  }else{
                    header('location: SellerPortal.php');
                  }
              }else {
                array_push($errors, "Invalid password");
              }
        }else{
            array_push($errors, "Invalid email");
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

    <div class="row mt-3 justify-content-center">
        <h1>Welcome to the Auction System!</h1>
    </div>
    
    <div class="row justify-content-center">
        <h2>User Login</h2>
    </div>
    

    <form method="post" action="login.php">
        <div class="form-group mt-3" >
        <div class="mx-auto" style="width: 300px;">

            <label for="InputUsername">Username: </label>
    
            <input type="text" class="form-control" id="InputUsername" name="username" placeholder="Enter username" required>
    
        </div>
        </div>
        <div class="form-group">
        <div class="mx-auto" style="width: 300px;">

            <label for="InputPassword">Password</label>
            <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password" required>
        </div>


        <div class="form-group mt-3">
            <button type="submit" class="btn btn-outline-primary" name="login-button">Submit</button>
        </div>
    </form>

    <div class="row justify-content-center">
        <a href="Registration.php"><button type="button" class="btn btn-outline-info">No account? Register</button></a>
    </div>
    

    </div>

</body>