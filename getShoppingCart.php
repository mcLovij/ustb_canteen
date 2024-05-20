<?php
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}
require_once "config.php";
$userName = $_SESSION['userName'];
$sql = "SELECT sc.chooseId, fl.foodName, sc.chooseTime, l.canteenName, sc.quantity, fl.foodImage,fl.foodPrice,fl.foodDetail
        FROM `shopping_cart` sc
        INNER JOIN `food_list` fl ON sc.foodId = fl.foodId
        INNER JOIN `location` l ON sc.canteenId = l.canteenId
        WHERE sc.`userName`='$userName'";
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
    <input type="hidden" name="totalCheckedItemsPrice" value="<?php echo $totalCheckedItemsPrice; ?>">

    <table border="1">
        <tr>
            <th>Check</th>
            <th>Food Name</th>
            <th>Food Detail</th>
            <th>Food Image</th>
            <th>Canteen Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
            <th>Delete</th>
        </tr>
        <?php
        $totalCheckedItemsPrice = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='checkedItems[]' value='" . $row["chooseId"] . "'></td>"; // Checkbox
                echo "<td>" . $row["foodName"] . "</td>";
                echo "<td>" . $row["foodDetail"] . "</td>";
                echo "<td><img src='" . $row["foodImage"] . "' width='100' ></td>";
                echo "<td>" . $row["canteenName"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["foodPrice"] . "</td>";
                $totalPrice = $row["foodPrice"] * $row["quantity"];
                echo "<td class='total-price'>" . $totalPrice . "</td>";
                echo "<td><button type='button' onclick='deleteItem(" . $row["chooseId"] . ")'>Delete</button></td>";
                echo "</tr>";
                $totalCheckedItemsPrice += $totalPrice;
            }
            // Update the value of the hidden input field with the calculated total price
            echo "<input type='hidden' name='totalCheckedItemsPrice' value='" . $totalCheckedItemsPrice . "'>";
            echo "<tr>";
            echo "<td colspan='8'><strong>Total for Checked Items:</strong></td>";
            echo "<td id='total-checked-items-price'>￥<strong>" . $totalCheckedItemsPrice . "</strong></td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='9'>No orders found</td></tr>";
        }
        ?>
    </table>
    <button type="button" onclick="openPaymentModal()">Select Payment Type</button>
</form>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <p>Total Price: ￥<span id="total-price-in-modal"><?php echo $totalCheckedItemsPrice; ?></span></p>
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
        <button onclick="closePaymentModal()">取消</button>
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
            const row = checkedItem.closest('tr');
            const priceCell = row.querySelector('.total-price');
            const totalPrice = parseFloat(priceCell.textContent);
            totalCheckedItemsPrice += totalPrice;
        });
        document.getElementById('total-checked-items-price').textContent = totalCheckedItemsPrice.toFixed(2);
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

    // Update total price when the document is loaded
    document.addEventListener('DOMContentLoaded', function () {
        updateTotalPrice();
    });
</script>




<script>
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
                var row = document.querySelector('input[value="' + chooseId + '"]').closest('tr');
                row.remove();
                let totalCheckedItemsPrice = 0;
                document.querySelectorAll('input[name="checkedItems[]"]:checked').forEach(checkedItem => {
                    const priceCell = checkedItem.closest('tr').querySelector('.total-price');
                    const totalPrice = parseFloat(priceCell.textContent);
                    totalCheckedItemsPrice += totalPrice;
                });
                document.getElementById('total-checked-items-price').textContent = totalCheckedItemsPrice.toFixed(2);
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