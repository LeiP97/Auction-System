<?php

require 'includes/server.php';

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true ) {
        header('location: login.php');
    } else if($_SESSION['role'] == 'Buyer'){
        header('location: BuyerPortal.php');
    }

    $getcategorysql = "SELECT `Name` FROM `Category`";
    $category= $db->query($getcategorysql);
    
    if (isset($_POST['create'])){
        
        $creatorID = $_SESSION["id"];
        $name = mysqli_real_escape_string($db, htmlspecialchars($_POST['name']));
        $description = mysqli_real_escape_string($db, htmlspecialchars($_POST['description']));
        $startprice = mysqli_real_escape_string($db, htmlspecialchars($_POST['startprice']));
        $reserveprice = mysqli_real_escape_string($db, htmlspecialchars($_POST['reserveprice']));
        $datetime = htmlspecialchars($_POST['datetime']);
        
        $categoryidsql = "SELECT `CategoryID` FROM `Category` WHERE `Name` ='".$_POST['category']."'";
        $categoryidresult= $db->query($categoryidsql);
        $categories = $categoryidresult->fetch_assoc();
        $categoryID = $categories['CategoryID'];

        $insertquery = "INSERT INTO `Item`(`CreatorID`, `Name`, `Description`, `CategoryID`, `DateCreated`, `DateExpires`, `Ended`, `StartingPrice`, `ReservePrice`) 
                    VALUES ('$creatorID', '$name', '$description', '$categoryID', now(), '$datetime', b'0', '$startprice', '$reserveprice') ";

        $db->query($insertquery);

        header('location: SellerPortal.php');
    }


require 'includes/header.php';

?>

<body>
    <div class="container mt-3">
        <div class="row">
            <h1>Create a new item auction</h1>
        </div>

        <form method="post" action="SellerCreate.php" oninput='reserveprice.setCustomValidity(reserveprice.value < startprice.value ? "Reserve price must be greater than start price" : "")'>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="ItemName">Item Name</label>
                <input type="text" class="form-control" id="ItemName" placeholder="Item Name" name="name" required>
                </div>                
            </div>
            
            <div class="form-row">

                <div class="form-group col-md-6">
                    <h5 class="form-text text-muted">Select Category:</h5>
                    <div class="form-group">

                    <?php

                        while ($row = $category->fetch_assoc()){

                        ?>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" id="inlineRadio" value=<?php echo $row['Name']; ?> required>
                                <label class="form-check-label" for="inlineRadio"><?php echo $row['Name']; ?></label>
                            </div>

                        <?php

                        }

                        ?>
                </div>

                </div>

            </div>

            <div class="form-group">
                <label for="inputDescription">Item Description</label>
                <input type="text" class="form-control" id="inputDescription" name='description' placeholder="Item Description" required>
            </div>

            <div class="form-group">
                <label for="inputStartPrice">Starting Price</label>
                <input type="number" min='1' class="form-control" step="any" id="inputStartPrice" name="startprice" placeholder="More than £1" required>
            </div>

            <div class="form-group">
                <label for="inputReservePrice">Reserve Price</label>
                <input type="number" min='1' class="form-control" step="any" id="inputReservePrice" name="reserveprice" placeholder="More than £1" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="endDate">End Date</label>
                </div>
            </div>

            <div class="form-row" id="endDate">

                <div class="form-group col-2">
                    <input type="datetime-local" id="datetimeinput" name="datetime" required>
                </div>            

            </div>

            <button type="submit" class="btn btn-primary" name='create'>Submit</button>
            </form>


    </div>
</body>

<script>

window.addEventListener("load", function() {
    var now = new Date();
    var utcString = now.toISOString().substring(0,19);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    var localDatetime = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) +
                      utcString.substring(16,19);
    var datetimeField = document.getElementById("datetimeinput");
    datetimeField.min = localDatetime;
});

</script>