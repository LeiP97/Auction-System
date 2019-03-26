
<?php

require 'includes/server.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true ) {
    header('location: login.php');
} else if($_SESSION['role'] == 'Buyer'){
    header('location: BuyerPortal.php');
}

require 'includes/CheckingAuction.php';

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

            <div class="col-6">
            <h5>Item Description: </h5>
                <p><?php echo $item['Description']; ?></p>
            </div>
        </div>

        <div class="row mt-5">
            <h5><span class="badge badge-info">Starting Price:</span>  £<?php echo $item['StartingPrice']; ?></h5>
        </div>
        <div class="row">
            <h5><span class="badge badge-info">Reserved Price:</span> £<?php echo $item['ReservePrice']; ?></h5>
        </div>
        <div class="row">
            <h5><span class="badge badge-info">Starting Date:</span> <?php echo $item['DateCreated']; ?></h5>
        </div>
        <div class="row">
            <h5><span class="badge badge-info">Ending Date:</span> <?php echo $item['DateExpires']; ?></h5>
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

                    <?php

                    }
                }

                    ?>
            </tbody>
        </table>
        </div>
    </div>
</body>