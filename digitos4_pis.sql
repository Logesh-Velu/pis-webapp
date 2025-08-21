-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2025 at 05:31 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digitos4_pis`
--

-- --------------------------------------------------------

--
-- Table structure for table `mst_application`
--

DROP TABLE IF EXISTS `mst_application`;
CREATE TABLE `mst_application` (
  `app_id` int(1) NOT NULL,
  `app_name` varchar(25) DEFAULT NULL,
  `app_code` varchar(2) NOT NULL,
  `app_client_name` varchar(50) DEFAULT NULL,
  `app_client_logo` varchar(20) DEFAULT NULL,
  `app_client_address` varchar(100) DEFAULT NULL,
  `app_client_phone` varchar(20) DEFAULT NULL,
  `app_client_email` varchar(30) DEFAULT NULL,
  `app_client_web` varchar(30) DEFAULT NULL,
  `app_client_validity` date DEFAULT NULL,
  `app_client_status` int(1) DEFAULT NULL,
  `app_page_title` varchar(30) DEFAULT NULL,
  `app_page_copyright` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_application`
--

INSERT INTO `mst_application` (`app_id`, `app_name`, `app_code`, `app_client_name`, `app_client_logo`, `app_client_address`, `app_client_phone`, `app_client_email`, `app_client_web`, `app_client_validity`, `app_client_status`, `app_page_title`, `app_page_copyright`) VALUES
(1, 'Panirendar CRM', 'PI', 'panirendar.com', 'logo.png', 'Coimbatore', '+91-99429-03373', 'kabilanju@gmail.com', 'https://panirendar.com/', '2020-12-31', 1, 'Panirendar CRM', 'Panirendar.com');

-- --------------------------------------------------------

--
-- Table structure for table `mst_city`
--

