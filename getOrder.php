<?php
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";
$userName = $_SESSION['userName'];
$orderDetailsHTML = '';
$sql = "SELECT o.orderId, o.foodId, o.canteenId, o.status, o.quantity, fl.foodName, l.canteenName, fl.foodPrice,fl.foodImage,fl.foodDetail,oh.price, oh.orderTime, oh.totalItem
        FROM orders o
        INNER JOIN food_list fl ON o.foodId = fl.foodId
        INNER JOIN order_history oh ON o.orderId = oh.orderId
        INNER JOIN location l ON o.canteenId = l.canteenId
        WHERE o.userName = '$userName'
        ORDER BY oh.orderTime DESC";

$result = $conn->query($sql);

$currentOrderId = null;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($currentOrderId !== $row["orderId"]) {
            if ($currentOrderId !== null) {
                $orderDetailsHTML .= "</div>";
            }
            $orderDetailsHTML .= "<div class='order'>";
            // Output orderId, price, and time within one <div>
            $orderDetailsHTML .= "<div class='order-info'>";
            $orderDetailsHTML .= "<div>Order ID: " . $row["orderId"] . "</div>";
            $orderDetailsHTML .= "<div>Price: ￥" . $row["price"] . "</div>";
            $orderDetailsHTML .= "<div>Time: " . $row["orderTime"] . "</div>";
            $orderDetailsHTML .= "<div>Status: " . $row["status"] . "</div>";
            $orderDetailsHTML .= "<div>Total: " . $row["totalItem"] . "</div>";
            $orderDetailsHTML .= "</div>"; // Close order-info div
            $currentOrderId = $row["orderId"];
        }
        // Concatenate order details to the HTML string
        $orderDetailsHTML .= "<div class='order-item'>";
        $orderDetailsHTML .= "<div class='item1'>";
        $orderDetailsHTML .= "<div class='image'><img src='" . $row["foodImage"] . "' ></div>";
        $orderDetailsHTML .= "<div class='item2-1'>";
        $orderDetailsHTML .= "<div>" . $row["foodName"] . "</div>";
        $orderDetailsHTML .= "<div>" . $row["foodDetail"] . "</div>";
        $orderDetailsHTML .= "</div>";
        $orderDetailsHTML .= "<div class='item2-2'>" . $row["canteenName"] . "</div>";
        $orderDetailsHTML .= "<div class='item2-3'>";
        $orderDetailsHTML .= "<div>￥" . $row["foodPrice"] . "</div>";
        $orderDetailsHTML .= "<div>×" . $row["quantity"] . "</div>";
        $orderDetailsHTML .= "</div>";
        $orderDetailsHTML .= "</div>";
        $orderDetailsHTML .= "</div>";
    }
    $orderDetailsHTML .= "</div>";
} else {
    $orderDetailsHTML = "<div>No orders found for user: $userName</div>";
}
//echo "<div id='order-details'>$orderDetailsHTML</div>";
?>
<button onclick="toggleOrderDetails()">Show Order Details</button>

<div id="order-details-popup" class="order-details-popup">
    <div id="order-details-content" class="order-details-content">
        <div style="display: flex; align-items: center;">
            <div style="margin-right: auto">订单</div>
            <button class="close-btn" onclick="hideOrderDetails()">×</button>
        </div>
        <?php echo $orderDetailsHTML; ?>

    </div>
</div>

<script>
    function toggleOrderDetails() {
        var popup = document.getElementById("order-details-popup");
        popup.style.display = "block";
    }

    function hideOrderDetails() {
        var popup = document.getElementById("order-details-popup");
        popup.style.display = "none";
    }

</script>
<style>
    .order-details-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 9999;
        overflow: auto;
    }

    .order-details-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border: 1px solid black;
        padding: 20px ;
        width: 90%;
        max-height: 90vh;fpadd
        overflow: hidden; /* Hide overflow within the content */
        overflow-y: auto; /* Enable vertical scrolling */
        border-radius: 15px;
    }
    .order-details-content button{
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        margin-bottom: 10px;
    }





    .order {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 15px;
    }
    .order-info {
        border-bottom: 1px solid #ddd;
        display: flex;
        width: 100%;
        justify-content: space-between;
    }
    .order-item {
        border-bottom: 1px solid #ddd;
        padding: 10px;
    }
    .order-item .item1 {
        display: flex;
    }
    .order-item .item1 .image img {
        width: 100px;
        border-radius: 10px;
    }
    .order-item .item1 .item2-1
    ,.order-item .item1 .item2-3{
        display: flex;
        flex-direction: column;
        padding: 10px;
    }
    .order-item .item1 .item2-2{
        padding: 10px;
        margin-left: auto;
    }
    .order-item .item1 .item2-3{
        text-align: end;
    }
    .order-item .item1 .item2-1 div:last-child
    ,.order-item .item1 .item2-3 div:last-child{
        margin-top: auto;
    }
    .order-item .item1 .item2-1 div:last-child{
        max-width: 250px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .order-item:last-child {
        border-bottom: none;
    }
</style>
