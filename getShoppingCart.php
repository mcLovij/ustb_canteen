<?php
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}
require_once "config.php";
$userName = $_SESSION['userName'];
$sql = "SELECT sc.chooseId, fl.foodName, sc.chooseTime, l.canteenName, sc.quantity, fl.foodImage, fl.foodPrice, fl.foodDetail,sc.chooseTime
        FROM `shopping_cart` sc
        INNER JOIN `food_list` fl ON sc.foodId = fl.foodId
        INNER JOIN `location` l ON sc.canteenId = l.canteenId
        WHERE sc.`userName`='$userName'
        ORDER BY sc.chooseTime DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Shopping Cart</h2>
<form method="post" action="process_payment.php">
    <div class="cart-container">
        <?php
        $totalCheckedItemsPrice = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalPrice = $row["foodPrice"] * $row["quantity"];
                echo "<div class='cart-item'>";
                echo "<div class='cart-item-checkbox'><input type='checkbox' name='checkedItems[]' value='" . $row["chooseId"] . "'></div>"; // Checkbox
                echo "<div class='cart-item-image'><img src='" . $row["foodImage"] . "' width='100'></div>";
                echo "<div class='cart-item-details'>";
                echo "<div class='cart-item-name'>" . $row["foodName"] . "</div>";
                echo "<div class='cart-item-description'>" . $row["foodDetail"] . "</div>";
                echo "<div class='cart-item-canteen'>" . $row["canteenName"] . "</div>";
                echo "<div class='cart-item-quantity'>Quantity: " . $row["quantity"] . "</div>";
                echo "<div class='cart-item-price'>Price: ￥" . $row["foodPrice"] . "</div>";
                echo "<div class='cart-item-total-price'>Total: ￥<span class='total-price'>" . $totalPrice . "</span></div>";
                echo "</div>";
                echo "<div class='cart-item-delete'><button type='button' onclick='deleteItem(" . $row["chooseId"] . ")'>Delete</button></div>";
                echo "</div>";
            }
            echo "<input type='hidden' id='totalCheckedItemsPrice' name='totalCheckedItemsPrice' value='0'>";
            echo "<div class='cart-total'>";
            echo "<strong>Total for Checked Items:</strong> ￥<span id='total-checked-items-price'>0</span>";
            echo "</div>";
        } else {
            echo "<div class='cart-empty'>PLease add item into cart first!</div>";
        }
        ?>
    </div>
    <button type="button" onclick="openPaymentModal()">Select Payment Type</button>
</form>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <p>Total Price: ￥<span id="total-price-in-modal">0</span></p>
        <p>Select Payment Type:</p>
        <?php
        require_once "config.php";
        $sql = "SELECT * FROM payment";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<button onclick=\"confirmPayment('" . $row['paymentType'] . "')\">" . $row['paymentType'] . "</button>";
            }
        } else {
            echo "No payment types available.";
        }
        ?>
        <button onclick="closePaymentModal()">Cancel</button>
    </div>
</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this item?</p>
        <button onclick="confirmDelete()">Yes</button>
        <button onclick="closeModal()">No</button>
    </div>
</div>

<script>
    function updateTotalPrice() {
        let totalCheckedItemsPrice = 0;
        document.querySelectorAll('input[name="checkedItems[]"]:checked').forEach(checkedItem => {
            const row = checkedItem.closest('.cart-item');
            const priceCell = row.querySelector('.total-price');
            const totalPrice = parseFloat(priceCell.textContent);
            totalCheckedItemsPrice += totalPrice;
        });
        document.getElementById('total-checked-items-price').textContent = totalCheckedItemsPrice.toFixed(2);
        document.getElementById('totalCheckedItemsPrice').value = totalCheckedItemsPrice.toFixed(2);
    }

    document.querySelectorAll('input[name="checkedItems[]"]').forEach(item => {
        item.addEventListener('change', function () {
            updateTotalPrice();
        });
    });

    function openPaymentModal() {
        const totalPrice = parseFloat(document.getElementById('total-checked-items-price').textContent);
        document.getElementById('total-price-in-modal').textContent = totalPrice.toFixed(2);
        document.getElementById('paymentModal').style.display = 'block';
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
    }

    function confirmPayment(paymentType) {
        console.log('Selected Payment Type:', paymentType);
        document.querySelector('form').submit();
        closePaymentModal();
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateTotalPrice();
    });

    function openModal() {
        document.getElementById('confirmationModal').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }
    function deleteItem(chooseId) {
        window.deleteItemId = chooseId;
        openModal();
    }
    function confirmDelete() {
        var chooseId = window.deleteItemId;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteShoppingCart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var row = document.querySelector('input[value="' + chooseId + '"]').closest('.cart-item');
                row.remove();
                updateTotalPrice();
            } else {
                console.log('Error deleting item');
            }
            closeModal();
        };
        xhr.send('chooseId=' + chooseId);
    }
</script>



</body>
<style>
    .cart-container {
        display: flex;
        flex-direction: column;
        /*gap: 10px;*/
    }

    .cart-item {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        padding: 10px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 5px auto;
        width: 85%;
    }

    .cart-item-checkbox,
    .cart-item-image,
    .cart-item-details,
    .cart-item-delete {
        margin-right: 15px;
    }

    .cart-item-details {
        flex-grow: 1;
    }

    .cart-total {
        font-weight: bold;
        margin-top: 20px;
        text-align: right;
    }

    .cart-empty {
        text-align: center;
        font-size: 18px;
        color: #999;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        border-radius: 15px;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        text-align: center;
    }

    .modal-content button {
        margin: 5px;
    }
</style>
</html>
