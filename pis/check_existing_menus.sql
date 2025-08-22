-- Check existing menu structure to see where to add Family Deity
-- Run these queries to understand your current menu setup

-- 1. Check all main menus
SELECT * FROM mst_main_menu ORDER BY mm_id;

-- 2. Check all sub-menus
SELECT sm.*, mm.mm_name as main_menu_name 
FROM mst_sub_menu sm 
JOIN mst_main_menu mm ON sm.mm_id = mm.mm_id 
ORDER BY sm.mm_id, sm.sm_index;

-- 3. Check user rights for a specific user (replace '1' with actual user ID)
SELECT ur.*, mm.mm_name, sm.sm_name 
FROM tbl_user_rights ur 
JOIN mst_main_menu mm ON ur.mm_id = mm.mm_id 
JOIN mst_sub_menu sm ON FIND_IN_SET(sm.sm_id, ur.sm_ids) 
WHERE ur.log_id = 1 
ORDER BY mm.mm_id, sm.sm_index;

-- 4. Find the main menu where City Master is located
SELECT * FROM mst_main_menu WHERE mm_url = 'mst-city.php';

-- 5. Check if there's a "Masters" or "Settings" type main menu
SELECT * FROM mst_main_menu WHERE mm_name LIKE '%master%' OR mm_name LIKE '%setting%' OR mm_name LIKE '%admin%';
