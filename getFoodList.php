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

    function redirectToFoodPage(foodId) {
        window.location.href = 'food_detail?id=' + foodId;
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
            echo "<div class='food-item-image'>";
            echo "<img src='" . $row['foodImage'] . "' alt='" . $row['foodName'] . "' onclick='redirectToFoodPage(" . $row['foodId'] . ")'>";
            echo "</div>";
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
            echo "<button onclick='showOrderPopup(" . $row['foodId'] . ", \"" . $row['foodName'] . "\", \"" . $row['foodImage'] . "\", \"" . $row['foodRate'] . "\", " . $row['foodPrice'] . ", \"" . $row['canteenName'] . "\", \"" . $row['canteenId'] . "\")'>+</button>";
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
    <button onclick="addOrder()">加入购物车</button>
    <button class="delete" onclick="hideOrderPopup()">取消</button>
</div>

