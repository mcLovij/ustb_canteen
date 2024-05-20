<?php
// Include config.php to establish database connection
require_once "config.php";

// Get the username from the session
$userName = $_SESSION['userName'];

// Fetch recommended foodIds from the recommendation table
$sql = "SELECT foodId FROM recommendation";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array to store recommended foodIds
    $recommendedFoodIds = array();

    // Fetch recommended foodIds and store in the array
    while ($row = $result->fetch_assoc()) {
        $recommendedFoodIds[] = $row['foodId'];
    }

    // Fetch details for recommended food from food_list table
    $sql = "SELECT fl.foodName, fl.foodPrice,  fl.floor, fl.bannerImg ,l.canteenName
            FROM food_list fl
            JOIN recommendation r ON fl.foodId = r.foodId
            JOIN location l ON fl.canteenId =l.canteenId
            WHERE fl.foodId IN (" . implode(",", $recommendedFoodIds) . ")";
    $result = $conn->query($sql);

//    $sql = "SELECT fl.foodName, fl.foodPrice,  fl.floor, b.image ,l.canteenName
//            FROM food_list fl
//            JOIN banner b ON fl.foodId = b.foodId
//            JOIN location l ON fl.canteenId =l.canteenId
//            WHERE fl.foodId IN (" . implode(",", $recommendedFoodIds) . ")";
//    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display banner images with food details
        echo '<div class="slider-container">';
        echo '<div id="slider" class="slider">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="slide">';
            echo '<img src="' . $row['bannerImg'] . '" alt="Banner Image">';
            echo '<div class="food-details">';
            echo '<p>Name: ' . $row['foodName'] . ' Price: $' . $row['foodPrice'] . 'Canteen ID: ' . $row['canteenName'] . 'Floor: ' . $row['floor'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo "No banner images found for recommended food.";
    }
} else {
    echo "No recommendations found.";
}
?>


<style>
    .slider-container {
        position: relative;
        width: 70%;
    }

    .slider {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .slide {
        display: none;
        width: 100%;
    }

    .slide img {
        width: 100%;
        height: auto;
    }
    .slide .food-details {
        display: flex;
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.7);
    }

    .slider .slide:first-child {
        display: block;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var slideIndex = 0;
        showSlides();

        function showSlides() {
            var slides = document.getElementsByClassName("slide");
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 5000); // Change image every 5 seconds
        }
    });
</script>
