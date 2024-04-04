<style>
    .star {
        font-size: 20px;
        color: yellow;
        -webkit-text-stroke-width: 0.5px;
        -webkit-text-stroke-color: black;
    }

    .star-outline {
        color: transparent;
        -webkit-text-stroke-width: 0.5px;
        -webkit-text-stroke-color: black;
    }

    #orderPopup {
        position: fixed;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: white;
        padding: 20px;
        border: 1px solid black;
        z-index: 1000;
        display: none;
    }
</style>
<script>
    function toggleFavorite(foodId, status) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update star icon color based on response
                if (xhr.responseText == '1') {
                    document.getElementById('star_' + foodId).classList.remove('star-outline');
                    document.getElementById('star_' + foodId).classList.add('star-yellow');
                } else {
                    document.getElementById('star_' + foodId).classList.remove('star-yellow');
                    document.getElementById('star_' + foodId).classList.add('star-outline');
                }

                // Reload the page
                window.location.reload();
            }
        };
        xhr.open("POST", "toggleFavorite.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("foodId=" + foodId + "&status=" + status);
    }

    function showOrderPopup(foodId) {
        // Show the pop-up div
        document.getElementById('orderPopup').style.display = 'block';
        // Pass foodId to the pop-up div (optional)
        document.getElementById('orderPopup').setAttribute('data-food-id', foodId);
    }

    function addOrder() {
        var foodId = document.getElementById('orderPopup').getAttribute('data-food-id');
        var locationId = document.getElementById('locationSelect').value;
        var quantity = document.getElementById('quantityInput').value;

        // You can perform validation here for location and quantity

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page or update the order list as needed
                window.location.reload();
            }
        };
        xhr.open("POST", "addToCart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("foodId=" + foodId + "&locationId=" + locationId + "&quantity=" + quantity);
    }
    function hideOrderPopup() {
        document.getElementById('orderPopup').style.display = 'none';
    }
    function showOrderPopup(foodId, foodName, foodImage, foodRate, foodPrice) {
        // Show the pop-up div
        document.getElementById('orderPopup').style.display = 'block';

        // Populate food details
        document.getElementById('foodImage').src = foodImage;
        document.getElementById('foodName').textContent = foodName;
        document.getElementById('foodRate').textContent = foodRate;
        document.getElementById('foodPrice').textContent = foodPrice;

        // Pass foodId to the pop-up div (optional)
        document.getElementById('orderPopup').setAttribute('data-food-id', foodId);
    }

</script>
<table border="1">
    <thead>
    <tr>
        <th>Food Name</th>
        <th>Price</th>
        <th>Detail</th>
        <th>Rate</th>
        <th>Image</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
    require_once "config.php";

    // Fetch food list with status for the logged-in user
    $sql = "SELECT food_list.*, IF(student_favorite.userName IS NULL, 0, 1) AS status
                FROM food_list
                LEFT JOIN student_favorite ON food_list.foodId = student_favorite.foodId AND student_favorite.userName = '$userName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['foodName'] . "</td>";
            echo "<td>$" . $row['foodPrice'] . "</td>";
            echo "<td>" . $row['foodDetail'] . "</td>";
            echo "<td>" . $row['foodRate'] . "</td>";
            echo "<td><img style='height: 100px;' src='" . $row['foodImage'] . "' alt='". $row['foodName'] ." '></td>";
            if ($row['status'] == 1) {
                echo "<td><span id='star_" . $row['foodId'] . "' class='star star-yellow' onclick='toggleFavorite(" . $row['foodId'] . ", 0)'>&#9733;</span></td>";
            } else {
                echo "<td><span id='star_" . $row['foodId'] . "' class='star star-outline' onclick='toggleFavorite(" . $row['foodId'] . ", 1)'>&#9733;</span></td>";
            }
            // Add button to add the item to the order list
            echo "<td><button onclick='showOrderPopup(" . $row['foodId'] . ", \"" . $row['foodName'] . "\", \"" . $row['foodImage'] . "\", \"" . $row['foodRate'] . "\", " . $row['foodPrice'] . ")'>Order</button></td>";


            echo "</tr>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No food available</td></tr>";
    }
    ?>
    </tbody>
</table>

<div id="orderPopup"
     style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border: 1px solid #ccc;">
    <h3>Food Details</h3>
    <img id="foodImage" style="height: 100px;">
    <p id="foodName"></p>
    <p id="foodRate"></p>
    <p id="foodPrice"></p>
    <h3>Select Location</h3>
    <select id="locationSelect">
        <?php
        // Fetch locations
        $sql = "SELECT * FROM location";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['canteenId'] . "'>" . $row['canteenName'] . "</option>";
            }
        }
        ?>
    </select>
    <h3>Quantity</h3>
    <input type="number" id="quantityInput" min="1" value="1">
    <br><br>
    <button onclick="addOrder()">Add to Order</button>
    <button onclick="hideOrderPopup()">Cancel</button>
</div>

