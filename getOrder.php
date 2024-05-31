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

?>
<!--<button onclick="toggleOrderDetails()">Show Order Details</button>-->
<div class="order-details" id='order-details'>
    <div class="order-title">食物清单</div>
    <?php echo $orderDetailsHTML; ?>
</div>;
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
</style>
