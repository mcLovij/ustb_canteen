<?php

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

// Include config.php to establish database connection
require_once "config.php";

// Get the username from the session
$userName = $_SESSION['userName'];

// Fetch orders by student name with food names and canteen names
$sql = "SELECT sc.chooseId, fl.foodName, sc.chooseTime, l.canteenName, sc.quantity, fl.foodImage,fl.foodPrice
        FROM `shopping_cart` sc
        INNER JOIN `food_list` fl ON sc.foodId = fl.foodId
        INNER JOIN `location` l ON sc.canteenId = l.canteenId
        WHERE sc.`userName`='$userName'";
$result = $conn->query($sql);
?>
<h2>Shopping Cart</h2>
<table border="1">
    <tr>
        <th>Choose ID</th>
        <th>Food Name</th>
        <th>Food Image</th>
<!--        <th>Time</th>-->
        <th>Canteen Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Price</th>
        <th>Action</th> <!-- New column for delete action -->
    </tr>
    <?php
    $totalAllItemsPrice = 0; // Initialize total price for all items
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["chooseId"] . "</td>";
            echo "<td>" . $row["foodName"] . "</td>";
            echo "<td><img src='" . $row["foodImage"] . "' width='50' height='50'></td>";
//            echo "<td>" . $row["chooseTime"] . "</td>";
            echo "<td>" . $row["canteenName"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>" . $row["foodPrice"] . "</td>"; // Display item price
            $totalPrice = $row["foodPrice"] * $row["quantity"]; // Calculate total price
            echo "<td>" . $totalPrice . "</td>"; // Display total price
            $totalAllItemsPrice += $totalPrice; // Add current item's total price to total all items price
            // Add a delete button in each row
            echo "<td><form method='post' action='deleteShoppingCart.php'><input type='hidden' name='chooseId' value='" . $row["chooseId"] . "'><input type='submit' value='Delete' onclick=\"return confirm('Are you sure you want to delete this order?');\"></form></td>";
            echo "</tr>";
        }

        // Add the last row for total price
        echo "<tr>";
        echo "<td colspan='7'><strong>Total:</strong></td>";
        echo "<td><strong>" . $totalAllItemsPrice . "</strong></td>"; // Empty column for action
        echo "</tr>";


    } else {
        echo "<tr><td colspan='7'>No orders found</td></tr>";
    }
    ?>
</table>

<!-- Add this hidden div for the pop-up dialog -->
<div id="paymentPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 1px solid black;">
    <h3>Enter Payment Code</h3>
    <input type="text" id="paymentCodeInput" placeholder="Enter payment code">
    <button id="confirmPaymentButton">Confirm</button>
    <button id="cancelPaymentButton">Cancel</button>
</div>

<!-- Add the payment button -->
<button id="paymentButton">Make Payment</button>

<script>
    document.getElementById("paymentButton").addEventListener("click", function() {
        // Display the payment popup
        document.getElementById("paymentPopup").style.display = "block";
    });

    document.getElementById("confirmPaymentButton").addEventListener("click", function() {
        var paymentCode = document.getElementById("paymentCodeInput").value;
        if (paymentCode !== "") {
            // Send an AJAX request to check if the payment code exists in the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "checkPaymentCode.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.valid) {
                            // If the payment code is valid, perform the action
                            window.location.href = "addToOrder.php";
                        } else {
                            alert("Invalid payment code. Please try again.");
                        }
                    } else {
                        alert("Error: Unable to check payment code.");
                    }
                }
            };
            xhr.send("paymentCode=" + encodeURIComponent(paymentCode));
        }
        // Hide the payment popup
        document.getElementById("paymentPopup").style.display = "none";
    });

    document.getElementById("cancelPaymentButton").addEventListener("click", function() {
        // Hide the payment popup
        document.getElementById("paymentPopup").style.display = "none";
    });

</script>
