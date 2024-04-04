<table border="1">
    <thead>
    <tr>
        <th>Food Name</th>
        <th>Price</th>
        <th>Detail</th>
        <th>Rate</th>
        <th>Image</th>
    </tr>
    </thead>
    <tbody>
    <?php
    require_once "config.php";

    // Fetch favorite food list for the logged-in user
    $sql = "SELECT food_list.* FROM food_list INNER JOIN student_favorite ON food_list.foodId = student_favorite.foodId WHERE student_favorite.userName = '$userName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['foodName'] . "</td>";
            echo "<td>$" . $row['foodPrice'] . "</td>";
            echo "<td>" . $row['foodDetail'] . "</td>";
            echo "<td>" . $row['foodRate'] . "</td>";
            echo "<td><img style='height: 100px;' src='" . $row['foodImage'] . "' alt='Food Image'></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No favorite foods</td></tr>";
    }
    ?>
    </tbody>
</table>