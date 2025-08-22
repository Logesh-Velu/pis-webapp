-- Family Deity Table Structure
-- Table: tbl_family_diety

CREATE TABLE `tbl_family_diety` (
  `fd_id` int(11) NOT NULL AUTO_INCREMENT,
  `fd_name` varchar(100) NOT NULL COMMENT 'Family Deity Name in English',
  `tn_name` varchar(255) NOT NULL COMMENT 'Family Deity Name in Tamil',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status: 1=Active, 0=Inactive',
  `lm_dtm` datetime NOT NULL COMMENT 'Last Modified Date and Time',
  `lm_by` int(11) NOT NULL COMMENT 'Last Modified By User ID',
  PRIMARY KEY (`fd_id`),
  UNIQUE KEY `uk_fd_name` (`fd_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Family Deity Master Table';

-- Sample data (optional)
INSERT INTO `tbl_family_diety` (`fd_name`, `tn_name`, `status`, `lm_dtm`, `lm_by`) VALUES
('Lord Ganesha', 'விநாயகர்', 1, NOW(), 1),
('Lord Murugan', 'முருகன்', 1, NOW(), 1),
('Lord Shiva', 'சிவன்', 1, NOW(), 1),
('Goddess Lakshmi', 'லட்சுமி', 1, NOW(), 1),
('Lord Venkateswara', 'வெங்கடேஸ்வரர்', 1, NOW(), 1);
