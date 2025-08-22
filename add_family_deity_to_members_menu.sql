-- Add Family Deity as Sub-menu under Members Main Menu (ID 10)
-- This script adds Family Deity to the existing Members menu structure

-- Step 1: Add Family Deity as a sub-menu under Members main menu
INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
(NULL, 10, 'Family Deity', 'family-diety.php', 1, 8);

-- Step 2: Get the new sub-menu ID that was just created
SET @new_family_deity_sm_id = LAST_INSERT_ID();

-- Step 3: Update existing user rights for Members main menu to include Family Deity
-- This adds Family Deity to the existing sm_ids for Members menu access

-- For admin user (assuming user ID 1), update the existing Members menu access
UPDATE `tbl_user_rights` 
SET `sm_ids` = CONCAT(`sm_ids`, ',', @new_family_deity_sm_id)
WHERE `log_id` = 1 AND `mm_id` = 10;

-- Alternative: If you want to replace the entire sm_ids string
-- UPDATE `tbl_user_rights` 
-- SET `sm_ids` = CONCAT('1,7,', @new_family_deity_sm_id)
-- WHERE `log_id` = 1 AND `mm_id` = 10;

-- Step 4: Verify the changes
-- Check the new sub-menu was added
SELECT * FROM `mst_sub_menu` WHERE `mm_id` = 10 ORDER BY `sm_index`;

-- Check admin user rights for Members menu
SELECT * FROM `tbl_user_rights` WHERE `log_id` = 1 AND `mm_id` = 10;

-- Check complete menu structure for Members
SELECT 
    mm.mm_name as main_menu,
    sm.sm_name as sub_menu,
    sm.sm_url,
    sm.sm_show,
    sm.sm_index
FROM `mst_main_menu` mm
JOIN `mst_sub_menu` sm ON mm.mm_id = sm.mm_id
WHERE mm.mm_id = 10
ORDER BY sm.sm_index;
