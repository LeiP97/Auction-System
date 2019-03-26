<?php

    date_default_timezone_set('Europe/London');

    $auctionEnded = false;

    $getitemsql = "SELECT * FROM `Item` WHERE ItemID =". $db->real_escape_string($_GET['ItemId']);	
    $result= $db->query($getitemsql);
    $item = $result->fetch_assoc();

    $getbidhistorysql = "SELECT `Username`, `BidPrice`, `BidTime` FROM `ItemBid`, `User` WHERE `BidItemID` = ". $_GET['ItemId']. " AND `UserID` = `BidUserID` ORDER BY `BidTime` DESC";
    $bidhistory= $db->query($getbidhistorysql);

    $maxpricesql = "SELECT MAX(`BidPrice`) AS maxprice FROM `ItemBid` WHERE `BidItemID` =". $_GET['ItemId'];
    $result3 = $db->query($maxpricesql);
    $price = $result3->fetch_assoc();
    $maxBidPrice = $item['StartingPrice']+1;
    if($price["maxprice"] != null){
        $maxBidPrice = $price["maxprice"]+1;
    }

    $dateEnded = strtotime($item["DateExpires"]);
    $currentTime = time();
    
    if($currentTime - $dateEnded >=0){
        if($maxBidPrice-1 > $item["ReservePrice"]){
            $getwinningusersql = "SELECT `Username`, `BidUserID` FROM `ItemBid`, `User` WHERE `UserID` = `BidUserID` AND `BidPrice` = ".($maxBidPrice-1);
            $result8= $db->query($getwinningusersql);
            $winninguser = $result8->fetch_assoc();
            $winninguserID = $winninguser["BidUserID"];
            $updateitemsql= "UPDATE `Item` SET `Ended`=b'1' ,`WinningUserID`=".$winninguserID." WHERE `ItemID`= ".$_GET['ItemId'];
            if($winninguserID == $_SESSION['id']){
                $msg ="You have won the bid for £".($maxBidPrice-1)."!";
            }else{
                $msg = $winninguser["Username"]." has won the bid for £".($maxBidPrice-1)."!";
            }           
        }else{
            $updateitemsql= "UPDATE `Item` SET `Ended`=b'1' WHERE `ItemID`= ".$_GET['ItemId'];
            $msg = "No one has won the auction. All bids below reserve price.";
        }
        $db->query($updateitemsql);
        $auctionEnded = true;
    }


?>