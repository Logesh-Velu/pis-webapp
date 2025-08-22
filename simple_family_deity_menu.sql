-- Simple Script to Add Family Deity Menu under Members (ID 10)
-- Run this script step by step

-- Step 1: Add Family Deity sub-menu
INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
(NULL, 10, 'Family Deity', 'family-diety.php', 1, 8);

-- Step 2: Get the new sub-menu ID
SELECT LAST_INSERT_ID() as new_submenu_id;

-- Step 3: Update admin user rights (replace 'X' with the ID from Step 2)
-- Example: If the new sub-menu ID is 15, then run:
-- UPDATE `tbl_user_rights` SET `sm_ids` = '1,7,15' WHERE `log_id` = 1 AND `mm_id` = 10;

-- Or use this to append to existing sm_ids:
-- UPDATE `tbl_user_rights` SET `sm_ids` = CONCAT(`sm_ids`, ',15') WHERE `log_id` = 1 AND `mm_id` = 10;
