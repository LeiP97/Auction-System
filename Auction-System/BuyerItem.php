
<?php

require 'includes/server.php';

require 'includes/CheckingAuction.php';


    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
        header('location: login.php');
    }else if($_SESSION['role'] == 'Seller'){
        header('location: SellerPortal.php');
    }

    if (isset($_POST['bidbutton'])){
        $biduserID = $_SESSION['id'];
        $biditemID = $_GET['ItemId'];
        $bidprice = htmlspecialchars($_POST['bidprice']);

        $insertbidsql = "INSERT INTO `ItemBid`(`BidUserID`, `BidItemID`, `BidPrice`, `BidTime`) VALUES ('$biduserID','$biditemID','$bidprice',now())";
        $db->query($insertbidsql);

        $getwatchuserssql = "SELECT `UserID`, `Username`, `Email` FROM `User`, `ItemWatch` WHERE `WatchItemID` = ".$_GET['ItemId']." AND `UserID` = `WatchUserID`";
        $watchuserresult = $db->query($getwatchuserssql);
        
        while($user = $watchuserresult->fetch_assoc()){
            $username = $user['Username'];
            $email = $user['Email'];
            $subject = "Online Auction System: New bid for ".$item['Name'];

            $checkbiddedsql = "SELECT `ItemBidID` FROM `ItemBid` WHERE `BidUserID`=".$user['UserID']." AND `BidItemID` = ".$_GET['ItemId']." LIMIT 1";
            $checkbiddedresult = $db->query($checkbiddedsql);
            if(mysqli_num_rows($checkbiddedresult) == 1){
                $msg = "Hey ".$username."! You have just been outbid for ".$bidprice." on the ".$item['Name']." you are watching!";
            }else{
                $msg = "Hey ".$username."! The ".$item['Name']." that you are watching has just been bidded for ".$bidprice."!";
            }
            mail($email, $subject, $msg);
        
        }

        echo "<meta http-equiv='refresh' content='0'>";

    }

    if (isset($_POST['watchbutton'])){
        $watchuserID = $_SESSION['id'];
        $watchitemID = $_GET['ItemId'];

        $insertwatchsql = "INSERT INTO `ItemWatch`(`WatchUserID`, `WatchItemID`, `StartingWatch`) VALUES ('$watchuserID','$watchitemID ',now())";
        $db->query($insertwatchsql);

        echo "<meta http-equiv='refresh' content='0'>";
    }
   


require 'includes/header.php';

?>


<body>
    
    <div class="container mt-3">

    <?php

        if($auctionEnded == true){

    ?>

    
    <div class="row justify-content-center">
            <div class="alert alert-success" role="alert">
                This item's auciton has ended! 
                <?php
                    echo $msg;
                ?>
            </div>
    </div>

        <?php

        }

        ?>

        <div class="row">

            <div class="col-2">
                <img src="images/Auction.jpg" alt="item image" class="img-thumbnail">
            </div>

            <div class="col-4">
                <h1><?php echo $item['Name']; ?></h1>
            </div>

            <div class="col-4">
                <h5>Item Description: </h5>
                <p><?php echo $item['Description']; ?></p>
            </div>

            <div class="col-2">
                <a href="BuyerPortal.php"><button class="btn btn-outline-success">Back</button></a>
            </div>

        </div>

        <div class="row mt-5">
            <h5><span class="badge badge-info">Starting Price: </span>   £<?php echo $item['StartingPrice']; ?></h5>
        </div>
        <div class="row">
            <h5><span class="badge badge-info">Starting Date: </span>     <?php echo $item['DateCreated']; ?></h5>
        </div>
        <div class="row">
            <h5><span class="badge badge-info">Ending Date: </span>       <?php echo $item['DateExpires']; ?></h5>
        </div>

        <div class="row  mt-5">
            <h1>Bidding History</h1>
        </div>
        <div class="row">
        <table class="table">
            <thead>
                <tr>
                <th scope="col">User</th>
                <th scope="col">Date</th>
                <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                

                <?php

                if(mysqli_num_rows($bidhistory) > 0){

                    while ($row = $bidhistory->fetch_assoc()){

                    ?>
                        
                        <tr>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['BidPrice']; ?></td>
                            <td><?php echo $row['BidTime']; ?></td>
                            </tr>
                        <tr>

                    <?php

                    }
                }

                    ?>

                
            </tbody>
            </table>
        </div>

        <?php

                if($auctionEnded == false){

        ?>

        <div class="row mt-5">
            <div class="col-4">
                <form method="post" action="BuyerItem.php?ItemId=<?php echo $item['ItemID']; ?>">
                    <div class="form-group">
                    <label for="InputBidPrice">Bid the item: </label>
                    <input type="number" min=<?php echo $maxBidPrice; ?> class="form-control" id="InputBidPrice" placeholder="Bid Price" name="bidprice" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary" name='bidbutton'>Submit</button>
                </form>
            </div>
            <div class="col-4">

            </div>
            <div class="col-4 mt-3">
                <form method="post" action="BuyerItem.php?ItemId=<?php echo $item['ItemID']; ?>">
                    <button type="submit" class="btn btn-outline-success" name="watchbutton">Watch Item</button>
                </form>
                
            </div>
        </div>

        <?php

                }

        ?>

    </div>

</body>