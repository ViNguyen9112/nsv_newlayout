-- MySQL dump 10.13  Distrib 5.6.42, for Linux (x86_64)
--
-- Host: localhost    Database: tms_TMS
-- ------------------------------------------------------
-- Server version	5.6.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tblMArea`
--

DROP TABLE IF EXISTS `tblMArea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMArea` (
  `AreaID` varchar(10) NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Region` varchar(4) DEFAULT NULL,
  `Deleted` int(4) DEFAULT '0',
  PRIMARY KEY (`AreaID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMArea`
--

LOCK TABLES `tblMArea` WRITE;
/*!40000 ALTER TABLE `tblMArea` DISABLE KEYS */;
INSERT INTO `tblMArea` VALUES ('M001','Thua Thien - Hue','DN',0),('N001','Ha Noi','HN',0),('S001','Ho Chi Minh','HCM',0),('S002','Nha Trang','HCM',0);
/*!40000 ALTER TABLE `tblMArea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMAreaStaff`
--

DROP TABLE IF EXISTS `tblMAreaStaff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMAreaStaff` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AreaID` varchar(10) NOT NULL,
  `StaffID` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMAreaStaff`
--

LOCK TABLES `tblMAreaStaff` WRITE;
/*!40000 ALTER TABLE `tblMAreaStaff` DISABLE KEYS */;
INSERT INTO `tblMAreaStaff` VALUES (1,'N001',''),(2,'S001',''),(3,'M001',''),(5,'M001','P0004'),(6,'M001','P0003'),(7,'N001','P0005'),(8,'N001','P0014'),(9,'S001','P0001'),(10,'S001','P0002'),(12,'S002','H0005'),(13,'S002','A0002'),(15,'S002','H0004');
/*!40000 ALTER TABLE `tblMAreaStaff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMCategory`
--

DROP TABLE IF EXISTS `tblMCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMCategory` (
  `CategoryCode` varchar(3) CHARACTER SET latin1 NOT NULL,
  `CategoryVN` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CategoryJP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`CategoryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMCategory`
--

LOCK TABLES `tblMCategory` WRITE;
/*!40000 ALTER TABLE `tblMCategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblMCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMCheck`
--

DROP TABLE IF EXISTS `tblMCheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMCheck` (
  `CheckID` int(11) NOT NULL AUTO_INCREMENT,
  `TypeCode` varchar(2) CHARACTER SET latin1 NOT NULL,
  `CategoryCode` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `CheckCode` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `CheckPointVN` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CheckPointJP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ExplanationVN` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ExplanationJP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`CheckID`),
  UNIQUE KEY `unique_check` (`TypeCode`,`CategoryCode`,`CheckCode`) USING BTREE,
  KEY `fk_check_category` (`CategoryCode`),
  CONSTRAINT `fk_check_category` FOREIGN KEY (`CategoryCode`) REFERENCES `tblMCategory` (`CategoryCode`) ON UPDATE CASCADE,
  CONSTRAINT `fk_check_type` FOREIGN KEY (`TypeCode`) REFERENCES `tblMType` (`TypeCode`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMCheck`
--

LOCK TABLES `tblMCheck` WRITE;
/*!40000 ALTER TABLE `tblMCheck` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblMCheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMCustomer`
--

DROP TABLE IF EXISTS `tblMCustomer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMCustomer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `STT` int(11) NOT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `AreaID` varchar(10) NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Latitude` varchar(255) DEFAULT NULL,
  `Longitude` varchar(255) DEFAULT NULL,
  `ImplementDate` date DEFAULT NULL,
  `PositionNo` varchar(100) DEFAULT NULL,
  `TaxCode` varchar(15) DEFAULT NULL,
  `Created_at` datetime DEFAULT NULL,
  `FlagDelete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CustomerID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `Area` (`AreaID`),
  CONSTRAINT `fk_area_id` FOREIGN KEY (`AreaID`) REFERENCES `tblMArea` (`AreaID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMCustomer`
--

LOCK TABLES `tblMCustomer` WRITE;
/*!40000 ALTER TABLE `tblMCustomer` DISABLE KEYS */;
INSERT INTO `tblMCustomer` VALUES (1,1,'A0001','Công ty Netsurf Việt Nam','S001','Lầu 6, Cao ốc Melinh Point Tower, Số 02 Ngô Đức Kế, Phường Đa Kao, Quận 1, TP Hồ Chí Minh','10.7521769','106.6268644','0000-00-00','','','2021-07-01 11:58:00',0),(2,2,'A0002','Thuy\'s Home','S001','Tây Phú, Long Phụng, Cần Giuộc, Long An','10.654180','106.605499','0000-00-00','','','2021-07-01 13:10:00',0),(3,3,'A0003','Bao\'s Home','M001','3/32 Lê Văn Hưu, Thuận Lộc, Huế, Thừa Thiên Huế','16.481350','107.577423','0000-00-00','','','2021-07-01 13:11:00',0),(4,4,'A0004','Vi\'s Home','M001','Thôn Mỹ Xá, Quảng An, Quảng Điền, Thừa Thiên - Huế','16.5499043','107.5476293','0000-00-00','','','2021-07-01 13:12:00',0),(5,5,'A0005','Khoi\'s Home','N001','Ngõ 40 Ngách 9 Nhà số 8 Phan Đình Giót, Thanh Xuân, Hà Nội','20.991510','105.839043','0000-00-00','','','2021-07-01 13:13:00',0),(6,6,'A0006','Dung\'s Home','N001',' Tổ 12 Thạch Bàn Long Biên, Hà Nội , Việt Nam','21.017069800273223','105.91844294022151','0000-00-00','','','2021-07-01 13:16:00',0),(7,7,'A0007','Công ty NSV Việt Nam','S002','Số 09 Võ Thị Sáu, Vĩnh Nguyên, Nha Trang, Khánh Hòa.','12.210194','109.201342','0000-00-00','','','2021-07-02 07:17:00',0);
/*!40000 ALTER TABLE `tblMCustomer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMLanguage`
--

DROP TABLE IF EXISTS `tblMLanguage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMLanguage` (
  `KeyString` varchar(50) CHARACTER SET latin1 NOT NULL,
  `VNLanguage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `JPLanguage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ENLanguage` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`KeyString`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMLanguage`
--

LOCK TABLES `tblMLanguage` WRITE;
/*!40000 ALTER TABLE `tblMLanguage` DISABLE KEYS */;
INSERT INTO `tblMLanguage` VALUES ('account_incorrect','Tên người dùng hoặc mật khẩu chưa chính xác','ユーザIDまたはパスワードが不正です','Your ID or Password is incorrect'),('area','Khu vực','エリア','Area'),('capture','Chụp','キャプチャー','Capture'),('check_in','Check In','チェックイン','Check In'),('check_in_successfully','Check In thành công!','チェックインに成功しました!','Check In successfully!'),('check_out','Check Out','チェックアウト','Check Out'),('check_out_successfully','Check Out thành công!','チェックアウトは成功しました。!','Check Out successfully!'),('clear','Xoá','削除','Clear'),('close','Đóng','閉める','Close'),('customer','Khách hàng','お客様','Customer'),('enable_location','Vui lòng mở Vị trí trên thiết bị của bạn !','あなたの携帯電話の位置情報通知の設定を [オン]にしてください !','Please enable location services on your device !'),('login','Đăng nhập','ログインしてください','LOGIN'),('look_camera','Xin hãy nhìn vào camera','カメラを見てください。','Please look at the camera'),('not_authorized_location','Không được quyền truy cập vào vị trí','位置情報を有効にしてください。','You are not authorized to access that location'),('not_checked_in','Bạn chưa check in.','あなたはまだチェックインしません','You have not checked in yet.'),('not_checked_out','Bạn chưa check out ở ','あなたはまだチェックアウトしていません。','You have not checked out at '),('not_reported','Bạn chưa viết báo cáo.','あなたはまだ報告書を入力しません。','You have not reported yet.'),('password','Mật khẩu','パスワード','Password'),('please_choose_area','Vui lòng chọn khu vực','エリアを選択してください。','Please choose Area'),('please_choose_customer','Vui lòng chọn khách hàng','クライアント名を選択してください。','Please choose Customer'),('please_login','Đăng nhập!','ログインしてください','Please login!'),('report','Báo cáo','レポート','Report'),('required_report','Nhập nội dung của bảng báo cáo','報告内容を入力してください。','Please input the content of the report'),('submit','Gửi','提出','Submit'),('submit_failed','Có lỗi xảy ra. Vui lòng tải lại trang và thử lại!','エラーが発生しました。 ページをリロードして再試行してください!','Have error has occurred. Please reload the page and try again!'),('submit_successfully','Gửi thành công','提出は成功しました。','Submitted successfully'),('time_checked_in','Giờ Check in','チェックイン時間','Check-in Time'),('time_checked_out','Giờ Check out','チェックアウト時間','Check-out Time'),('type','Kiểu','タイプ','Type'),('type_1','Tuần tra','パトロール','Patrol'),('type_2','Cuộc họp (khách hàng)','ミーティング(お客様)','Meeting (Customer)'),('type_3','Công việc văn phòng','ディスクワーク','Desk Work'),('type_4','Cuộc họp trong công ty','社内会議','In-house Meeting'),('type_5','Hoạt động tuyển dụng','採用活動','Recruitment Activities'),('type_6','Bảo vệ/ Bố trí nhân sự','警備員/ポスト配置','Security Guard/ Post Disposition'),('type_7','Khác','その他 ','Others'),('working_calendar','Lịch làm viêc','カレンダー','Working Calendar'),('you_checked_in','Bạn đã check in lúc TIME','あなたは TIME にチェックインしました。','You checked in at TIME'),('you_checked_out','Bạn đã check out lúc TIME','あなたは TIME にチェックアウトしました。','You checked out at TIME');
/*!40000 ALTER TABLE `tblMLanguage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMPage`
--

DROP TABLE IF EXISTS `tblMPage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMPage` (
  `PageID` varchar(10) NOT NULL,
  `PageName` varchar(255) NOT NULL,
  PRIMARY KEY (`PageID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMPage`
--

LOCK TABLES `tblMPage` WRITE;
/*!40000 ALTER TABLE `tblMPage` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblMPage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMRegion`
--

DROP TABLE IF EXISTS `tblMRegion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMRegion` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `RegionID` varchar(100) NOT NULL,
  `Region` varchar(100) DEFAULT NULL,
  `Deleted` int(2) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMRegion`
--

LOCK TABLES `tblMRegion` WRITE;
/*!40000 ALTER TABLE `tblMRegion` DISABLE KEYS */;
INSERT INTO `tblMRegion` VALUES (1,'HN','Hà Nội',0);
/*!40000 ALTER TABLE `tblMRegion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMStaff`
--

DROP TABLE IF EXISTS `tblMStaff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMStaff` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` varchar(10) NOT NULL,
  `Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(10) DEFAULT NULL,
  `Position` varchar(100) NOT NULL,
  `Title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Region` varchar(4) DEFAULT NULL,
  `Created_at` datetime DEFAULT NULL,
  `FlagDelete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `StaffID_2` (`StaffID`),
  KEY `StaffID` (`StaffID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMStaff`
--

LOCK TABLES `tblMStaff` WRITE;
/*!40000 ALTER TABLE `tblMStaff` DISABLE KEYS */;
INSERT INTO `tblMStaff` VALUES (1,'A0001','Admin','123456','Japanese Manager',NULL,NULL,NULL,0),(2,'P0001','Phạm Vũ Hoàng Điệp','Diep12','Area Leader','','HCM','2021-07-01 11:45:00',0),(3,'P0002','Phan Thi Thu Thuy','Thuy12','Area Leader','','HCM','2021-07-01 11:46:00',0),(4,'P0003','Nguyễn Thái Bảo','Bao123','Area Leader','','DN','2021-07-01 11:54:00',0),(5,'P0004','Nguyễn Thị Tường Vi','Vi1234','Area Leader','','DN','2021-07-01 11:54:00',0),(6,'P0005','Nguyễn Văn Khôi','Khoi12','Area Leader','','HN','2021-07-01 11:55:00',0),(7,'P0014','Hoàng Công Dũng','Dung12','Area Leader','','HN','2021-07-01 11:55:00',0),(8,'H0004','Lương Vũ Hạ Uyên','Uyen12','Leader','','HCM','2021-07-02 07:13:00',0),(9,'H0005','Uyen Test','Uyen12','Leader','','HCM','2021-07-02 09:24:00',1),(10,'A0002','KUBOTA','123456Hk','Area Leader','Boss','HCM','2021-07-09 09:32:00',0);
/*!40000 ALTER TABLE `tblMStaff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblMType`
--

DROP TABLE IF EXISTS `tblMType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblMType` (
  `TypeCode` varchar(2) CHARACTER SET latin1 NOT NULL,
  `TypeEN` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TypeVN` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TypeJP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`TypeCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblMType`
--

LOCK TABLES `tblMType` WRITE;
/*!40000 ALTER TABLE `tblMType` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblMType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTCheckResult`
--

DROP TABLE IF EXISTS `tblTCheckResult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTCheckResult` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TimeCardID` int(11) NOT NULL,
  `TypeCode` varchar(2) CHARACTER SET latin1 NOT NULL,
  `CheckID` int(11) NOT NULL,
  `Result` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `fk_checkid_check` (`CheckID`),
  KEY `fk_checkid_type` (`TypeCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTCheckResult`
--

LOCK TABLES `tblTCheckResult` WRITE;
/*!40000 ALTER TABLE `tblTCheckResult` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblTCheckResult` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTDistance`
--

DROP TABLE IF EXISTS `tblTDistance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTDistance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `StaffID` varchar(10) NOT NULL,
  `Distance` float NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_staffid_distance_staff` (`StaffID`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTDistance`
--

LOCK TABLES `tblTDistance` WRITE;
/*!40000 ALTER TABLE `tblTDistance` DISABLE KEYS */;
INSERT INTO `tblTDistance` VALUES (1,'2021-07-01','P0001',0,'2021-07-01 15:52:54'),(2,'2021-07-01','P0002',0,'2021-07-01 16:18:26'),(3,'2021-07-02','P0005',0,'2021-07-02 07:22:11'),(4,'2021-07-02','H0004',0,'2021-07-02 07:23:01'),(5,'2021-07-02','P0004',0,'2021-07-02 07:23:49'),(6,'2021-07-02','P0003',0,'2021-07-02 07:35:26'),(7,'2021-07-02','P0002',0,'2021-07-02 07:56:57'),(8,'2021-07-02','P0001',0,'2021-07-02 08:25:39'),(9,'2021-07-02','H0005',0,'2021-07-02 09:25:22'),(10,'2021-07-02','P0014',0,'2021-07-02 18:24:50'),(11,'2021-07-03','P0005',0,'2021-07-03 14:22:55'),(12,'2021-07-05','P0004',0,'2021-07-05 06:42:37'),(13,'2021-07-05','P0003',0,'2021-07-05 07:13:31'),(14,'2021-07-05','P0005',0,'2021-07-05 07:27:24'),(15,'2021-07-05','H0004',0,'2021-07-05 07:29:39'),(16,'2021-07-05','P0001',0,'2021-07-05 08:08:51'),(17,'2021-07-05','P0014',0,'2021-07-05 08:26:56'),(18,'2021-07-05','P0002',0,'2021-07-05 08:42:56'),(19,'2021-07-06','P0004',0,'2021-07-06 06:33:42'),(20,'2021-07-06','P0005',0,'2021-07-06 07:10:46'),(21,'2021-07-06','H0004',0,'2021-07-06 07:25:18'),(22,'2021-07-06','P0003',0,'2021-07-06 07:25:27'),(23,'2021-07-06','P0014',0,'2021-07-06 07:58:31'),(24,'2021-07-06','P0002',0,'2021-07-06 08:12:58'),(25,'2021-07-07','P0004',0,'2021-07-07 07:03:35'),(26,'2021-07-07','P0003',0,'2021-07-07 07:08:20'),(27,'2021-07-07','P0005',0,'2021-07-07 07:12:22'),(28,'2021-07-07','H0004',0,'2021-07-07 07:25:50'),(29,'2021-07-07','P0002',0,'2021-07-07 07:45:51'),(30,'2021-07-07','P0014',0,'2021-07-07 08:13:20'),(31,'2021-07-08','P0004',0,'2021-07-08 07:03:08'),(32,'2021-07-08','P0003',0,'2021-07-08 07:31:39'),(33,'2021-07-08','P0002',0,'2021-07-08 07:46:20'),(34,'2021-07-08','H0004',0,'2021-07-08 07:48:50'),(35,'2021-07-08','P0005',0,'2021-07-08 07:52:24'),(36,'2021-07-08','P0014',0,'2021-07-08 17:49:39'),(37,'2021-07-09','P0004',0,'2021-07-09 06:57:49'),(38,'2021-07-09','P0003',0,'2021-07-09 07:07:39'),(39,'2021-07-09','H0004',0,'2021-07-09 07:09:00'),(40,'2021-07-09','P0002',0,'2021-07-09 07:58:45'),(41,'2021-07-09','P0014',0,'2021-07-09 08:00:26'),(42,'2021-07-09','P0001',0,'2021-07-09 08:38:54'),(43,'2021-07-09','A0002',0,'2021-07-09 09:43:49'),(44,'2021-07-12','P0003',0,'2021-07-12 06:47:14'),(45,'2021-07-12','P0004',0,'2021-07-12 06:47:21'),(46,'2021-07-12','H0004',0,'2021-07-12 06:59:40'),(47,'2021-07-12','P0005',0,'2021-07-12 07:17:18'),(48,'2021-07-12','P0002',0,'2021-07-12 07:51:09'),(49,'2021-07-12','P0001',0,'2021-07-12 07:58:59'),(50,'2021-07-12','P0014',0,'2021-07-12 08:00:04'),(51,'2021-07-12','A0002',0,'2021-07-12 17:32:15'),(52,'2021-07-13','P0003',0,'2021-07-13 06:48:50'),(53,'2021-07-13','P0004',0,'2021-07-13 06:57:49'),(54,'2021-07-13','H0004',0,'2021-07-13 07:09:26'),(55,'2021-07-13','P0014',0,'2021-07-13 07:16:31'),(56,'2021-07-13','P0005',0,'2021-07-13 07:19:31'),(57,'2021-07-13','P0002',0,'2021-07-13 07:46:03'),(58,'2021-07-13','P0001',0,'2021-07-13 08:45:27'),(59,'2021-07-14','P0004',0,'2021-07-14 06:09:44'),(60,'2021-07-14','P0003',0,'2021-07-14 06:36:58'),(61,'2021-07-14','P0014',0,'2021-07-14 07:18:38'),(62,'2021-07-14','P0005',0,'2021-07-14 07:43:37'),(63,'2021-07-14','P0002',0,'2021-07-14 07:46:22'),(64,'2021-07-14','P0001',0,'2021-07-14 07:51:10'),(65,'2021-07-15','P0003',0,'2021-07-15 06:43:37'),(66,'2021-07-15','P0004',0,'2021-07-15 06:50:40'),(67,'2021-07-15','P0005',0,'2021-07-15 07:14:04'),(68,'2021-07-15','P0014',0,'2021-07-15 07:20:06'),(69,'2021-07-15','P0002',0,'2021-07-15 07:45:55'),(70,'2021-07-15','P0001',0,'2021-07-15 07:50:26'),(71,'2021-07-16','P0004',0,'2021-07-16 07:00:14'),(72,'2021-07-16','P0003',0,'2021-07-16 07:12:35'),(73,'2021-07-16','P0014',0,'2021-07-16 07:18:50'),(74,'2021-07-16','P0002',0,'2021-07-16 07:46:22'),(75,'2021-07-16','P0001',0,'2021-07-16 07:49:22'),(76,'2021-07-16','P0005',0,'2021-07-16 07:49:59'),(77,'2021-07-19','P0003',0,'2021-07-19 07:26:39'),(78,'2021-07-19','P0002',0,'2021-07-19 07:46:39'),(79,'2021-07-19','P0004',0,'2021-07-19 07:53:16'),(80,'2021-07-19','P0001',0,'2021-07-19 07:57:34'),(81,'2021-07-19','P0014',0.01,'2021-07-19 22:41:59'),(82,'2021-07-19','P0005',0,'2021-07-19 08:02:19'),(83,'2021-07-20','P0003',0,'2021-07-20 06:48:09'),(84,'2021-07-20','P0005',0,'2021-07-20 07:39:37'),(85,'2021-07-20','P0004',0,'2021-07-20 07:45:53'),(86,'2021-07-20','P0002',0,'2021-07-20 07:46:28'),(87,'2021-07-20','P0001',0,'2021-07-20 07:57:37'),(88,'2021-07-20','P0014',0,'2021-07-20 09:03:40'),(89,'2021-07-21','P0004',0,'2021-07-21 06:39:34'),(90,'2021-07-21','P0005',0,'2021-07-21 07:30:41'),(91,'2021-07-21','P0002',0,'2021-07-21 07:46:33'),(92,'2021-07-21','P0014',0,'2021-07-21 07:50:13'),(93,'2021-07-21','P0001',0,'2021-07-21 07:58:58'),(94,'2021-07-22','P0003',0,'2021-07-22 07:10:14'),(95,'2021-07-22','P0004',0,'2021-07-22 07:30:06'),(96,'2021-07-22','P0002',0,'2021-07-22 07:45:57'),(97,'2021-07-22','P0014',0,'2021-07-22 07:46:52'),(98,'2021-07-22','P0005',0,'2021-07-22 07:47:35'),(99,'2021-07-22','P0001',0,'2021-07-22 23:40:11'),(100,'2021-07-23','P0004',0,'2021-07-23 06:52:01'),(101,'2021-07-23','P0003',0,'2021-07-23 06:59:56'),(102,'2021-07-23','P0001',0,'2021-07-23 07:38:37'),(103,'2021-07-23','P0002',0,'2021-07-23 07:45:19'),(104,'2021-07-23','P0005',0,'2021-07-23 07:55:03'),(105,'2021-07-23','P0014',0.01,'2021-07-23 18:17:47'),(106,'2021-07-24','P0001',0,'2021-07-24 00:02:53'),(107,'2021-07-26','P0004',0,'2021-07-26 06:47:43'),(108,'2021-07-26','P0003',0,'2021-07-26 07:21:28'),(109,'2021-07-26','P0014',0,'2021-07-26 07:33:44'),(110,'2021-07-26','P0002',0,'2021-07-26 07:45:44'),(111,'2021-07-26','P0001',0,'2021-07-26 07:51:07'),(112,'2021-07-26','P0005',0,'2021-07-26 07:58:56');
/*!40000 ALTER TABLE `tblTDistance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTFaceImage`
--

DROP TABLE IF EXISTS `tblTFaceImage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTFaceImage` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `FolderID` int(11) NOT NULL,
  `TimeCardID` int(11) DEFAULT NULL,
  `Created_at` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FolderID` (`FolderID`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTFaceImage`
--

LOCK TABLES `tblTFaceImage` WRITE;
/*!40000 ALTER TABLE `tblTFaceImage` DISABLE KEYS */;
INSERT INTO `tblTFaceImage` VALUES (1,'P0001202107011552IN.jpg','FaceImage/P0001/',1,1,'2021-07-01 15:52:54'),(2,'P0002202107011618IN.jpg','FaceImage/P0002/',2,2,'2021-07-01 16:18:26'),(3,'P0005202107020722IN.jpg','FaceImage/P0005/',3,3,'2021-07-02 07:22:11'),(4,'H0004202107020723IN.jpg','FaceImage/H0004/',4,4,'2021-07-02 07:23:01'),(5,'P0004202107020723IN.jpg','FaceImage/P0004/',5,5,'2021-07-02 07:23:49'),(6,'P0003202107020735IN.jpg','FaceImage/P0003/',6,6,'2021-07-02 07:35:26'),(7,'P0002202107020756IN.jpg','FaceImage/P0002/',2,7,'2021-07-02 07:56:57'),(8,'P0001202107020825IN.jpg','FaceImage/P0001/',1,8,'2021-07-02 08:25:38'),(9,'H0005202107020925IN.jpg','FaceImage/H0005/',7,9,'2021-07-02 09:25:21'),(10,'P0005202107021700OUT.jpg','FaceImage/P0005/',3,3,'2021-07-02 17:00:53'),(11,'P0003202107021703OUT.jpg','FaceImage/P0003/',6,6,'2021-07-02 17:03:38'),(12,'P0004202107021738OUT.jpg','FaceImage/P0004/',5,5,'2021-07-02 17:38:22'),(13,'P0014202107021824IN.jpg','FaceImage/P0014/',8,10,'2021-07-02 18:24:49'),(14,'P0014202107021827OUT.jpg','FaceImage/P0014/',8,10,'2021-07-02 18:27:09'),(15,'P0002202107022214OUT.jpg','FaceImage/P0002/',2,7,'2021-07-02 22:14:58'),(16,'P0005202107030843IN.jpg','FaceImage/P0005/',3,11,'2021-07-03 08:43:41'),(17,'P0005202107030844IN.jpg','FaceImage/P0005/',3,12,'2021-07-03 08:44:05'),(18,'P0005202107030846OUT.jpg','FaceImage/P0005/',3,12,'2021-07-03 08:46:34'),(19,'P0005202107030846OUT.jpg','FaceImage/P0005/',3,12,'2021-07-03 08:46:40'),(20,'P0005202107031337IN.jpg','FaceImage/P0005/',3,13,'2021-07-03 13:37:53'),(21,'P0005202107031339IN.jpg','FaceImage/P0005/',3,14,'2021-07-03 13:39:31'),(22,'P0005202107031408OUT.jpg','FaceImage/P0005/',3,14,'2021-07-03 14:08:38'),(23,'P0005202107031414IN.jpg','FaceImage/P0005/',3,15,'2021-07-03 14:14:50'),(24,'P0005202107031414OUT.jpg','FaceImage/P0005/',3,15,'2021-07-03 14:14:57'),(25,'P0005202107031422IN.jpg','FaceImage/P0005/',3,16,'2021-07-03 14:22:53'),(26,'P0005202107031425OUT.jpg','FaceImage/P0005/',3,16,'2021-07-03 14:25:21'),(27,'P0004202107050642IN.jpg','FaceImage/P0004/',5,17,'2021-07-05 06:42:37'),(28,'P0003202107050713IN.jpg','FaceImage/P0003/',6,18,'2021-07-05 07:13:31'),(29,'P0005202107050727IN.jpg','FaceImage/P0005/',3,19,'2021-07-05 07:27:22'),(30,'H0004202107050729IN.jpg','FaceImage/H0004/',4,20,'2021-07-05 07:29:39'),(31,'P0001202107050808IN.jpg','FaceImage/P0001/',1,21,'2021-07-05 08:08:51'),(32,'P0014202107050826IN.jpg','FaceImage/P0014/',8,22,'2021-07-05 08:26:56'),(33,'P0002202107050842IN.jpg','FaceImage/P0002/',2,23,'2021-07-05 08:42:56'),(34,'P0005202107051700OUT.jpg','FaceImage/P0005/',3,19,'2021-07-05 17:00:46'),(35,'P0004202107051729OUT.jpg','FaceImage/P0004/',5,17,'2021-07-05 17:29:20'),(36,'P0014202107051810OUT.jpg','FaceImage/P0014/',8,22,'2021-07-05 18:10:20'),(37,'P0002202107051812OUT.jpg','FaceImage/P0002/',2,23,'2021-07-05 18:12:59'),(38,'P0003202107051932OUT.jpg','FaceImage/P0003/',6,18,'2021-07-05 19:32:19'),(39,'P0004202107060633IN.jpg','FaceImage/P0004/',5,24,'2021-07-06 06:33:41'),(40,'P0005202107060710IN.jpg','FaceImage/P0005/',3,25,'2021-07-06 07:10:45'),(41,'H0004202107060725IN.jpg','FaceImage/H0004/',4,26,'2021-07-06 07:25:18'),(42,'P0003202107060725IN.jpg','FaceImage/P0003/',6,27,'2021-07-06 07:25:27'),(43,'P0014202107060758IN.jpg','FaceImage/P0014/',8,28,'2021-07-06 07:58:31'),(44,'P0002202107060812IN.jpg','FaceImage/P0002/',2,29,'2021-07-06 08:12:57'),(45,'P0005202107061700OUT.jpg','FaceImage/P0005/',3,25,'2021-07-06 17:00:34'),(46,'P0004202107061714OUT.jpg','FaceImage/P0004/',5,24,'2021-07-06 17:14:18'),(47,'P0014202107061804OUT.jpg','FaceImage/P0014/',8,28,'2021-07-06 18:04:17'),(48,'P0003202107061808OUT.jpg','FaceImage/P0003/',6,27,'2021-07-06 18:08:45'),(49,'P0002202107062010OUT.jpg','FaceImage/P0002/',2,29,'2021-07-06 20:10:30'),(50,'P0004202107070703IN.jpg','FaceImage/P0004/',5,30,'2021-07-07 07:03:35'),(51,'P0003202107070708IN.jpg','FaceImage/P0003/',6,31,'2021-07-07 07:08:20'),(52,'P0005202107070712IN.jpg','FaceImage/P0005/',3,32,'2021-07-07 07:12:08'),(53,'H0004202107070725IN.jpg','FaceImage/H0004/',4,33,'2021-07-07 07:25:50'),(54,'P0002202107070745IN.jpg','FaceImage/P0002/',2,34,'2021-07-07 07:45:51'),(55,'P0014202107070813IN.jpg','FaceImage/P0014/',8,35,'2021-07-07 08:13:20'),(56,'P0014202107071726OUT.jpg','FaceImage/P0014/',8,35,'2021-07-07 17:26:35'),(57,'P0002202107071803OUT.jpg','FaceImage/P0002/',2,34,'2021-07-07 18:03:19'),(58,'P0005202107071807OUT.jpg','FaceImage/P0005/',3,32,'2021-07-07 18:07:41'),(59,'P0003202107071810OUT.jpg','FaceImage/P0003/',6,31,'2021-07-07 18:10:16'),(60,'P0004202107071953OUT.jpg','FaceImage/P0004/',5,30,'2021-07-07 19:53:07'),(61,'P0004202107080703IN.jpg','FaceImage/P0004/',5,36,'2021-07-08 07:03:08'),(62,'P0003202107080731IN.jpg','FaceImage/P0003/',6,37,'2021-07-08 07:31:39'),(63,'P0002202107080746IN.jpg','FaceImage/P0002/',2,38,'2021-07-08 07:46:20'),(64,'H0004202107080748IN.jpg','FaceImage/H0004/',4,39,'2021-07-08 07:48:50'),(65,'P0005202107080752IN.jpg','FaceImage/P0005/',3,40,'2021-07-08 07:52:23'),(66,'P0005202107081704OUT.jpg','FaceImage/P0005/',3,40,'2021-07-08 17:04:31'),(67,'P0003202107081722OUT.jpg','FaceImage/P0003/',6,37,'2021-07-08 17:22:13'),(68,'P0004202107081734OUT.jpg','FaceImage/P0004/',5,36,'2021-07-08 17:34:23'),(69,'P0014202107081749IN.jpg','FaceImage/P0014/',8,41,'2021-07-08 17:49:39'),(70,'P0014202107081749OUT.jpg','FaceImage/P0014/',8,41,'2021-07-08 17:49:51'),(71,'P0002202107081759OUT.jpg','FaceImage/P0002/',2,38,'2021-07-08 17:59:14'),(72,'P0004202107090657IN.jpg','FaceImage/P0004/',5,42,'2021-07-09 06:57:49'),(73,'P0003202107090707IN.jpg','FaceImage/P0003/',6,43,'2021-07-09 07:07:38'),(74,'H0004202107090709IN.jpg','FaceImage/H0004/',4,44,'2021-07-09 07:09:00'),(75,'P0002202107090758IN.jpg','FaceImage/P0002/',2,45,'2021-07-09 07:58:44'),(76,'P0014202107090800IN.jpg','FaceImage/P0014/',8,46,'2021-07-09 08:00:25'),(77,'P0001202107090838IN.jpg','FaceImage/P0001/',1,47,'2021-07-09 08:38:54'),(78,'A0002202107090943IN.jpg','FaceImage/A0002/',9,48,'2021-07-09 09:43:49'),(79,'P0002202107091801OUT.jpg','FaceImage/P0002/',2,45,'2021-07-09 18:01:46'),(80,'P0014202107091807OUT.jpg','FaceImage/P0014/',8,46,'2021-07-09 18:07:46'),(81,'P0003202107091811OUT.jpg','FaceImage/P0003/',6,43,'2021-07-09 18:11:54'),(82,'P0004202107091814OUT.jpg','FaceImage/P0004/',5,42,'2021-07-09 18:14:30'),(83,'P0003202107120647IN.jpg','FaceImage/P0003/',6,49,'2021-07-12 06:47:13'),(84,'P0004202107120647IN.jpg','FaceImage/P0004/',5,50,'2021-07-12 06:47:21'),(85,'H0004202107120659IN.jpg','FaceImage/H0004/',4,51,'2021-07-12 06:59:40'),(86,'P0005202107120717IN.jpg','FaceImage/P0005/',3,52,'2021-07-12 07:17:16'),(87,'P0002202107120751IN.jpg','FaceImage/P0002/',2,53,'2021-07-12 07:51:09'),(88,'P0001202107120758IN.jpg','FaceImage/P0001/',1,54,'2021-07-12 07:58:59'),(89,'P0014202107120800IN.jpg','FaceImage/P0014/',8,55,'2021-07-12 08:00:04'),(90,'P0005202107121700OUT.jpg','FaceImage/P0005/',3,52,'2021-07-12 17:00:40'),(91,'A0002202107121732IN.jpg','FaceImage/A0002/',9,56,'2021-07-12 17:32:15'),(92,'P0003202107121755OUT.jpg','FaceImage/P0003/',6,49,'2021-07-12 17:55:43'),(93,'P0014202107121823OUT.jpg','FaceImage/P0014/',8,55,'2021-07-12 18:23:15'),(94,'P0004202107121825OUT.jpg','FaceImage/P0004/',5,50,'2021-07-12 18:25:22'),(95,'P0002202107122105OUT.jpg','FaceImage/P0002/',2,53,'2021-07-12 21:05:15'),(96,'P0001202107122209OUT.jpg','FaceImage/P0001/',1,54,'2021-07-12 22:09:15'),(97,'P0003202107130648IN.jpg','FaceImage/P0003/',6,57,'2021-07-13 06:48:49'),(98,'P0004202107130657IN.jpg','FaceImage/P0004/',5,58,'2021-07-13 06:57:48'),(99,'H0004202107130709IN.jpg','FaceImage/H0004/',4,59,'2021-07-13 07:09:25'),(100,'P0014202107130716IN.jpg','FaceImage/P0014/',8,60,'2021-07-13 07:16:30'),(101,'P0005202107130719IN.jpg','FaceImage/P0005/',3,61,'2021-07-13 07:19:29'),(102,'P0002202107130746IN.jpg','FaceImage/P0002/',2,62,'2021-07-13 07:46:03'),(103,'P0001202107130845IN.jpg','FaceImage/P0001/',1,63,'2021-07-13 08:45:26'),(104,'P0005202107131716OUT.jpg','FaceImage/P0005/',3,61,'2021-07-13 17:16:30'),(105,'P0003202107131732OUT.jpg','FaceImage/P0003/',6,57,'2021-07-13 17:32:39'),(106,'P0014202107131834OUT.jpg','FaceImage/P0014/',8,60,'2021-07-13 18:34:50'),(107,'P0004202107131911OUT.jpg','FaceImage/P0004/',5,58,'2021-07-13 19:11:03'),(108,'P0002202107132021OUT.jpg','FaceImage/P0002/',2,62,'2021-07-13 20:21:16'),(109,'P0001202107132245OUT.jpg','FaceImage/P0001/',1,63,'2021-07-13 22:45:18'),(110,'P0004202107140609IN.jpg','FaceImage/P0004/',5,64,'2021-07-14 06:09:43'),(111,'P0003202107140636IN.jpg','FaceImage/P0003/',6,65,'2021-07-14 06:36:58'),(112,'P0014202107140718IN.jpg','FaceImage/P0014/',8,66,'2021-07-14 07:18:38'),(113,'P0005202107140743IN.jpg','FaceImage/P0005/',3,67,'2021-07-14 07:43:35'),(114,'P0002202107140746IN.jpg','FaceImage/P0002/',2,68,'2021-07-14 07:46:22'),(115,'P0001202107140751IN.jpg','FaceImage/P0001/',1,69,'2021-07-14 07:51:10'),(116,'P0005202107141700OUT.jpg','FaceImage/P0005/',3,67,'2021-07-14 17:00:29'),(117,'P0003202107141723OUT.jpg','FaceImage/P0003/',6,65,'2021-07-14 17:23:30'),(118,'P0002202107141752OUT.jpg','FaceImage/P0002/',2,68,'2021-07-14 17:52:26'),(119,'P0014202107141949OUT.jpg','FaceImage/P0014/',8,66,'2021-07-14 19:49:07'),(120,'P0004202107142120OUT.jpg','FaceImage/P0004/',5,64,'2021-07-14 21:20:18'),(121,'P0001202107142305OUT.jpg','FaceImage/P0001/',1,69,'2021-07-14 23:05:52'),(122,'P0003202107150643IN.jpg','FaceImage/P0003/',6,70,'2021-07-15 06:43:36'),(123,'P0004202107150650IN.jpg','FaceImage/P0004/',5,71,'2021-07-15 06:50:40'),(124,'P0005202107150714IN.jpg','FaceImage/P0005/',3,72,'2021-07-15 07:14:03'),(125,'P0014202107150720IN.jpg','FaceImage/P0014/',8,73,'2021-07-15 07:20:06'),(126,'P0002202107150745IN.jpg','FaceImage/P0002/',2,74,'2021-07-15 07:45:55'),(127,'P0001202107150750IN.jpg','FaceImage/P0001/',1,75,'2021-07-15 07:50:26'),(128,'P0005202107151703OUT.jpg','FaceImage/P0005/',3,72,'2021-07-15 17:03:47'),(129,'P0003202107151750OUT.jpg','FaceImage/P0003/',6,70,'2021-07-15 17:50:23'),(130,'P0004202107151826OUT.jpg','FaceImage/P0004/',5,71,'2021-07-15 18:26:10'),(131,'P0002202107151928OUT.jpg','FaceImage/P0002/',2,74,'2021-07-15 19:28:15'),(132,'P0014202107152031OUT.jpg','FaceImage/P0014/',8,73,'2021-07-15 20:31:10'),(133,'P0001202107152254OUT.jpg','FaceImage/P0001/',1,75,'2021-07-15 22:54:16'),(134,'P0004202107160700IN.jpg','FaceImage/P0004/',5,76,'2021-07-16 07:00:14'),(135,'P0003202107160712IN.jpg','FaceImage/P0003/',6,77,'2021-07-16 07:12:35'),(136,'P0014202107160718IN.jpg','FaceImage/P0014/',8,78,'2021-07-16 07:18:50'),(137,'P0002202107160746IN.jpg','FaceImage/P0002/',2,79,'2021-07-16 07:46:22'),(138,'P0001202107160749IN.jpg','FaceImage/P0001/',1,80,'2021-07-16 07:49:22'),(139,'P0005202107160749IN.jpg','FaceImage/P0005/',3,81,'2021-07-16 07:49:57'),(140,'P0005202107161702OUT.jpg','FaceImage/P0005/',3,81,'2021-07-16 17:02:30'),(141,'P0003202107161712OUT.jpg','FaceImage/P0003/',6,77,'2021-07-16 17:12:55'),(142,'P0002202107161740OUT.jpg','FaceImage/P0002/',2,79,'2021-07-16 17:40:01'),(143,'P0004202107161829OUT.jpg','FaceImage/P0004/',5,76,'2021-07-16 18:29:25'),(144,'P0014202107161919OUT.jpg','FaceImage/P0014/',8,78,'2021-07-16 19:19:36'),(145,'P0001202107162212OUT.jpg','FaceImage/P0001/',1,80,'2021-07-16 22:12:30'),(146,'P0003202107190726IN.jpg','FaceImage/P0003/',6,82,'2021-07-19 07:26:39'),(147,'P0002202107190746IN.jpg','FaceImage/P0002/',2,83,'2021-07-19 07:46:39'),(148,'P0004202107190753IN.jpg','FaceImage/P0004/',5,84,'2021-07-19 07:53:16'),(149,'P0001202107190757IN.jpg','FaceImage/P0001/',1,85,'2021-07-19 07:57:34'),(150,'P0014202107190759IN.jpg','FaceImage/P0014/',8,86,'2021-07-19 07:59:16'),(151,'P0005202107190802IN.jpg','FaceImage/P0005/',3,87,'2021-07-19 08:02:17'),(152,'P0005202107191702OUT.jpg','FaceImage/P0005/',3,87,'2021-07-19 17:02:16'),(153,'P0003202107191708OUT.jpg','FaceImage/P0003/',6,82,'2021-07-19 17:08:15'),(154,'P0002202107191835OUT.jpg','FaceImage/P0002/',2,83,'2021-07-19 18:35:53'),(155,'P0004202107191931OUT.jpg','FaceImage/P0004/',5,84,'2021-07-19 19:31:56'),(156,'P0001202107192226OUT.jpg','FaceImage/P0001/',1,85,'2021-07-19 22:26:51'),(157,'P0014202107192241OUT.jpg','FaceImage/P0014/',8,86,'2021-07-19 22:41:37'),(158,'P0014202107192241IN.jpg','FaceImage/P0014/',8,88,'2021-07-19 22:41:58'),(159,'P0014202107192242OUT.jpg','FaceImage/P0014/',8,88,'2021-07-19 22:42:14'),(160,'P0003202107200648IN.jpg','FaceImage/P0003/',6,89,'2021-07-20 06:48:08'),(161,'P0005202107200739IN.jpg','FaceImage/P0005/',3,90,'2021-07-20 07:39:35'),(162,'P0004202107200745IN.jpg','FaceImage/P0004/',5,91,'2021-07-20 07:45:53'),(163,'P0002202107200746IN.jpg','FaceImage/P0002/',2,92,'2021-07-20 07:46:28'),(164,'P0001202107200757IN.jpg','FaceImage/P0001/',1,93,'2021-07-20 07:57:37'),(165,'P0014202107200903IN.jpg','FaceImage/P0014/',8,94,'2021-07-20 09:03:40'),(166,'P0003202107201704OUT.jpg','FaceImage/P0003/',6,89,'2021-07-20 17:04:39'),(167,'P0005202107201706OUT.jpg','FaceImage/P0005/',3,90,'2021-07-20 17:06:05'),(168,'P0002202107201801OUT.jpg','FaceImage/P0002/',2,92,'2021-07-20 18:01:15'),(169,'P0004202107201811OUT.jpg','FaceImage/P0004/',5,91,'2021-07-20 18:11:46'),(170,'P0014202107201915OUT.jpg','FaceImage/P0014/',8,94,'2021-07-20 19:15:15'),(171,'P0004202107210639IN.jpg','FaceImage/P0004/',5,95,'2021-07-21 06:39:33'),(172,'P0003202107210717IN.jpg','FaceImage/P0003/',6,96,'2021-07-21 07:17:30'),(173,'P0005202107210730IN.jpg','FaceImage/P0005/',3,97,'2021-07-21 07:30:39'),(174,'P0002202107210746IN.jpg','FaceImage/P0002/',2,98,'2021-07-21 07:46:32'),(175,'P0014202107210750IN.jpg','FaceImage/P0014/',8,99,'2021-07-21 07:50:13'),(176,'P0001202107210758IN.jpg','FaceImage/P0001/',1,100,'2021-07-21 07:58:58'),(177,'P0014202107211959OUT.jpg','FaceImage/P0014/',8,99,'2021-07-21 19:59:15'),(178,'P0001202107212346OUT.jpg','FaceImage/P0001/',1,100,'2021-07-21 23:46:26'),(179,'P0003202107220710IN.jpg','FaceImage/P0003/',6,101,'2021-07-22 07:10:13'),(180,'P0004202107220730IN.jpg','FaceImage/P0004/',5,102,'2021-07-22 07:30:06'),(181,'P0002202107220745IN.jpg','FaceImage/P0002/',2,103,'2021-07-22 07:45:57'),(182,'P0014202107220746IN.jpg','FaceImage/P0014/',8,104,'2021-07-22 07:46:52'),(183,'P0005202107220747IN.jpg','FaceImage/P0005/',3,105,'2021-07-22 07:47:32'),(184,'P0003202107221716OUT.jpg','FaceImage/P0003/',6,101,'2021-07-22 17:16:29'),(185,'P0005202107221756OUT.jpg','FaceImage/P0005/',3,105,'2021-07-22 17:56:54'),(186,'P0004202107221928OUT.jpg','FaceImage/P0004/',5,102,'2021-07-22 19:28:16'),(187,'P0002202107221934OUT.jpg','FaceImage/P0002/',2,103,'2021-07-22 19:34:41'),(188,'P0014202107221946OUT.jpg','FaceImage/P0014/',8,104,'2021-07-22 19:46:53'),(189,'P0001202107222340IN.jpg','FaceImage/P0001/',1,106,'2021-07-22 23:40:10'),(190,'P0001202107222340OUT.jpg','FaceImage/P0001/',1,106,'2021-07-22 23:40:25'),(191,'P0004202107230652IN.jpg','FaceImage/P0004/',5,107,'2021-07-23 06:52:00'),(192,'P0003202107230659IN.jpg','FaceImage/P0003/',6,108,'2021-07-23 06:59:56'),(193,'P0001202107230738IN.jpg','FaceImage/P0001/',1,109,'2021-07-23 07:38:36'),(194,'P0002202107230745IN.jpg','FaceImage/P0002/',2,110,'2021-07-23 07:45:19'),(195,'P0005202107230755IN.jpg','FaceImage/P0005/',3,111,'2021-07-23 07:55:02'),(196,'P0014202107230853IN.jpg','FaceImage/P0014/',8,112,'2021-07-23 08:53:00'),(197,'P0002202107231702OUT.jpg','FaceImage/P0002/',2,110,'2021-07-23 17:02:21'),(198,'P0003202107231711OUT.jpg','FaceImage/P0003/',6,108,'2021-07-23 17:12:00'),(199,'P0005202107231723OUT.jpg','FaceImage/P0005/',3,111,'2021-07-23 17:23:15'),(200,'P0014202107231817OUT.jpg','FaceImage/P0014/',8,112,'2021-07-23 18:17:33'),(201,'P0014202107231817IN.jpg','FaceImage/P0014/',8,113,'2021-07-23 18:17:47'),(202,'P0014202107231818OUT.jpg','FaceImage/P0014/',8,113,'2021-07-23 18:18:08'),(203,'P0004202107232116OUT.jpg','FaceImage/P0004/',5,107,'2021-07-23 21:16:46'),(204,'P0001202107240002IN.jpg','FaceImage/P0001/',1,114,'2021-07-24 00:02:53'),(205,'P0001202107240003OUT.jpg','FaceImage/P0001/',1,114,'2021-07-24 00:03:04'),(206,'P0004202107260647IN.jpg','FaceImage/P0004/',5,115,'2021-07-26 06:47:43'),(207,'P0003202107260721IN.jpg','FaceImage/P0003/',6,116,'2021-07-26 07:21:28'),(208,'P0014202107260733IN.jpg','FaceImage/P0014/',8,117,'2021-07-26 07:33:44'),(209,'P0002202107260745IN.jpg','FaceImage/P0002/',2,118,'2021-07-26 07:45:44'),(210,'P0001202107260751IN.jpg','FaceImage/P0001/',1,119,'2021-07-26 07:51:07'),(211,'P0005202107260758IN.jpg','FaceImage/P0005/',3,120,'2021-07-26 07:58:53');
/*!40000 ALTER TABLE `tblTFaceImage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTFolder`
--

DROP TABLE IF EXISTS `tblTFolder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTFolder` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Type` varchar(20) CHARACTER SET latin1 NOT NULL,
  `ChildrenFolder` text COLLATE utf8mb4_unicode_ci,
  `ParentFolder` int(11) DEFAULT NULL,
  `QuickAccess` text COLLATE utf8mb4_unicode_ci,
  `Created_at` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTFolder`
--

LOCK TABLES `tblTFolder` WRITE;
/*!40000 ALTER TABLE `tblTFolder` DISABLE KEYS */;
INSERT INTO `tblTFolder` VALUES (1,'P0001','Staff',NULL,NULL,NULL,'2021-07-01 15:52:54'),(2,'P0002','Staff',NULL,NULL,NULL,'2021-07-01 16:18:26'),(3,'P0005','Staff',NULL,NULL,NULL,'2021-07-02 07:22:11'),(4,'H0004','Staff',NULL,NULL,NULL,'2021-07-02 07:23:01'),(5,'P0004','Staff',NULL,NULL,NULL,'2021-07-02 07:23:49'),(6,'P0003','Staff',NULL,NULL,NULL,'2021-07-02 07:35:26'),(7,'H0005','Staff',NULL,NULL,NULL,'2021-07-02 09:25:21'),(8,'P0014','Staff',NULL,NULL,NULL,'2021-07-02 18:24:49'),(9,'A0002','Staff',NULL,NULL,NULL,'2021-07-09 09:43:49');
/*!40000 ALTER TABLE `tblTFolder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTImageReport`
--

DROP TABLE IF EXISTS `tblTImageReport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTImageReport` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ReportID` int(11) NOT NULL,
  `ImageName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTImageReport`
--

LOCK TABLES `tblTImageReport` WRITE;
/*!40000 ALTER TABLE `tblTImageReport` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblTImageReport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTLogin`
--

DROP TABLE IF EXISTS `tblTLogin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTLogin` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DateTime` datetime NOT NULL,
  `StaffID` varchar(10) NOT NULL,
  `PageID` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_staffid_login_staff` (`StaffID`),
  KEY `fk_pageid_login_page` (`PageID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTLogin`
--

LOCK TABLES `tblTLogin` WRITE;
/*!40000 ALTER TABLE `tblTLogin` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblTLogin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTReport`
--

DROP TABLE IF EXISTS `tblTReport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTReport` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` varchar(10) NOT NULL,
  `DateTime` datetime NOT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `Report` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ReportJP` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ReportVN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ReportEN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TypeCode` varchar(2) NOT NULL,
  `TimeCardID` int(11) NOT NULL,
  `CheckID` text,
  `Created_at` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTReport`
--

LOCK TABLES `tblTReport` WRITE;
/*!40000 ALTER TABLE `tblTReport` DISABLE KEYS */;
INSERT INTO `tblTReport` VALUES (1,'P0005','2021-07-02 17:00:20','A0005','','','','','-1',3,NULL,'2021-07-02 17:00:20'),(2,'P0003','2021-07-02 17:03:07','A0003','','','','','-1',6,NULL,'2021-07-02 17:03:07'),(3,'P0004','2021-07-02 17:22:48','A0004','','','','','-1',5,NULL,'2021-07-02 17:22:48'),(4,'P0014','2021-07-02 18:26:56','A0006','','','','','-1',10,NULL,'2021-07-02 18:26:56'),(5,'P0002','2021-07-02 22:14:25','A0001','','','','','-1',7,NULL,'2021-07-02 22:14:25'),(6,'P0005','2021-07-03 08:45:29','A0005','','','','','-1',12,NULL,'2021-07-03 08:45:29'),(7,'P0005','2021-07-03 14:01:25','A0005','B','b','B.','B','-1',14,NULL,'2021-07-03 14:01:25'),(8,'P0005','2021-07-03 14:14:56','A0005','','','','','-1',15,NULL,'2021-07-03 14:14:56'),(9,'P0005','2021-07-03 14:25:20','A0005','','','','','-1',16,NULL,'2021-07-03 14:25:20'),(10,'P0005','2021-07-05 17:00:42','A0005','','','','','-1',19,NULL,'2021-07-05 17:00:42'),(11,'P0003','2021-07-05 17:07:03','A0003','','','','','-1',18,NULL,'2021-07-05 17:07:03'),(12,'P0004','2021-07-05 17:29:12','A0004','','','','','-1',17,NULL,'2021-07-05 17:29:12'),(13,'P0014','2021-07-05 18:10:12','A0006','','','','','-1',22,NULL,'2021-07-05 18:10:12'),(14,'P0002','2021-07-05 18:12:18','A0001','','','','','-1',23,NULL,'2021-07-05 18:12:18'),(15,'P0005','2021-07-06 17:00:30','A0005','','','','','-1',25,NULL,'2021-07-06 17:00:30'),(16,'P0004','2021-07-06 17:14:07','A0004','','','','','-1',24,NULL,'2021-07-06 17:14:07'),(17,'P0014','2021-07-06 18:04:03','A0006','','','','','-1',28,NULL,'2021-07-06 18:04:03'),(18,'P0003','2021-07-06 18:08:34','A0003','','','','','-1',27,NULL,'2021-07-06 18:08:34'),(19,'P0002','2021-07-06 20:10:19','A0001','','','','','-1',29,NULL,'2021-07-06 20:10:19'),(20,'P0014','2021-07-07 17:26:24','A0006','','','','','-1',35,NULL,'2021-07-07 17:26:24'),(21,'P0002','2021-07-07 18:03:01','A0002','','','','','-1',34,NULL,'2021-07-07 18:03:01'),(22,'P0005','2021-07-07 18:07:32','A0005','','','','','-1',32,NULL,'2021-07-07 18:07:32'),(23,'P0003','2021-07-07 18:10:02','A0003','','','','','-1',31,NULL,'2021-07-07 18:10:02'),(24,'P0004','2021-07-07 19:53:00','A0004','','','','','-1',30,NULL,'2021-07-07 19:53:00'),(25,'P0005','2021-07-08 17:04:28','A0005','','','','','-1',40,NULL,'2021-07-08 17:04:28'),(26,'P0003','2021-07-08 17:21:55','A0003','','','','','-1',37,NULL,'2021-07-08 17:21:55'),(27,'P0004','2021-07-08 17:34:00','A0004','','','','','-1',36,NULL,'2021-07-08 17:34:00'),(28,'P0014','2021-07-08 17:49:44','A0006','','','','','-1',41,NULL,'2021-07-08 17:49:44'),(29,'P0002','2021-07-08 17:58:52','A0002','','','','','-1',38,NULL,'2021-07-08 17:58:52'),(30,'P0002','2021-07-09 18:01:31','A0001','','','','','-1',45,NULL,'2021-07-09 18:01:31'),(31,'P0014','2021-07-09 18:07:37','A0006','','','','','-1',46,NULL,'2021-07-09 18:07:37'),(32,'P0003','2021-07-09 18:11:44','A0003','','','','','-1',43,NULL,'2021-07-09 18:11:44'),(33,'P0004','2021-07-09 18:14:22','A0004','','','','','-1',42,NULL,'2021-07-09 18:14:22'),(34,'P0005','2021-07-12 17:00:36','A0005','','','','','-1',52,NULL,'2021-07-12 17:00:36'),(35,'P0003','2021-07-12 17:55:33','A0003','','','','','-1',49,NULL,'2021-07-12 17:55:33'),(36,'P0014','2021-07-12 18:23:10','A0006','','','','','-1',55,NULL,'2021-07-12 18:23:10'),(37,'P0004','2021-07-12 18:25:03','A0004','','','','','-1',50,NULL,'2021-07-12 18:25:03'),(38,'P0002','2021-07-12 21:05:01','A0001','','','','','-1',53,NULL,'2021-07-12 21:05:01'),(39,'P0001','2021-07-12 22:09:04','A0001','','','','','-1',54,NULL,'2021-07-12 22:09:04'),(40,'P0005','2021-07-13 17:16:24','A0005','','','','','-1',61,NULL,'2021-07-13 17:16:24'),(41,'P0003','2021-07-13 17:32:31','A0003','','','','','-1',57,NULL,'2021-07-13 17:32:31'),(42,'P0014','2021-07-13 18:34:41','A0006','','','','','-1',60,NULL,'2021-07-13 18:34:41'),(43,'P0004','2021-07-13 19:10:55','A0004','','','','','-1',58,NULL,'2021-07-13 19:10:55'),(44,'P0002','2021-07-13 20:21:03','A0001','','','','','-1',62,NULL,'2021-07-13 20:21:03'),(45,'P0001','2021-07-13 22:45:06','A0001','','','','','-1',63,NULL,'2021-07-13 22:45:06'),(46,'P0005','2021-07-14 17:00:23','A0005','Hello Sir','こんにちは','Xin chào Sir.','Hello Sir','-1',67,NULL,'2021-07-14 17:00:23'),(47,'P0003','2021-07-14 17:23:14','A0003','','','','','-1',65,NULL,'2021-07-14 17:23:14'),(48,'P0002','2021-07-14 17:52:01','A0001','','','','','-1',68,NULL,'2021-07-14 17:52:01'),(49,'P0014','2021-07-14 19:48:50','A0006','','','','','-1',66,NULL,'2021-07-14 19:48:50'),(50,'P0004','2021-07-14 21:20:03','A0004','','','','','-1',64,NULL,'2021-07-14 21:20:03'),(51,'P0001','2021-07-14 23:05:42','A0001','','','','','-1',69,NULL,'2021-07-14 23:05:42'),(52,'P0005','2021-07-15 17:03:43','A0005','','','','','-1',72,NULL,'2021-07-15 17:03:43'),(53,'P0003','2021-07-15 17:50:16','A0003','','','','','-1',70,NULL,'2021-07-15 17:50:16'),(54,'P0004','2021-07-15 18:25:54','A0004','','','','','-1',71,NULL,'2021-07-15 18:25:54'),(55,'P0002','2021-07-15 19:27:39','A0001','','','','','-1',74,NULL,'2021-07-15 19:27:39'),(56,'P0014','2021-07-15 20:31:02','A0006','','','','','-1',73,NULL,'2021-07-15 20:31:02'),(57,'P0001','2021-07-15 22:54:08','A0001','','','','','-1',75,NULL,'2021-07-15 22:54:08'),(58,'P0005','2021-07-16 17:02:23','A0005','','','','','-1',81,NULL,'2021-07-16 17:02:23'),(59,'P0003','2021-07-16 17:12:46','A0003','','','','','-1',77,NULL,'2021-07-16 17:12:46'),(60,'P0002','2021-07-16 17:39:46','A0001','','','','','-1',79,NULL,'2021-07-16 17:39:46'),(61,'P0004','2021-07-16 18:29:15','A0004','','','','','-1',76,NULL,'2021-07-16 18:29:15'),(62,'P0014','2021-07-16 19:19:26','A0006','','','','','-1',78,NULL,'2021-07-16 19:19:26'),(63,'P0001','2021-07-16 22:12:16','A0001','','','','','-1',80,NULL,'2021-07-16 22:12:16'),(64,'P0005','2021-07-19 17:02:11','A0005','','','','','-1',87,NULL,'2021-07-19 17:02:11'),(65,'P0003','2021-07-19 17:08:07','A0003','','','','','-1',82,NULL,'2021-07-19 17:08:07'),(66,'P0002','2021-07-19 18:35:23','A0001','','','','','-1',83,NULL,'2021-07-19 18:35:23'),(67,'P0004','2021-07-19 19:31:19','A0004','','','','','-1',84,NULL,'2021-07-19 19:31:19'),(68,'P0001','2021-07-19 22:26:40','A0001','','','','','-1',85,NULL,'2021-07-19 22:26:40'),(69,'P0014','2021-07-19 22:41:15','A0005','','','','','-1',86,NULL,'2021-07-19 22:41:15'),(70,'P0014','2021-07-19 22:42:05','A0006','','','','','-1',88,NULL,'2021-07-19 22:42:05'),(71,'P0003','2021-07-20 17:04:30','A0003','','','','','-1',89,NULL,'2021-07-20 17:04:30'),(72,'P0005','2021-07-20 17:06:01','A0005','','','','','-1',90,NULL,'2021-07-20 17:06:01'),(73,'P0002','2021-07-20 18:01:00','A0001','','','','','-1',92,NULL,'2021-07-20 18:01:00'),(74,'P0004','2021-07-20 18:11:30','A0004','','','','','-1',91,NULL,'2021-07-20 18:11:30'),(75,'P0014','2021-07-20 19:15:05','A0006','','','','','-1',94,NULL,'2021-07-20 19:15:05'),(76,'P0002','2021-07-21 17:15:32','A0001','','','','','-1',98,NULL,'2021-07-21 17:15:32'),(77,'P0005','2021-07-21 17:17:01','A0005','','','','','-1',97,NULL,'2021-07-21 17:17:01'),(78,'P0003','2021-07-21 17:21:47','A0003','','','','','-1',96,NULL,'2021-07-21 17:21:47'),(79,'P0004','2021-07-21 17:27:42','A0004','','','','','-1',95,NULL,'2021-07-21 17:27:42'),(80,'P0014','2021-07-21 19:58:45','A0006','','','','','-1',99,NULL,'2021-07-21 19:58:45'),(81,'P0001','2021-07-21 23:46:20','A0001','','','','','-1',100,NULL,'2021-07-21 23:46:20'),(82,'P0003','2021-07-22 17:16:07','A0003','','','','','-1',101,NULL,'2021-07-22 17:16:07'),(83,'P0005','2021-07-22 17:56:50','A0005','','','','','-1',105,NULL,'2021-07-22 17:56:50'),(84,'P0004','2021-07-22 19:27:58','A0004','','','','','-1',102,NULL,'2021-07-22 19:27:58'),(85,'P0002','2021-07-22 19:34:01','A0001','','','','','-1',103,NULL,'2021-07-22 19:34:01'),(86,'P0014','2021-07-22 19:46:43','A0006','','','','','-1',104,NULL,'2021-07-22 19:46:43'),(87,'P0001','2021-07-22 23:40:17','A0001','','','','','-1',106,NULL,'2021-07-22 23:40:17'),(88,'P0002','2021-07-23 17:02:13','A0001','','','','','-1',110,NULL,'2021-07-23 17:02:13'),(89,'P0003','2021-07-23 17:11:52','A0003','','','','','-1',108,NULL,'2021-07-23 17:11:52'),(90,'P0005','2021-07-23 17:23:12','A0005','','','','','-1',111,NULL,'2021-07-23 17:23:12'),(91,'P0014','2021-07-23 18:17:23','A0005','','','','','-1',112,NULL,'2021-07-23 18:17:23'),(92,'P0014','2021-07-23 18:17:58','A0006','','','','','-1',113,NULL,'2021-07-23 18:17:58'),(93,'P0004','2021-07-23 21:16:41','A0004','','','','','-1',107,NULL,'2021-07-23 21:16:41'),(94,'P0001','2021-07-24 00:02:59','A0001','','','','','-1',114,NULL,'2021-07-24 00:02:59');
/*!40000 ALTER TABLE `tblTReport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTSchedule`
--

DROP TABLE IF EXISTS `tblTSchedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTSchedule` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `TimeBegin` time NOT NULL,
  `TimeEnd` time DEFAULT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `Type` enum('#e50000','#d5702c','#e5ad00','#2a4c88','#949494','#001d56','#652b90') NOT NULL,
  `Created_at` datetime NOT NULL,
  `Updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_stafid` (`StaffID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTSchedule`
--

LOCK TABLES `tblTSchedule` WRITE;
/*!40000 ALTER TABLE `tblTSchedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblTSchedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTTimeCard`
--

DROP TABLE IF EXISTS `tblTTimeCard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTTimeCard` (
  `TimeCardID` int(11) NOT NULL AUTO_INCREMENT,
  `StaffID` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `TimeIn` time NOT NULL,
  `TimeOut` time DEFAULT NULL,
  `TotalTime` float DEFAULT NULL,
  `CustomerID` varchar(10) NOT NULL,
  `CheckinLocation` varchar(255) NOT NULL,
  `CheckoutLocation` varchar(255) DEFAULT NULL,
  `Created_at` datetime NOT NULL,
  `Updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`TimeCardID`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTTimeCard`
--

LOCK TABLES `tblTTimeCard` WRITE;
/*!40000 ALTER TABLE `tblTTimeCard` DISABLE KEYS */;
INSERT INTO `tblTTimeCard` VALUES (1,'P0001','2021-07-01','15:52:54',NULL,NULL,'A0001','12.210261044424248,109.20120456561249',NULL,'2021-07-01 15:52:54',NULL),(2,'P0002','2021-07-01','16:18:26',NULL,NULL,'A0002','10.7539328,106.7162058','10.7539328,106.7162058','2021-07-01 16:18:26',NULL),(3,'P0005','2021-07-02','07:22:11','17:00:53',9.63,'A0005','21.0017611,105.8206347','21.0017678,105.8206287','2021-07-02 07:22:11','2021-07-02 17:00:53'),(4,'H0004','2021-07-02','07:23:01',NULL,NULL,'A0007','12.209913867584998,109.20137130623046',NULL,'2021-07-02 07:23:01',NULL),(5,'P0004','2021-07-02','07:23:49','17:38:22',10.25,'A0004','16.5499047,107.5476246','16.5498857,107.5475919','2021-07-02 07:23:49','2021-07-02 17:38:22'),(6,'P0003','2021-07-02','07:35:26','17:03:38',9.47,'A0003','16.45860210893262,107.59107378269898','16.458176530804053,107.59168547680981','2021-07-02 07:35:26','2021-07-02 17:03:38'),(7,'P0002','2021-07-02','07:56:57','22:14:58',14.3,'A0001','10.750950,106.698960','10.7539358,106.7162478','2021-07-02 07:56:57','2021-07-02 22:14:58'),(8,'P0001','2021-07-02','08:25:38',NULL,NULL,'A0001','10.7521629,106.626835',NULL,'2021-07-02 08:25:38',NULL),(9,'H0005','2021-07-02','09:25:21',NULL,NULL,'A0007','12.209937052771814,109.20142073468553',NULL,'2021-07-02 09:25:21',NULL),(10,'P0014','2021-07-02','18:24:49','18:27:09',0.05,'A0006','21.018832305050665,105.8095524460698','21.018824133001928,105.8095543411135','2021-07-02 18:24:49','2021-07-02 18:27:09'),(11,'P0005','2021-07-03','08:43:41',NULL,NULL,'A0005','20.9990269,105.8172996',NULL,'2021-07-03 08:43:41',NULL),(12,'P0005','2021-07-03','08:44:05','08:46:40',0.03,'A0005','20.9990269,105.8172996','20.9990269,105.8172996','2021-07-03 08:44:05','2021-07-03 08:46:40'),(13,'P0005','2021-07-03','13:37:53',NULL,NULL,'','20.9990269,105.8172996',NULL,'2021-07-03 13:37:53',NULL),(14,'P0005','2021-07-03','13:39:31','14:08:38',0.48,'A0005','20.9990269,105.8172996','20.9990269,105.8172996','2021-07-03 13:39:31','2021-07-03 14:08:38'),(15,'P0005','2021-07-03','14:14:49','14:14:57',0,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-03 14:14:49','2021-07-03 14:14:57'),(16,'P0005','2021-07-03','14:22:53','14:25:21',0.05,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-03 14:22:53','2021-07-03 14:25:21'),(17,'P0004','2021-07-05','06:42:37','17:29:20',10.78,'A0004','16.5498987,107.5476331','16.5498848,107.5475747','2021-07-05 06:42:37','2021-07-05 17:29:20'),(18,'P0003','2021-07-05','07:13:31','19:32:18',12.32,'A0003','16.480929242606017,107.57839471409505','16.480337544819054,107.57824190900752','2021-07-05 07:13:31','2021-07-05 19:32:18'),(19,'P0005','2021-07-05','07:27:21','17:00:44',9.55,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-05 07:27:21','2021-07-05 17:00:44'),(20,'H0004','2021-07-05','07:29:39',NULL,NULL,'A0007','12.209939623989916,109.20124143998139',NULL,'2021-07-05 07:29:39',NULL),(21,'P0001','2021-07-05','08:08:51',NULL,NULL,'A0001','10.752198,106.6268579',NULL,'2021-07-05 08:08:51',NULL),(22,'P0014','2021-07-05','08:26:56','18:10:20',9.73,'A0006','21.01888984510464,105.8096022548049','21.018831472393234,105.80958292744543','2021-07-05 08:26:56','2021-07-05 18:10:20'),(23,'P0002','2021-07-05','07:42:56','18:12:59',10.3,'A0001','10.654180,106.605500','10.7539412,106.7162598','2021-07-05 08:42:56','2021-07-05 18:12:59'),(24,'P0004','2021-07-06','06:33:41','17:14:18',10.68,'A0004','16.549901,107.547615','16.5499025,107.5476342','2021-07-06 06:33:41','2021-07-06 17:14:18'),(25,'P0005','2021-07-06','07:10:42','17:00:32',9.83,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-06 07:10:42','2021-07-06 17:00:32'),(26,'H0004','2021-07-06','07:25:18',NULL,NULL,'A0007','12.210042793884439,109.20140422355782',NULL,'2021-07-06 07:25:18',NULL),(27,'P0003','2021-07-06','07:25:26','18:08:45',10.72,'A0003','16.481031720614798,107.5784010783211','16.458798534969436,107.59189948928898','2021-07-06 07:25:26','2021-07-06 18:08:45'),(28,'P0014','2021-07-06','07:58:31','18:04:17',10.1,'A0006','21.01907657289072,105.80972981872888','21.01838534953944,105.80966503714295','2021-07-06 07:58:31','2021-07-06 18:04:17'),(29,'P0002','2021-07-06','07:45:57','20:10:30',12.42,'A0001','10.7539401,106.7162611','10.7539309,106.7162073','2021-07-06 08:12:57','2021-07-06 20:10:30'),(30,'P0004','2021-07-07','07:03:35','19:53:07',12.83,'A0004','16.5498989,107.5476275','16.5476459,107.5455225','2021-07-07 07:03:35','2021-07-07 19:53:07'),(31,'P0003','2021-07-07','07:08:19','18:10:16',11.03,'A0003','16.48076400530783,107.57850051032919','16.458886000172804,107.59187021566993','2021-07-07 07:08:19','2021-07-07 18:10:16'),(32,'P0005','2021-07-07','07:12:07','18:07:39',10.92,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-07 07:12:07','2021-07-07 18:07:39'),(33,'H0004','2021-07-07','07:25:50',NULL,NULL,'A0007','12.209902425820012,109.20128609057856',NULL,'2021-07-07 07:25:50',NULL),(34,'P0002','2021-07-07','07:45:51','18:03:19',10.3,'A0002','10.7539372,106.716262','10.7539365,106.7162618','2021-07-07 07:45:51','2021-07-07 18:03:19'),(35,'P0014','2021-07-07','08:13:20','17:26:35',9.22,'A0006','21.018902516631602,105.80960237156711','21.018803318340275,105.80955417193948','2021-07-07 08:13:20','2021-07-07 17:26:35'),(36,'P0004','2021-07-08','07:03:08','17:34:23',10.52,'A0004','16.5498991,107.5476267','16.5498976,107.5476283','2021-07-08 07:03:08','2021-07-08 17:34:23'),(37,'P0003','2021-07-08','07:31:39','17:22:12',9.85,'A0003','16.48072923990905,107.57837105937108','16.45812720966137,107.59158525330385','2021-07-08 07:31:39','2021-07-08 17:22:12'),(38,'P0002','2021-07-08','07:46:20','17:59:13',10.22,'A0002','10.7539374,106.7162565','10.7539176,106.7162253','2021-07-08 07:46:20','2021-07-08 17:59:13'),(39,'H0004','2021-07-08','07:48:50',NULL,NULL,'A0007','12.210093530240513,109.20120083764688',NULL,'2021-07-08 07:48:50',NULL),(40,'P0005','2021-07-08','07:52:22','17:04:30',9.2,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-08 07:52:22','2021-07-08 17:04:30'),(41,'P0014','2021-07-08','17:49:38','17:49:51',0,'A0006','21.019096805414225,105.80974355731655','21.018843458349348,105.80942121596469','2021-07-08 17:49:38','2021-07-08 17:49:51'),(42,'P0004','2021-07-09','06:57:49','18:14:30',11.28,'A0004','16.5476459,107.5455225','16.5476459,107.5455225','2021-07-09 06:57:49','2021-07-09 18:14:30'),(43,'P0003','2021-07-09','07:07:38','18:11:54',11.07,'A0003','16.480863756220096,107.57845315750392','16.459035147884524,107.5918010583167','2021-07-09 07:07:38','2021-07-09 18:11:54'),(44,'H0004','2021-07-09','07:09:00',NULL,NULL,'A0007','12.210019581711576,109.20143778649306',NULL,'2021-07-09 07:09:00',NULL),(45,'P0002','2021-07-09','07:58:44','18:01:46',10.05,'A0001','10.7539254,106.7162699','10.7539181,106.7163005','2021-07-09 07:58:44','2021-07-09 18:01:46'),(46,'P0014','2021-07-09','08:00:25','18:07:46',10.12,'A0006','21.01896677688654,105.8096633452725','21.019013041805266,105.8097777671625','2021-07-09 08:00:25','2021-07-09 18:07:46'),(47,'P0001','2021-07-09','08:38:54',NULL,NULL,'A0001','10.7521769,106.6268644',NULL,'2021-07-09 08:38:54',NULL),(48,'A0002','2021-07-09','09:43:48',NULL,NULL,'A0007','12.2103161,109.2013043',NULL,'2021-07-09 09:43:48',NULL),(49,'P0003','2021-07-12','06:47:13','17:55:43',11.13,'A0003','16.45929298762079,107.58062050593395','16.458086379884975,107.59165990509973','2021-07-12 06:47:13','2021-07-12 17:55:43'),(50,'P0004','2021-07-12','06:47:21','18:25:21',11.63,'A0004','16.5499043,107.5476293','16.549905,107.547635','2021-07-12 06:47:21','2021-07-12 18:25:21'),(51,'H0004','2021-07-12','06:59:40',NULL,NULL,'A0007','12.20970687479278,109.2014293061227',NULL,'2021-07-12 06:59:40',NULL),(52,'P0005','2021-07-12','07:17:14','17:00:38',9.72,'A0005','20.991431,105.838824','20.991431,105.838824','2021-07-12 07:17:14','2021-07-12 17:00:38'),(53,'P0002','2021-07-12','07:51:09','21:05:14',13.23,'A0001','10.7539219,106.716266','10.7539236,106.7162713','2021-07-12 07:51:09','2021-07-12 21:05:14'),(54,'P0001','2021-07-12','07:58:58','22:09:15',14.18,'A0001','10.7521691,106.6268714','10.7521674,106.6268226','2021-07-12 07:58:58','2021-07-12 22:09:15'),(55,'P0014','2021-07-12','08:00:03','18:23:15',10.38,'A0006','21.018843859311914,105.80956298324713','21.01892754096849,105.80963153635238','2021-07-12 08:00:03','2021-07-12 18:23:15'),(56,'A0002','2021-07-12','17:32:15',NULL,NULL,'A0007','12.2386031,109.19488',NULL,'2021-07-12 17:32:15',NULL),(57,'P0003','2021-07-13','06:48:49','17:32:38',10.73,'A0003','16.45894801000345,107.58081053166379','16.458361206429696,107.59147349903714','2021-07-13 06:48:49','2021-07-13 17:32:38'),(58,'P0004','2021-07-13','06:57:48','19:11:03',12.23,'A0004','16.5499042,107.5476255','16.5499022,107.547636','2021-07-13 06:57:48','2021-07-13 19:11:03'),(59,'H0004','2021-07-13','07:09:25',NULL,NULL,'A0007','12.209915963925626,109.20139473501365',NULL,'2021-07-13 07:09:25',NULL),(60,'P0014','2021-07-13','07:16:30','18:34:49',11.3,'A0006','21.017069800273223,105.91844294022151','21.017125730422922,105.91845440063746','2021-07-13 07:16:30','2021-07-13 18:34:49'),(61,'P0005','2021-07-13','07:19:29','17:16:27',9.95,'A0005','20.991431,105.838824','20.991431,105.838824','2021-07-13 07:19:29','2021-07-13 17:16:27'),(62,'P0002','2021-07-13','07:46:02','20:21:16',12.58,'A0001','10.7539261,106.71627','10.7539282,106.7162665','2021-07-13 07:46:02','2021-07-13 20:21:16'),(63,'P0001','2021-07-13','08:45:26','22:45:18',14,'A0001','10.7522113,106.626852','10.7521651,106.6268733','2021-07-13 08:45:26','2021-07-13 22:45:18'),(64,'P0004','2021-07-14','06:09:43','21:20:18',15.18,'A0004','16.5499013,107.5476317','16.5499049,107.5476261','2021-07-14 06:09:43','2021-07-14 21:20:18'),(65,'P0003','2021-07-14','06:36:57','17:23:30',10.78,'A0003','16.481240719099972,107.57857147395816','16.45811382159431,107.5917667980249','2021-07-14 06:36:57','2021-07-14 17:23:30'),(66,'P0014','2021-07-14','07:18:38','19:49:06',12.52,'A0006','21.01707987192901,105.91844791965012','21.017095343952526,105.91846799766871','2021-07-14 07:18:38','2021-07-14 19:49:06'),(67,'P0005','2021-07-14','07:43:34','17:00:27',9.28,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-14 07:43:34','2021-07-14 17:00:27'),(68,'P0002','2021-07-14','07:46:22','17:52:26',10.1,'A0001','10.7539175,106.7162423','10.753928,106.7162604','2021-07-14 07:46:22','2021-07-14 17:52:26'),(69,'P0001','2021-07-14','07:51:10','23:05:51',15.23,'A0001','10.7521611,106.6268635','10.7522033,106.6268452','2021-07-14 07:51:10','2021-07-14 23:05:51'),(70,'P0003','2021-07-15','06:43:36','17:50:23',11.12,'A0003','16.458806476210157,107.58078811391852','16.45812687310889,107.5915568164127','2021-07-15 06:43:36','2021-07-15 17:50:23'),(71,'P0004','2021-07-15','06:50:39','18:26:10',11.6,'A0004','16.5480011,107.5455225','16.5498965,107.5476299','2021-07-15 06:50:39','2021-07-15 18:26:10'),(72,'P0005','2021-07-15','07:14:01','17:03:44',9.82,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-15 07:14:01','2021-07-15 17:03:44'),(73,'P0014','2021-07-15','07:20:05','20:31:10',13.18,'A0006','21.01710709353392,105.91852057224291','21.017068463242307,105.91850471600058','2021-07-15 07:20:05','2021-07-15 20:31:10'),(74,'P0002','2021-07-15','07:45:55','19:28:15',11.72,'A0001','10.7539157,106.7162278','10.7539275,106.7162664','2021-07-15 07:45:55','2021-07-15 19:28:15'),(75,'P0001','2021-07-15','07:50:25','22:54:15',15.07,'A0001','10.7522032,106.6268466','10.7521969,106.6268357','2021-07-15 07:50:25','2021-07-15 22:54:15'),(76,'P0004','2021-07-16','07:00:14','18:29:25',11.48,'A0004','16.5499045,107.5476358','16.54992,107.5476463','2021-07-16 07:00:14','2021-07-16 18:29:25'),(77,'P0003','2021-07-16','07:12:35','17:12:54',10,'A0003','16.480826919245587,107.57842773902212','16.458083945821517,107.5916929153891','2021-07-16 07:12:35','2021-07-16 17:12:54'),(78,'P0014','2021-07-16','07:18:49','19:19:36',12.02,'A0006','21.017084573755103,105.91848663500716','21.017135850329748,105.91850898991','2021-07-16 07:18:49','2021-07-16 19:19:36'),(79,'P0002','2021-07-16','07:46:22','17:40:00',9.9,'A0001','10.7539221,106.716272','10.7539242,106.7162709','2021-07-16 07:46:22','2021-07-16 17:40:00'),(80,'P0001','2021-07-16','07:49:22','22:12:30',14.38,'A0001','10.7521736,106.6268846','10.7522077,106.62685839999999','2021-07-16 07:49:22','2021-07-16 22:12:30'),(81,'P0005','2021-07-16','07:49:57','17:02:29',9.22,'A0005','20.991432,105.838823','20.991432,105.838823','2021-07-16 07:49:57','2021-07-16 17:02:29'),(82,'P0003','2021-07-19','07:26:39','17:08:14',9.7,'A0003','16.481011446505192,107.57848073834421','16.458231610878034,107.59170082552508','2021-07-19 07:26:39','2021-07-19 17:08:14'),(83,'P0002','2021-07-19','07:46:39','18:35:53',10.82,'A0001','10.7539342,106.71628','10.7539192,106.7162624','2021-07-19 07:46:39','2021-07-19 18:35:53'),(84,'P0004','2021-07-19','07:53:16','19:31:56',11.63,'A0004','16.5498985,107.5476252','16.5498994,107.5476282','2021-07-19 07:53:16','2021-07-19 19:31:56'),(85,'P0001','2021-07-19','07:57:34','22:26:51',14.48,'A0001','10.752051,106.6268199','10.752143,106.6268613','2021-07-19 07:57:34','2021-07-19 22:26:51'),(86,'P0014','2021-07-19','07:59:15','22:41:37',14.7,'A0005','21.04920493907515,106.16967696501294','21.0492769282129,106.16962593194377','2021-07-19 07:59:15','2021-07-19 22:41:37'),(87,'P0005','2021-07-19','08:02:15','17:02:16',9,'A0005','20.9915642,105.836787','20.9915642,105.836787','2021-07-19 08:02:15','2021-07-19 17:02:16'),(88,'P0014','2021-07-19','22:41:58','22:42:14',0.02,'A0006','21.049275554886666,106.16961904621982','21.049265885722132,106.16962713190401','2021-07-19 22:41:58','2021-07-19 22:42:14'),(89,'P0003','2021-07-20','06:48:08','17:04:38',10.27,'A0003','16.459394883787052,107.58088005626573','16.458199006585126,107.59159164850931','2021-07-20 06:48:08','2021-07-20 17:04:38'),(90,'P0005','2021-07-20','07:39:34','17:06:02',9.45,'A0005','20.9915642,105.836787','20.9915642,105.836787','2021-07-20 07:39:34','2021-07-20 17:06:02'),(91,'P0004','2021-07-20','07:45:53','18:11:46',10.43,'A0004','16.5498988,107.5476367','16.5498934,107.54763','2021-07-20 07:45:53','2021-07-20 18:11:46'),(92,'P0002','2021-07-20','07:46:28','18:01:15',10.25,'A0001','10.7539001,106.7162244','10.7538932,106.7163498','2021-07-20 07:46:28','2021-07-20 18:01:15'),(93,'P0001','2021-07-20','07:57:37',NULL,NULL,'','10.7522063,106.6268157',NULL,'2021-07-20 07:57:37',NULL),(94,'P0014','2021-07-20','09:03:40','19:15:14',10.2,'A0006','21.049239490438563,106.16960993509126','21.049346857385512,106.16962616241034','2021-07-20 09:03:40','2021-07-20 19:15:14'),(95,'P0004','2021-07-21','06:39:33','17:27:55',10.8,'A0004','16.549897,107.5476265','16.5498957,107.5476223','2021-07-21 06:39:33','2021-07-21 17:27:55'),(96,'P0003','2021-07-21','07:17:30','17:21:55',10.07,'A0003','16.480836456398713,107.57861195197789','16.458218967608534,107.59171167648466','2021-07-21 07:17:30','2021-07-21 17:21:55'),(97,'P0005','2021-07-21','07:30:38','17:17:04',9.78,'A0005','20.9915642,105.836787','20.9915642,105.836787','2021-07-21 07:30:38','2021-07-21 17:17:04'),(98,'P0002','2021-07-21','07:46:32','17:15:39',9.48,'A0001','10.7539267,106.7162807','10.7539264,106.7162718','2021-07-21 07:46:32','2021-07-21 17:15:39'),(99,'P0014','2021-07-21','07:50:12','19:59:14',12.15,'A0006','21.04927995432509,106.16962631205723','21.049206982728425,106.16962622787743','2021-07-21 07:50:12','2021-07-21 19:59:14'),(100,'P0001','2021-07-21','07:58:58','23:46:25',15.8,'A0001','10.7521479,106.6268594','10.7521902,106.6268383','2021-07-21 07:58:58','2021-07-21 23:46:25'),(101,'P0003','2021-07-22','07:10:13','17:16:29',10.1,'A0003','16.481015023869322,107.57837635033752','16.458288502658096,107.59148188773773','2021-07-22 07:10:13','2021-07-22 17:16:29'),(102,'P0004','2021-07-22','07:30:06','19:28:15',11.97,'A0004','16.5498981,107.5476249','16.5499172,107.5476101','2021-07-22 07:30:06','2021-07-22 19:28:15'),(103,'P0002','2021-07-22','07:45:56','19:34:41',11.82,'A0001','10.7539233,106.7162669','10.753929,106.7162682','2021-07-22 07:45:56','2021-07-22 19:34:41'),(104,'P0014','2021-07-22','07:46:52','19:46:52',12,'A0006','21.01704927566869,105.91849362753786','21.049259700905832,106.1695990459599','2021-07-22 07:46:52','2021-07-22 19:46:52'),(105,'P0005','2021-07-22','07:47:30','17:56:52',10.15,'A0005','20.9915642,105.836787','20.9915642,105.836787','2021-07-22 07:47:30','2021-07-22 17:56:52'),(106,'P0001','2021-07-22','23:40:10','23:40:25',0,'A0001','10.7521449,106.6268576','10.7610342,106.61735309999999','2021-07-22 23:40:10','2021-07-22 23:40:25'),(107,'P0004','2021-07-23','06:52:00','21:16:46',14.4,'A0004','16.5499054,107.5476283','16.5498876,107.5476189','2021-07-23 06:52:00','2021-07-23 21:16:46'),(108,'P0003','2021-07-23','06:59:55','17:11:59',10.2,'A0003','16.45967626119694,107.58052034002165','16.45826720588018,107.5914592415478','2021-07-23 06:59:55','2021-07-23 17:11:59'),(109,'P0001','2021-07-23','07:38:36',NULL,NULL,'A0001','10.7522336,106.6268496',NULL,'2021-07-23 07:38:36',NULL),(110,'P0002','2021-07-23','07:45:19','17:02:21',9.28,'A0001','10.7539426,106.7162173','10.7539315,106.7162603','2021-07-23 07:45:19','2021-07-23 17:02:21'),(111,'P0005','2021-07-23','07:55:01','17:23:13',9.47,'A0005','20.9915642,105.836787','20.9915642,105.836787','2021-07-23 07:55:01','2021-07-23 17:23:13'),(112,'P0014','2021-07-23','08:53:00','18:17:33',9.4,'A0005','21.049229068780345,106.16960273392746','21.049255732218278,106.16970166500704','2021-07-23 08:53:00','2021-07-23 18:17:33'),(113,'P0014','2021-07-23','18:17:46','18:18:08',0.02,'A0006','21.04920386849182,106.16965366678244','21.049276213196194,106.16965811539689','2021-07-23 18:17:46','2021-07-23 18:18:08'),(114,'P0001','2021-07-24','00:02:53','00:03:04',0.02,'A0001','10.752172,106.6268849','10.752172,106.6268849','2021-07-24 00:02:53','2021-07-24 00:03:04'),(115,'P0004','2021-07-26','06:47:42',NULL,NULL,'A0004','16.5499044,107.5476272',NULL,'2021-07-26 06:47:42',NULL),(116,'P0003','2021-07-26','07:21:28',NULL,NULL,'A0003','16.481057982615628,107.578538906149',NULL,'2021-07-26 07:21:28',NULL),(117,'P0014','2021-07-26','07:33:44',NULL,NULL,'A0006','21.04927745217522,106.16962141847068',NULL,'2021-07-26 07:33:44',NULL),(118,'P0002','2021-07-26','07:45:43',NULL,NULL,'A0001','10.7539467,106.7162286',NULL,'2021-07-26 07:45:43',NULL),(119,'P0001','2021-07-26','07:51:07',NULL,NULL,'A0001','10.7521671,106.6268836',NULL,'2021-07-26 07:51:07',NULL),(120,'P0005','2021-07-26','07:58:51',NULL,NULL,'A0005','20.9915642,105.836787',NULL,'2021-07-26 07:58:51',NULL);
/*!40000 ALTER TABLE `tblTTimeCard` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-26  8:41:43
