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

    function addOrder() {
        var foodId = document.getElementById('orderPopup').getAttribute('data-food-id');
        var locationIdString = document.getElementById('canteenId').innerText;
        var locationId = parseInt(locationIdString);
        // var quantity = document.getElementById('quantityInput').value;
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

    function showOrderPopup(foodId, foodName, foodImage, foodRate, foodPrice, canteenName, canteenId) {
        // Show the pop-up div
        document.getElementById('orderPopup').style.display = 'block';

        // Populate food details
        document.getElementById('foodImage').src = foodImage;
        document.getElementById('foodName').textContent = foodName;
        document.getElementById('foodRate').textContent = foodRate;
        document.getElementById('foodPrice').textContent = foodPrice;
        document.getElementById('canteenName').textContent = canteenName;
        document.getElementById('canteenId').textContent = canteenId;

        // Pass foodId to the pop-up div (optional)
        document.getElementById('orderPopup').setAttribute('data-food-id', foodId);
    }

</script>


<?php
require_once "config.php";

//// Fetch unique canteen names
//$sql = "SELECT DISTINCT canteenName FROM location";
//$result = $conn->query($sql);
//$canteenNames = array();
//if ($result->num_rows > 0) {
//    while ($row = $result->fetch_assoc()) {
//        $canteenNames[] = $row['canteenName'];
//    }
//}
//
//// Generate buttons for each canteen
//echo "<div class='filter'><div onclick=\"showAllRows()\" class=\"filter_div filter_button\">Show All</div>";
//foreach ($canteenNames as $index => $canteenName) {
//    echo "<div onclick=\"filterTableByCanteen('$canteenName', this)\" class=\"filter_div filter_button\">$canteenName</div>";
//}
////echo "</div>";
//?>
<!-- Add the input field for food name search -->
<input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">

<script>
    // Function to filter table rows by food name
    function filterByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("allFoodsTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Index 1 is for food name column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</div>
<script>
    function showAllRows() {
        var rows = document.querySelectorAll("#allFoodsTable tbody tr");
        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }
        // Remove filter_active class from all buttons
        document.querySelectorAll('.filter_button').forEach(button => button.classList.remove('filter_active'));
        document.querySelector('.filter_button:first-child').classList.add('filter_active');
    }

    function filterTableByCanteen(canteenName, button) {
        var rows = document.querySelectorAll("#allFoodsTable tbody tr");
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var canteenCell = row.getElementsByTagName("td")[3];
            if (canteenCell) {
                var rowCanteenName = canteenCell.textContent || canteenCell.innerText;
                if (rowCanteenName === canteenName) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }
        // Remove filter_active class from all buttons and add it to the clicked button
        document.querySelectorAll('.filter_button').forEach(button => button.classList.remove('filter_active'));
        button.classList.add('filter_active'); // Add class to the clicked button
    }

    function filterTable() {
        var filterValue = document.getElementById('filterInput').value.toUpperCase();
        var rows = document.querySelectorAll("#allFoodsTable tbody tr");
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var canteenCell = row.getElementsByTagName("td")[3];
            if (canteenCell) {
                var canteenName = canteenCell.textContent || canteenCell.innerText;
                if (canteenName.toUpperCase().indexOf(filterValue) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }
    }
</script>


<table id="allFoodsTable" border="1">
    <thead>
    <tr>
        <th>Image</th>
        <th>Food Name</th>
        <th>Detail</th>
        <th>CanteenName</th>
        <th>Floor</th>
        <th>Rate</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!isset($_SESSION['staffUserName'])) {
        header("Location: login");
        exit();
    }
    $staffUserName = $_SESSION['staffUserName'];

    // Fetch food list with status for the logged-in user
    $sql = "SELECT food_list.*, 
       location.canteenName AS canteenName
FROM food_list
LEFT JOIN location ON food_list.canteenId = location.canteenId
WHERE food_list.canteenId = (SELECT staff_detail.canteenId FROM staff_detail WHERE staff_detail.staffUserName = '$staffUserName')
";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img style='height: 100px;' src='" . $row['foodImage'] . "' alt='" . $row['foodName'] . " '></td>";
            echo "<td>" . $row['foodName'] . "</td>";
            echo "<td>" . $row['foodDetail'] . "</td>";
            echo "<td>" . $row['canteenName'] . "</td>"; // Display canteenName instead of canteenId
            echo "<td>" . $row['floor'] . "层</td>";
            echo "<td>" . $row['foodRate'] . "分</td>";
            echo "<td>￥" . $row['foodPrice'] . "</td>";
            // Add button to add the item to the order list


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
    <p id="canteenName"></p>
    <p id="canteenId" style="display: none;"></p>
    <h3>Quantity : 1</h3>
    <input type="number" id="quantityInput" min="1" value="1" readonly style="display: none;">
    <br><br>
    <button onclick="addOrder()">Add to Order</button>
    <button onclick="hideOrderPopup()">Cancel</button>
</div>

