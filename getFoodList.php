<style>
    .star {
        font-size: 20px;
        color: yellow;
        -webkit-text-stroke-width: 0.5px;
        -webkit-text-stroke-color: black;
        cursor: pointer;
    }

    .star-outline {
        color: transparent;
        -webkit-text-stroke-width: 0.5px;
        -webkit-text-stroke-color: black;
    }

    #orderPopup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid black;
        z-index: 1000;
        display: none;
    }


    .food-list {
        width: 85%;
        display: flex;
        flex-direction: column;
        margin: auto;
        text-align: left;
    }

    .food-list-title {
        margin: auto;
        font-weight: bold;
        font-size: 20px;
        padding: 15px;
    }

    .filter {
        margin-bottom: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .filter_button {
        display: inline-block;
        padding: 8px 16px;
        margin: 0 5px;
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f2f2f2;
        transition: background-color 0.3s;
    }

    .filter_button:hover {
        background-color: #e0e0e0;
    }

    .filter_active {
        background-color: #395039;
        color: white;
    }
    .filter_button input{
        margin-left: auto;
    }

    #searchInput {
        /*height: 46px;*/
        border-radius: 6px;
        font-size: 15px;
        border: 0.5px solid lightgrey;
        padding: 8px 16px;
        margin-left: auto;
        background-image: linear-gradient(135deg, #ffd6d6 40%, #eca2ca);
        /*margin-bottom: 20px;*/
    }

    .allFoodsContainer {
        width: 85%;
        margin: auto;
        display: flex;
        flex-wrap: wrap;
        /*justify-content: space-between;*/
    }


    .food-item {
        width: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ccc;
        /*padding: 15px;*/
        margin-bottom: 15px;
        margin-right: 15px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .food-item img {
        width: 100%;
    }

    .food-details {
        flex: 1;
        width: 100%;
    }

    .food-name {
        color: #0056b3;
        font-weight: bold;
        padding: 5px  15px;
    }

    .food-detail {
        width: 85%;
        margin: 5px 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .food-location {
        width: 85%;
        margin: 5px 15px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    .food-rate{
        color: #e1840e;
        font-weight: bold;
    }

    .food-action {
        width: 85%;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 0 15px 15px 15px;
        justify-content: space-between;
    }

    .food-action span,
    .food-action div,
    .food-action button {
        /*margin-left: 20px;*/
    }
</style>

<script>
    function toggleFavorite(foodId, status) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var star = document.getElementById('star_' + foodId);
                if (xhr.responseText === '1') {
                    star.classList.remove('star-outline');
                    star.classList.add('star');
                } else {
                    star.classList.remove('star');
                    star.classList.add('star-outline');
                }
                window.location.reload();
            }
        };
        xhr.open("POST", "toggleFavorite.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("foodId=" + foodId + "&status=" + status);
    }

    function addOrder() {
        var foodId = document.getElementById('orderPopup').getAttribute('data-food-id');
        var locationId = parseInt(document.getElementById('canteenId').textContent);
        var quantity = document.getElementById('quantityInput').value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
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
        document.getElementById('orderPopup').style.display = 'block';
        document.getElementById('foodImage').src = foodImage;
        document.getElementById('foodName').textContent = foodName;
        document.getElementById('foodRate').textContent = foodRate + "分";
        document.getElementById('foodPrice').textContent = "￥" + foodPrice;
        document.getElementById('canteenName').textContent = canteenName;
        document.getElementById('canteenId').textContent = canteenId;
        document.getElementById('orderPopup').setAttribute('data-food-id', foodId);
    }

    function filterByName() {
        var input = document.getElementById("searchInput").value.toUpperCase();
        var items = document.querySelectorAll(".food-item");
        items.forEach(function(item) {
            var foodName = item.querySelector(".food-name").textContent.toUpperCase();
            item.style.display = foodName.indexOf(input) > -1 ? "" : "none";
        });
    }

    function showAllRows() {
        var items = document.querySelectorAll(".food-item");
        items.forEach(function(item) {
            item.style.display = "";
        });
        document.querySelectorAll('.filter_button').forEach(button => button.classList.remove('filter_active'));
        document.querySelector('.filter_button:first-child').classList.add('filter_active');
    }

    function filterTableByCanteen(canteenName, button) {
        var items = document.querySelectorAll(".food-item");
        items.forEach(function(item) {
            var canteenCell = item.querySelector(".canteen-name");
            var rowCanteenName = canteenCell.textContent;
            item.style.display = rowCanteenName === canteenName ? "" : "none";
        });
        document.querySelectorAll('.filter_button').forEach(button => button.classList.remove('filter_active'));
        button.classList.add('filter_active');
    }
</script>
<div class="food-list">
    <div class="food-list-title">食物清单</div>
    <?php
    require_once "config.php";

    // Fetch unique canteen names
    $sql = "SELECT DISTINCT canteenName FROM location";
    $result = $conn->query($sql);
    $canteenNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $canteenNames[] = $row['canteenName'];
        }
    }

    // Generate buttons for each canteen
    echo "<div class='filter'><div onclick=\"showAllRows()\" class=\"filter_button filter_active\">所有的</div>";
    foreach ($canteenNames as $canteenName) {
        echo "<div onclick=\"filterTableByCanteen('$canteenName', this)\" class=\"filter_button\">$canteenName</div>";
    }
    ?>
<!--    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">-->
    </div>
</div>

<div id="allFoodsContainer" class="allFoodsContainer">
    <?php
    require_once "config.php";

    // Fetch food list with status for the logged-in user
    $sql = "SELECT food_list.*, location.canteenName AS canteenName, 
        IF(student_favorite.userName IS NULL, 0, 1) AS status
        FROM food_list
        LEFT JOIN location ON food_list.canteenId = location.canteenId
        LEFT JOIN student_favorite ON food_list.foodId = student_favorite.foodId 
        AND student_favorite.userName = '$userName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='food-item'>";
            echo "<img src='" . $row['foodImage'] . "' alt='" . $row['foodName'] . "'>";
            echo "<div class='food-details'>";
            echo "<div class='food-name'>" . $row['foodName'] . "</div>";
            echo "<div class='food-detail'>" . $row['foodDetail'] . "</div>";
            echo "<div class='food-location'>";
            echo "<div class='canteen-name'>" . $row['canteenName'] . "</div>";
            echo "<div class='floor'>" . $row['floor'] . "层</div>";
            if ($row['status'] == 1) {
                echo "<span id='star_" . $row['foodId'] . "' class='star' onclick='toggleFavorite(" . $row['foodId'] . ", 0)'>&#9733;</span>";
            } else {
                echo "<span id='star_" . $row['foodId'] . "' class='star-outline' onclick='toggleFavorite(" . $row['foodId'] . ", 1)'>&#9733;</span>";
            }
            echo "</div>";
            echo "</div>";
            echo "<div class='food-action'>";
            echo "<div class='food-rate'>" . $row['foodRate'] . "分</div>";
            echo "<div class='food-price'>￥" . $row['foodPrice'] . "</div>";
            echo "<button onclick='showOrderPopup(" . $row['foodId'] . ", \"" . $row['foodName'] . "\", \"" . $row['foodImage'] . "\", \"" . $row['foodRate'] . "\", " . $row['foodPrice'] . ", \"" . $row['canteenName'] . "\", \"" . $row['canteenId'] . "\")'>Order</button>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<div>No food available</div>";
    }
    ?>
</div>

<div id="orderPopup">
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
