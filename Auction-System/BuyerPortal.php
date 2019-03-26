<?php

require 'includes/server.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
    header('location: login.php');
}else if($_SESSION['role'] == 'Seller'){
    header('location: SellerPortal.php');
}

    $getitemsql = "SELECT * FROM `Item`";
    if(isset($_GET['CategoryId'])){
        $getitemsql = "SELECT * FROM `Item` WHERE CategoryID =". $db->real_escape_string($_GET['CategoryId']);
    }
	
    $allitems = $db->query($getitemsql);
    
    $getbiddedsql = "SELECT DISTINCT `ItemID`, `Name`, `Description`, `CategoryID` FROM `ItemBid`, `Item` WHERE `BidItemID` = `ItemID` AND `BidUserID` =".$_SESSION["id"];
    $biddeditems = $db->query($getbiddedsql);

    $getwatchedsql = "SELECT DISTINCT `ItemID`, `Name`, `Description`, `CategoryID` FROM `ItemWatch`, `Item` WHERE `WatchItemID` = `ItemID` AND `WatchUserID` =".$_SESSION["id"];
    $watcheditems = $db->query($getwatchedsql);

    $getcategorysql = "SELECT * FROM `Category`";
    $category= $db->query($getcategorysql);

    $getrecommendedsql = "SELECT `ItemID`, `Name`, `Description`, `CategoryID` FROM `Item` WHERE `ItemID` IN 
            (SELECT DISTINCT `BidItemID` FROM `ItemBid` WHERE `BidUserID` IN 
            (SELECT DISTINCT `BidUserID`FROM `ItemBid` WHERE `BidItemID` IN 
            (SELECT DISTINCT `BidItemID` FROM `ItemBid` WHERE `BidUserID` = ".$_SESSION["id"].") AND `BidUserID` <> ".$_SESSION["id"]."))";
    $recommendeditems = $db->query($getrecommendedsql);


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
            <div class="col-6">
            <div class="input-group mb-3">
                <input type="text" id="item-search" class="form-control" placeholder="Search for an item" aria-label="Search for an item" aria-describedby="button-addon2">
            </div>
            </div>
            <div class="col-4">
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Category
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="BuyerPortal.php">All</a>
                        <?php

                        while ($row = $category->fetch_assoc()){

                        ?>
                            <a class="dropdown-item" href="BuyerPortal.php?CategoryId=<?php echo $row['CategoryID']; ?>"><?php echo $row['Name']; ?></a>
                        <?php

                            }

                        ?>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <a href="logout.php"><button type="button" class="btn btn-outline-dark">Logout</button></a>
            </div>
        </div>
    </div>

    <div class="container mt-3">

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">All items</a>
                <a class="nav-item nav-link" id="nav-bidded-tab" data-toggle="tab" href="#nav-bidded" role="tab" aria-controls="nav-bidded" aria-selected="false">Bidded</a>
                <a class="nav-item nav-link" id="nav-watched-tab" data-toggle="tab" href="#nav-watched" role="tab" aria-controls="nav-watched" aria-selected="false">Watched</a>
                <a class="nav-item nav-link" id="nav-recommended-tab" data-toggle="tab" href="#nav-recommended" role="tab" aria-controls="nav-recommended" aria-selected="false">Recommended</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">

                <div class="container mt-3">

                    <div class="row">

                    
                    <?php

                    while ($row = $allitems->fetch_assoc()){

                    ?>
                        
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="images/Auction.jpg" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['Name']; ?></h5>
                                <p class="card-text"><?php echo $row['Description']; ?></p>
                                <a href="BuyerItem.php?ItemId=<?php echo $row['ItemID']; ?>" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    

                    <?php

                    }

                    ?>

                    </div>

                </div> 
            </div>

            <div class="tab-pane fade" id="nav-bidded" role="tabpanel" aria-labelledby="nav-bidded-tab">

                <div class="container mt-3">
                    <div class="row">
                    <?php

                        if(mysqli_num_rows($biddeditems) > 0){

                        while ($row = $biddeditems->fetch_assoc()){

                        ?>
                            
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="images/Auction.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['Name']; ?></h5>
                                    <p class="card-text"><?php echo $row['Description']; ?></p>
                                    <a href="BuyerItem.php?ItemId=<?php echo $row['ItemID']; ?>" class="btn btn-primary">See Details</a>
                                </div>
                            </div>


                        <?php

                        }
                    }

                        ?>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="nav-watched" role="tabpanel" aria-labelledby="nav-watched-tab">

                <div class="container mt-3">
                <div class="row">
                    <?php

                        if(mysqli_num_rows($watcheditems) > 0){

                        while ($row = $watcheditems->fetch_assoc()){

                        ?>
                            
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="images/Auction.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['Name']; ?></h5>
                                    <p class="card-text"><?php echo $row['Description']; ?></p>
                                    <a href="BuyerItem.php?ItemId=<?php echo $row['ItemID']; ?>" class="btn btn-primary">See Details</a>
                                </div>
                            </div>


                        <?php

                        }
                    }

                        ?>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="nav-recommended" role="tabpanel" aria-labelledby="nav-recommended-tab">

                <div class="container mt-3">
                <div class="row">
                    <?php

                        if(mysqli_num_rows($recommendeditems) > 0){

                        while ($row = $recommendeditems->fetch_assoc()){

                        ?>
                            
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="images/Auction.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['Name']; ?></h5>
                                    <p class="card-text"><?php echo $row['Description']; ?></p>
                                    <a href="BuyerItem.php?ItemId=<?php echo $row['ItemID']; ?>" class="btn btn-primary">See Details</a>
                                </div>
                            </div>


                        <?php

                        }
                    }

                        ?>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>


<?php require 'includes/footer.php'; ?>