<?php
//session_start();
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
<form method="post" action="process_payment.php">
    <div class="cart-container">
        <div class="cart-container-title">购物车</div>
        <?php
        $totalCheckedItemsPrice = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalPrice = $row["foodPrice"] * $row["quantity"];
                echo "<div class='cart-item'>";
                echo "<div class='cart-item-checkbox'><input type='checkbox' name='checkedItems[]' value='" . $row["chooseId"] . "'></div>"; // Checkbox
                echo "<div class='cart-item-image'><img src='" . $row["foodImage"] . "' ></div>";
                echo "<div class='cart-item-details'>";

                echo "<div class='cart-name-des'>";
                echo "<div class='cart-item-name'>" . $row["foodName"] . "</div>";
                echo "<div class='cart-item-description'>" . $row["foodDetail"] . "</div>";
                echo "</div>";
                echo "<div class='cart-item-canteen'>" . $row["canteenName"] . "</div>";
                echo "<div class='cart-item-quantity'>Quantity: " . $row["quantity"] . "</div>";
                echo "<div class='cart-item-price'>Price: ￥" . $row["foodPrice"] . "</div>";
                echo "<div class='cart-item-total-price'>Total: ￥<span class='total-price'>" . $totalPrice . "</span></div>";
                echo "</div>";

                echo "<div class='cart-item-delete'><button class='delete' type='button' onclick='deleteItem(" . $row["chooseId"] . ")'>Delete</button></div>";
                echo "</div>";
            }
            echo "<input type='hidden' id='totalCheckedItemsPrice' name='totalCheckedItemsPrice' value='0'>";
            echo "<div class='cart-total'>";
            echo "<div>";
            echo "<strong>Total for Checked Items: ";
            echo "</div>";
            echo "<div>";
            echo "￥<span id='total-checked-items-price'>0</strong></span>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='cart-empty'>PLease add item into cart first!</div>";
        }
        ?>
    </div>
    <div class="payment-button"><button type="button"  onclick="openPaymentModal()">Select Payment Type</button></div>

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
        <button class="delete" onclick="closePaymentModal()">Cancel</button>
    </div>
</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this item?</p>
        <button  class="delete" onclick="confirmDelete()">Yes</button>
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
<style>
</style>
