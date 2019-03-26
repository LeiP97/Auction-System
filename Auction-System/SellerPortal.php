<?php

require 'includes/server.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true ) {
    header('location: login.php');
} else if($_SESSION['role'] == 'Buyer'){
    header('location: BuyerPortal.php');
}

$getallitemssql = "SELECT * FROM `Item` WHERE `CreatorID`="  . $_SESSION["id"];
	
$allitems = $db->query($getallitemssql);

require 'includes/header.php';

?>

<body>
    
<div class="container mt-3">


        <?php  if (isset($_SESSION['success'])) : ?>
            
            <div class="row justify-content-center">
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; 
                          unset($_SESSION['success']); ?>
                </div>
            </div>
            
        <?php  endif ?>

        <div class="row mb-5 justify-content-center">
            <span class="badge badge-success">Welcome! <?php echo $_SESSION['username']; ?></span>
        </div>

    <div class="row">
        <div class="col-10">
            <a href="SellerCreate.php"><button type="button" class="btn btn-outline-danger">Create Auction</button></a>
        </div>
        

        <div class="col-2 justify-content-end">
            <a href="logout.php"><button type="button" class="btn btn-outline-dark">Logout</button></a>
        </div>
    </div>
    
</div>

<div class="container mt-3">
    <div class="row">
        <h1>Your Items</h1>
    </div>
    
    <div class="row">
    <?php

        while ($row = $allitems->fetch_assoc()){

        ?>
            
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/Auction.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['Name']; ?></h5>
                    <p class="card-text"><?php echo $row['Description']; ?></p>
                    <a href="SellerItem.php?ItemId=<?php echo $row['ItemID']; ?>" class="btn btn-primary">See Details</a>
                </div>
            </div>

        <?php

        }

    ?>

        
        
    </div>
    
</div>

</body>