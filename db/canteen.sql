/*
 Navicat Premium Data Transfer

 Source Server         : MySql
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : localhost:3306
 Source Schema         : canteen

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 20/05/2024 11:13:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for food_list
-- ----------------------------
DROP TABLE IF EXISTS `food_list`;
CREATE TABLE `food_list`  (
  `foodId` int(0) NOT NULL AUTO_INCREMENT,
  `foodName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foodDetail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `foodImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `canteenId` int(0) NULL DEFAULT NULL,
  `floor` int(0) NULL DEFAULT NULL,
  `foodRate` float NULL DEFAULT NULL,
  `foodPrice` decimal(10, 2) NOT NULL,
  `bannerImg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`foodId`) USING BTREE,
  INDEX `canteenId`(`canteenId`) USING BTREE,
  CONSTRAINT `food_list_ibfk_1` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of food_list
-- ----------------------------
BEGIN;
INSERT INTO `food_list` VALUES (1, 'Pizza', 'Classic Italian pasta dish with creamy sauce and bacon.', 'img/food/pizza.jpg', 1, 1, 4.5, 12.50, 'img/banner/pizza.jpg'), (2, '魔饭台式卤肉饭', '魔饭台式卤肉饭魔饭台式卤肉饭魔饭台式卤肉饭', 'img/food/mofantaishi.png', 2, 2, 4.9, 9.90, 'img.banner/mofantaishi.png'), (3, 'burger', 'burgerrrrrrrrrrrr', 'img/food/burger.jpg', 1, 2, 3, 6.50, 'img/banner/burger.jpg');
COMMIT;

-- ----------------------------
-- Table structure for location
-- ----------------------------
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location`  (
  `canteenId` int(0) NOT NULL AUTO_INCREMENT,
  `canteenName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`canteenId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of location
-- ----------------------------
BEGIN;
INSERT INTO `location` VALUES (1, '万秀园'), (2, '洪博园');
COMMIT;

-- ----------------------------
-- Table structure for order_history
-- ----------------------------
DROP TABLE IF EXISTS `order_history`;
CREATE TABLE `order_history`  (
  `historyId` int(0) NOT NULL AUTO_INCREMENT,
  `orderId` int(0) NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `orderTime` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `totalItem` int(0) NOT NULL,
  PRIMARY KEY (`historyId`) USING BTREE,
  INDEX `orderId`(`orderId`) USING BTREE,
  CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of order_history
-- ----------------------------
BEGIN;
INSERT INTO `order_history` VALUES (1, 47273774, 28.90, '2024-05-20 11:11:52', 2), (2, 53584309, 28.90, '2024-05-20 11:12:12', 3);
COMMIT;

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `orderId` int(0) NOT NULL,
  `userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foodId` int(0) NOT NULL,
  `canteenId` int(0) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `quantity` int(0) NOT NULL,
  PRIMARY KEY (`orderId`, `foodId`) USING BTREE,
  INDEX `userName`(`userName`) USING BTREE,
  INDEX `foodId`(`foodId`) USING BTREE,
  INDEX `canteenId`(`canteenId`) USING BTREE,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of orders
-- ----------------------------
BEGIN;
INSERT INTO `orders` VALUES (47273774, 'student1', 1, 1, 0, 1), (47273774, 'student1', 2, 2, 0, 1), (53584309, 'student1', 1, 1, 0, 1), (53584309, 'student1', 2, 2, 0, 1), (53584309, 'student1', 3, 1, 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for payment
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment`  (
  `paymentId` int(0) NOT NULL AUTO_INCREMENT,
  `paymentType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`paymentId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of payment
-- ----------------------------
BEGIN;
INSERT INTO `payment` VALUES (1, '微信'), (2, '支付宝'), (3, '银行卡');
COMMIT;

-- ----------------------------
-- Table structure for recommendation
-- ----------------------------
DROP TABLE IF EXISTS `recommendation`;
CREATE TABLE `recommendation`  (
  `foodId` int(0) NOT NULL,
  PRIMARY KEY (`foodId`) USING BTREE,
  CONSTRAINT `recommendation_ibfk_1` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of recommendation
-- ----------------------------
BEGIN;
INSERT INTO `recommendation` VALUES (1), (3);
COMMIT;

-- ----------------------------
-- Table structure for shopping_cart
-- ----------------------------
DROP TABLE IF EXISTS `shopping_cart`;
CREATE TABLE `shopping_cart`  (
  `chooseId` int(0) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foodId` int(0) NOT NULL,
  `canteenId` int(0) NOT NULL,
  `chooseTime` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `quantity` int(0) NOT NULL,
  PRIMARY KEY (`chooseId`) USING BTREE,
  INDEX `userName`(`userName`) USING BTREE,
  INDEX `foodId`(`foodId`) USING BTREE,
  INDEX `canteenId`(`canteenId`) USING BTREE,
  CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `shopping_cart_ibfk_3` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 49952542 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of shopping_cart
-- ----------------------------
BEGIN;
INSERT INTO `shopping_cart` VALUES (68473168, 'student1', 2, 2, '2024-05-20 11:12:32', 1), (89703785, 'student1', 1, 1, '2024-05-20 11:12:35', 1);
COMMIT;

-- ----------------------------
-- Table structure for student_detail
-- ----------------------------
DROP TABLE IF EXISTS `student_detail`;
CREATE TABLE `student_detail`  (
  `userName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `profile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`userName`) USING BTREE,
  CONSTRAINT `fk_student_detail_student` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of student_detail
-- ----------------------------
BEGIN;
INSERT INTO `student_detail` VALUES ('student1', 'John Doe', 'img/profile/img1.png'), ('student2', 'Jane Smith', 'img/profile/img2.png'), ('student3', 'Mike Johnson', 'Profile for student 3');
COMMIT;

-- ----------------------------
-- Table structure for student_favorite
-- ----------------------------
DROP TABLE IF EXISTS `student_favorite`;
CREATE TABLE `student_favorite`  (
  `userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foodId` int(0) NOT NULL,
  PRIMARY KEY (`userName`, `foodId`) USING BTREE,
  INDEX `foodId`(`foodId`) USING BTREE,
  CONSTRAINT `student_favorite_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `student_favorite_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of student_favorite
-- ----------------------------
BEGIN;
INSERT INTO `student_favorite` VALUES ('student1', 1);
COMMIT;

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `userName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`userName`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of students
-- ----------------------------
BEGIN;
INSERT INTO `students` VALUES ('student1', 'password1'), ('student2', 'password2'), ('student3', 'password3');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