DROP TABLE IF EXISTS `mst_city`;
CREATE TABLE `mst_city` (
  `city_id` tinyint(4) NOT NULL,
  `state_id` tinyint(4) NOT NULL,
  `city_name` varchar(30) NOT NULL,
  `state_name` varchar(30) NOT NULL,
  `city_tamil` varchar(100) NOT NULL,
  `state_tamil` varchar(100) NOT NULL,
  `city_status` tinyint(1) NOT NULL DEFAULT '1',
  `lm_by` smallint(6) NOT NULL,
  `lm_dtm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mst_city`
--

INSERT INTO `mst_city` (`city_id`, `state_id`, `city_name`, `state_name`, `city_tamil`, `state_tamil`, `city_status`, `lm_by`, `lm_dtm`) VALUES
(1, 1, 'Tiruppur', 'Tamilnadu', 'திருப்பூர்	', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:28:05'),
(2, 1, 'Erode', 'Tamilnadu', 'ஈரோடு', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:31:27'),
(3, 1, 'Vellakoil', 'Tamilnadu', 'வெள்ளக்கோவில்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:30:45'),
(4, 1, 'Chennai', 'Tamilnadu', 'சென்னை	', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:30:26'),
(5, 1, 'Coimbatore', 'Tamilnadu', 'கோயம்பத்தூர்				', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:30:07'),
(6, 1, 'Bengaluru', 'Karnataka', '', '', 1, 1, '2021-03-31 08:48:41'),
(7, 1, 'NAMAKKAL DISTRICT', 'Tamilnadu', 'நாமக்கல் மாவட்டம்', 'தமிழ்நாடு', 1, 1, '2022-06-11 12:53:07'),
(8, 1, 'KARUR ', 'Tamilnadu', 'கரூர்', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:33:13'),
(9, 1, 'CALICUT', 'Kerala', '', '', 1, 1, '2021-03-31 12:21:37'),
(10, 1, 'CHIDAMBARAM', 'Tamilnadu', 'சிதம்பரம்	', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:29:25'),
(11, 1, 'Dindugul', 'Tamilnadu', 'திண்டுக்கல்', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:30:59'),
(12, 1, 'Kangeyam', 'Tamilnadu', 'காங்கேயம்', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:31:38'),
(13, 1, 'Kulithalai', 'Tamilnadu', 'குளித்தலை', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:32:22'),
(14, 1, 'Pune', 'Maharashtra', '', '', 1, 1, '2021-03-31 12:25:27'),
(15, 1, 'MANGALORE', 'Karnataka', '', '', 1, 1, '2021-03-31 12:25:53'),
(16, 1, 'Palladam', 'Tamilnadu', 'பல்லடம்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:33:28'),
(17, 1, 'PERAMBALUR', 'Tamilnadu', 'பெரம்பலூர்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:51:12'),
(18, 1, 'PUDHUKOTTAI', 'Tamilnadu', 'புதுக்கோட்டை', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:31:51'),
(19, 1, 'SIVAGANGAI  DISTRICT', 'Tamilnadu', 'சிவகங்கை, மாவட்டம்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:33:51'),
(20, 1, 'UTHUKULI', 'Tamilnadu', 'ஊத்துக்குளி	', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:30:29'),
(21, 1, 'UDUPI', 'Karnataka', '', '', 1, 1, '2021-03-31 12:32:27'),
(22, 1, 'UDUMALPET', 'Tamilnadu', 'உடுமலை', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:30:05'),
(23, 1, 'TIRUCHI  DISTRICT', 'Tamilnadu', 'திருச்சி மாவட்டம்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:31:31'),
(24, 1, 'Velayuthampalayam', 'Tamilnadu', 'வேலாயுதம்பாளையம்', 'தமிழ்நாடு', 1, 1, '2022-06-03 10:33:17'),
(25, 1, 'SIVAYAM ', 'Tamilnadu', 'சிவாயம்	', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:50:58'),
(26, 1, 'SINGAPORE', 'SINGAPORE', '', '', 1, 1, '2021-03-31 12:33:56'),
(27, 1, 'DHARAPURAM', 'Tamilnadu', 'தாராபுரம்', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:30:41'),
(28, 1, 'tirupur1', '', '', '', 0, 1, '2021-04-16 12:29:05'),
(29, 1, 'Vijayapuram , Tiruppur', 'Tamilnadu', 'விஜயாபுரம், திருப்பூர்', 'தமிழ்நாடு', 1, 1, '2022-05-12 18:31:10'),
(30, 1, 'Amaravathipalayam, TIRUPUR', 'Tamilnadu', 'அமராவதிபாளையம், திருப்பூர்', 'தமிழ்நாடு', 1, 1, '2022-04-18 12:20:53'),
(31, 1, 'Pollikalipalayam, TIRUPPUR ', 'Tamilnadu', 'பொல்லிக்காலிபாளையம்', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:34:54'),
(32, 1, 'Karaikudi', 'Tamilnadu', 'காரைக்குடி', 'தமிழ்நாடு', 1, 1, '2022-04-20 11:31:51'),
(33, 1, 'PYA', 'TAMILNADU', '', '', 1, 1, '2021-12-07 22:09:15'),
(34, 1, 'Paramathi Velur', 'Tamilnadu', 'பரமத்தி வேலூர்', 'தமிழ்நாடு', 1, 1, '2022-06-03 10:33:58'),
(35, 1, 'Theni', 'Tamilnadu', 'தேனி		', 'தமிழ்நாடு', 1, 1, '2022-06-03 10:37:24'),
(36, 1, 'KARUR DISTRICT', 'TAMILNADU', 'கரூர் மாவட்டம்', 'தமிழ்நாடு', 1, 1, '2022-06-23 15:16:58'),
(37, 1, 'Chennimalai', 'Tamilnadu', 'சென்னிமலை	', 'தமிழ்நாடு', 1, 1, '2022-08-16 07:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `mst_main_menu`
--

DROP TABLE IF EXISTS `mst_main_menu`;
CREATE TABLE `mst_main_menu` (
  `mm_id` double NOT NULL,
  `mm_name` varchar(50) NOT NULL,
  `mm_url` varchar(50) NOT NULL,
  `mm_class` varchar(40) NOT NULL,
  `mm_show` int(11) NOT NULL DEFAULT '1',
  `mm_default` int(11) NOT NULL DEFAULT '0',
  `mm_for` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_main_menu`
--

INSERT INTO `mst_main_menu` (`mm_id`, `mm_name`, `mm_url`, `mm_class`, `mm_show`, `mm_default`, `mm_for`) VALUES
(1, 'Adrs Management', 'javascript:;', 'ti-id-badge', 1, 0, 0),
(2, 'User Accounts', 'javascript:;', 'ti-user', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mst_sub_menu`
--

DROP TABLE IF EXISTS `mst_sub_menu`;
CREATE TABLE `mst_sub_menu` (
  `sm_id` double NOT NULL,
  `mm_id` double NOT NULL,
  `sm_index` int(11) NOT NULL,
  `sm_name` varchar(300) NOT NULL,
  `sm_url` varchar(50) NOT NULL,
  `related_url` varchar(100) NOT NULL,
  `sm_query` varchar(100) NOT NULL,
  `sm_show` int(11) NOT NULL DEFAULT '1',
  `sm_default` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_sub_menu`
--

INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_index`, `sm_name`, `sm_url`, `related_url`, `sm_query`, `sm_show`, `sm_default`) VALUES
(1, 1, 1, 'City Master', 'mst-city.php', '', '', 1, 0),
(2, 1, 2, 'Members', 'members.php', 'members-add.php,members-import.php', '', 1, 0),
(3, 1, 3, 'Print Address', 'address-print.php', 'mst-events-add.php', '', 1, 0),
(4, 2, 1, 'List of Users', 'list-users.php', 'mst-user-add.php,mst-users-rights.php', '', 1, 0),
(5, 1, 4, 'Print Tamil Address', 'address-print-tamil.php', '', '', 1, 0),
(6, 1, 6, 'Tamil Update', 'members-tamil.php', '', '', 1, 0),
(7, 1, 4, 'Print Name List', 'name-list-print.php', '', '', 1, 0),
(8, 1, 5, 'MOI Note Name List ', 'moi-list-print.php', '', '', 1, 0),
(9, 1, 4, 'Address with Photo', 'address-photo-print.php', '', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email`
--

DROP TABLE IF EXISTS `tbl_email`;
CREATE TABLE `tbl_email` (
  `req_id` smallint(6) NOT NULL,
  `req_date` date NOT NULL,
  `req_sent` varchar(40) NOT NULL,
  `req_subject` varchar(50) NOT NULL,
  `req_text` text NOT NULL,
  `req_dtm` datetime NOT NULL,
  `req_status` tinyint(1) NOT NULL,
  `log_id` smallint(6) NOT NULL,
  `lm_dtm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_email`
--

INSERT INTO `tbl_email` (`req_id`, `req_date`, `req_sent`, `req_subject`, `req_text`, `req_dtm`, `req_status`, `log_id`, `lm_dtm`) VALUES
(1, '2021-02-26', 'kabilanju@gmail.com', 'This is TEST Email', 'test message', '2021-02-26 21:50:38', 0, 1, '2021-02-26 21:50:38'),
(2, '2021-02-26', 'kabilanju@gmail.com', 'TEST', 'Hi <b>Sharu</b>,<br><br>This is test message from email..<br><br><br><br>', '2021-02-26 22:23:21', 0, 1, '2021-02-26 22:23:21'),
(3, '2021-02-26', 'kabilanju@gmail.com', 'TEST', 'Hi <b>Sharu</b>,<br><br>This is test message from email..<br><br><br><br>', '2021-02-26 22:25:47', 0, 1, '2021-02-26 22:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

DROP TABLE IF EXISTS `tbl_login`;
CREATE TABLE `tbl_login` (
  `log_id` double NOT NULL,
  `log_name` varchar(30) NOT NULL,
  `log_pwd` varchar(50) NOT NULL,
  `usr_type` varchar(3) NOT NULL,
  `usr_name` varchar(50) NOT NULL,
  `usr_dest` varchar(20) NOT NULL,
  `usr_city` varchar(20) NOT NULL,
  `usr_mobile` varchar(10) NOT NULL,
  `lm_by` smallint(6) NOT NULL,
  `lm_dtm` datetime NOT NULL,
  `log_dtm` datetime NOT NULL,
  `usr_avatar` varchar(20) NOT NULL,
  `log_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`log_id`, `log_name`, `log_pwd`, `usr_type`, `usr_name`, `usr_dest`, `usr_city`, `usr_mobile`, `lm_by`, `lm_dtm`, `log_dtm`, `usr_avatar`, `log_status`) VALUES
(1, 'admin@panirendar.com', '947ce907f447b612bed346b1bd57c4db', 'A', 'Admin', 'Admin', '', '9942903373', 1, '2021-01-23 11:41:57', '2025-08-20 11:15:46', 'male.jpg', 1),
(2, 'kabilanju@gmail.com', '2645d4e62ecaaa484577f8cf739c221c', 'S', 'Kabilan J', 'User', '', '9942903373', 1, '2021-03-31 08:31:49', '2021-03-29 00:43:32', 'male.jpg', 1),
(3, 'aimmshan@gmail.com', 'e268b46a495392e0016f5007166d1a12', 'S', 'shanmugam', '', '', '9843038056', 1, '2021-03-31 22:39:06', '0000-00-00 00:00:00', 'male.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_members`
--

DROP TABLE IF EXISTS `tbl_members`;
CREATE TABLE `tbl_members` (
  `memb_id` double NOT NULL,
  `memb_regdt` date NOT NULL,
  `memb_group` tinyint(1) NOT NULL DEFAULT '1',
  `memb_code` varchar(6) NOT NULL,
  `memb_type` char(1) NOT NULL DEFAULT 'C',
  `memb_fname` varchar(75) NOT NULL,
  `memb_initial` varchar(10) NOT NULL,
  `memb_lname` varchar(50) NOT NULL,
  `memb_sname` varchar(50) NOT NULL,
  `memb_mobile` varchar(10) NOT NULL,
  `memb_email` varchar(50) NOT NULL,
  `memb_add1` varchar(100) NOT NULL,
  `memb_add2` varchar(100) NOT NULL,
  `memb_city` tinyint(4) NOT NULL,
  `memb_native` tinyint(4) NOT NULL,
  `memb_state` varchar(30) NOT NULL,
  `memb_pincode` varchar(6) NOT NULL,
  `memb_remarks` text NOT NULL,
  `memb_tamil_update` tinyint(1) NOT NULL,
  `memb_fname_tamil` varchar(255) NOT NULL,
  `memb_initial_tamil` varchar(50) NOT NULL,
  `memb_lname_tamil` varchar(255) NOT NULL,
  `memb_sname_tamil` varchar(255) NOT NULL,
  `memb_add1_tamil` varchar(255) NOT NULL,
  `memb_add2_tamil` varchar(255) NOT NULL,
  `memb_state_tamil` varchar(100) NOT NULL,
  `memb_photo` varchar(255) NOT NULL,
  `lm_dtm` datetime NOT NULL,
  `lm_by` double NOT NULL,
  `memb_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_members`
--

INSERT INTO `tbl_members` (`memb_id`, `memb_regdt`, `memb_group`, `memb_code`, `memb_type`, `memb_fname`, `memb_initial`, `memb_lname`, `memb_sname`, `memb_mobile`, `memb_email`, `memb_add1`, `memb_add2`, `memb_city`, `memb_native`, `memb_state`, `memb_pincode`, `memb_remarks`, `memb_tamil_update`, `memb_fname_tamil`, `memb_initial_tamil`, `memb_lname_tamil`, `memb_sname_tamil`, `memb_add1_tamil`, `memb_add2_tamil`, `memb_state_tamil`, `memb_photo`, `lm_dtm`, `lm_by`, `memb_status`) VALUES
(1, '2021-04-19', 1, 'M001', 'C', 'ANAND,', 'R.', 'S/O.RAJALINGAM,', '', '9894630100', '', 'VALIPALAYAM,', 'BHARATHI STREET,', 1, 0, 'Tamilnadu', '641601', '', 1, 'ஆனந்த்		', 'R.	', 'த.பெ. ராஜாலிங்கம்', '', 'வாலிபாளையம்', 'பாரதி வீதி,', 'தமிழ்நாடு', '', '2022-05-12 18:51:55', 1, 1),
(2, '2021-04-19', 1, 'M002', 'C', 'ARJUNAN,', 'R.', '', '', '9677877773', '', '33, K.P.N. COLONY ,', 'I ST STREET,', 1, 0, 'Tamilnadu', '641601', '', 1, 'அர்ஜுனன்		', 'R.', '', '', '33, கே.பி.என்.காலனி', 'முதல் வீதி,', 'தமிழ்நாடு', '', '2022-05-12 18:25:09', 1, 1),
(3, '2021-04-19', 1, 'M003', 'C', 'ARULKUMARAN,', 'R.', '', '', '', '', '1/456, CHETTIPALAYAM,', 'SAGAJPURAM,', 1, 0, 'Tamilnadu', '641608', '', 1, 'அருள் குமரன்	', 'R.', '', '', '1/456, செட்டிபாளையம்', 'சகஜபுரம், ', 'தமிழ்நாடு', '', '2022-05-12 18:29:25', 1, 1),
(4, '2021-04-19', 1, 'M004', 'C', 'ARUNACHALAM,', 'R.', '', '', '9842271171', '', '13/2, OM SAKTHI NAGAR,', '2 ND STREET ,', 1, 0, 'Tamilnadu', '641601', '', 1, 'அருணாச்சலம்	', 'R.', '', '', '13/2, ஓம் சக்தி நகர் 2வது வீதி,', 'கே.பி.என். காலனி,', 'தமிழ்நாடு', '', '2022-05-25 14:24:26', 1, 1),
(5, '2021-04-19', 1, 'M005', 'C', 'AYYAPPAN', 'K.P.K.M.', 'SELVA NILAYAM', '', '9842273536', '', '37, KONGU NAGAR,', '2 ND STREET ,', 1, 0, 'Tamilnadu', '641607', '', 1, 'ஐயப்பன்	', 'K.P.K.M', '', '', '37, கொங்கு நகர்', '2வது வீதி', 'தமிழ்நாடு', '', '2022-05-25 14:25:41', 1, 1),
(6, '2021-04-19', 1, 'M006', 'C', 'BABU,', 'P.', '', '', '9843227036', '', '8/28, RUBA HOSPITAL OPPOSITE,', 'M.P. NAGAR , 2 ND STREET ,', 1, 0, 'Tamilnadu', '641607', '', 1, 'பாபு', 'P.', '', '', '8/28, ரூபா மருத்துவமனை எதிரில்', 'M.P.நகர், 2வது வீதி,', 'தமிழ்நாடு', '', '2022-05-25 14:26:44', 1, 1),
(7, '2021-04-19', 1, 'M007', 'C', 'BABY,', 'K.N.', 'W/O.KARUPANNAN ( BALU ),', '', '', '', '11, GOVINDHA GOUNDER STREET ,', 'GOVINDHA GOUNDER STREET ,', 1, 0, 'Tamilnadu', '641604', '', 1, 'பேபி', 'K.N.', 'க.பெ. கருப்பண்ணன் (பாலு)', '', '11, கோவிந்த கவுண்டர் வீதி,', '', 'தமிழ்நாடு', '', '2022-05-25 14:27:53', 1, 1),
(8, '2021-04-19', 1, 'M008', 'C', 'BAKKIYAM,', 'R.', '', '', '', '', '12A, MAIN ROAD ,', 'NANTHAVANATHOTTAM', 1, 0, 'Tamilnadu', '641604', '', 1, 'பாக்கியம் 		', 'R.', '', '', '12A, மெயின் ரோடு', 'நந்தவனத்தோட்டம்', 'தமிழ்நாடு', '', '2022-05-25 14:28:37', 1, 1),
(9, '2021-04-19', 1, 'M009', 'C', 'BALAJI,', 'C.K.R.R.', '', '', '', '', '24, K.P.N. COLONY ,', 'III STREET,', 1, 0, 'Tamilnadu', '641601', '', 1, 'பாலாஜி', 'C.K.R.R.', '', '', '24, கே.பி.என்.காலனி', '3வது வீதி', 'தமிழ்நாடு', '', '2022-05-25 14:29:11', 1, 1),
(10, '2021-04-19', 1, 'M010', 'C', 'BALAKRISHNAN,', 'K.T.', '', '', '', '', '23, KONGU NAGAR,', '1 ST STREET,', 1, 0, 'Tamilnadu', '641607', '', 1, 'பாலகிருஷ்ணன்', 'K.T.', '', '', '23, கொங்கு நகர்', 'முதல் வீதி', 'தமிழ்நாடு', '', '2022-05-25 14:30:06', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_members_temp`
--

DROP TABLE IF EXISTS `tbl_members_temp`;
CREATE TABLE `tbl_members_temp` (
  `auto_id` double NOT NULL,
  `memb_group` tinyint(1) NOT NULL DEFAULT '1',
  `memb_code` varchar(6) NOT NULL,
  `memb_type` char(1) NOT NULL DEFAULT 'C',
  `memb_fname` varchar(75) NOT NULL,
  `memb_initial` varchar(10) NOT NULL,
  `memb_lname` varchar(50) NOT NULL,
  `memb_mobile` varchar(10) NOT NULL,
  `memb_email` varchar(50) NOT NULL,
  `memb_add1` varchar(100) NOT NULL,
  `memb_add2` varchar(100) NOT NULL,
  `memb_city` tinyint(4) NOT NULL,
  `memb_cityname` varchar(30) NOT NULL,
  `memb_state` varchar(30) NOT NULL,
  `memb_pincode` varchar(6) NOT NULL,
  `import_status` tinyint(1) NOT NULL,
  `import_remarks` varchar(50) NOT NULL,
  `session_id` varchar(15) NOT NULL,
  `dt` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_rights`
--

DROP TABLE IF EXISTS `tbl_user_rights`;
CREATE TABLE `tbl_user_rights` (
  `auto_id` double NOT NULL,
  `usr_type` varchar(3) NOT NULL,
  `log_id` double NOT NULL,
  `usr_id` double NOT NULL,
  `mm_id` int(2) NOT NULL,
  `sm_id` int(3) NOT NULL,
  `sm_ids` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_rights`
--

INSERT INTO `tbl_user_rights` (`auto_id`, `usr_type`, `log_id`, `usr_id`, `mm_id`, `sm_id`, `sm_ids`) VALUES
(1, '', 1, 0, 4, 0, '0,8,0'),
(6, '', 1, 0, 1, 0, '0,1,2,8,3,9,7,5,6,0'),
(7, '', 1, 0, 2, 0, '0,4,0'),
(8, '', 2, 0, 1, 0, '0,3,0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mst_application`
--
ALTER TABLE `mst_application`
  ADD PRIMARY KEY (`app_id`);

--
-- Indexes for table `mst_city`
--
ALTER TABLE `mst_city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `mst_main_menu`
--
ALTER TABLE `mst_main_menu`
  ADD PRIMARY KEY (`mm_id`);

--
-- Indexes for table `mst_sub_menu`
--
ALTER TABLE `mst_sub_menu`
  ADD PRIMARY KEY (`sm_id`);

--
-- Indexes for table `tbl_email`
--
ALTER TABLE `tbl_email`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_name` (`log_name`),
  ADD KEY `log_pwd` (`log_pwd`),
  ADD KEY `usr_type` (`usr_type`);

--
-- Indexes for table `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`memb_id`);

--
-- Indexes for table `tbl_members_temp`
--
ALTER TABLE `tbl_members_temp`
  ADD PRIMARY KEY (`auto_id`);

--
-- Indexes for table `tbl_user_rights`
--
ALTER TABLE `tbl_user_rights`
  ADD PRIMARY KEY (`auto_id`),
  ADD KEY `log_id` (`log_id`),
  ADD KEY `mm_id` (`mm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mst_application`
--
ALTER TABLE `mst_application`
  MODIFY `app_id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mst_city`
--
ALTER TABLE `mst_city`
  MODIFY `city_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `mst_main_menu`
--
ALTER TABLE `mst_main_menu`
  MODIFY `mm_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_sub_menu`
--
ALTER TABLE `mst_sub_menu`
  MODIFY `sm_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_email`
--
ALTER TABLE `tbl_email`
  MODIFY `req_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `log_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `memb_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=799;

--
-- AUTO_INCREMENT for table `tbl_members_temp`
--
ALTER TABLE `tbl_members_temp`
  MODIFY `auto_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4838;

--
-- AUTO_INCREMENT for table `tbl_user_rights`
--
ALTER TABLE `tbl_user_rights`
  MODIFY `auto_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
