-- Add Family Deity Menu to Sidebar Navigation
-- This script adds the Family Deity menu under a suitable main menu

-- Option 1: Add as a new main menu item (if you want it as a top-level menu)
INSERT INTO `mst_main_menu` (`mm_id`, `mm_name`, `mm_url`, `mm_class`, `mm_show`, `mm_index`) VALUES
(NULL, 'Family Deity', 'family-diety.php', 'ti-star', 1, 10);

-- Option 2: Add as a sub-menu under an existing main menu (recommended)
-- First, find the main menu ID where you want to add this (e.g., under "Masters" or "Settings")
-- Let's assume you want to add it under a main menu with ID = 2 (adjust this ID as needed)

-- Add the sub-menu item
INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
(NULL, 2, 'Family Deity', 'family-diety.php', 1, 5);

-- Option 3: If you want to add it under "City Master" main menu
-- First, find the main menu ID for City Master
-- SELECT mm_id FROM mst_main_menu WHERE mm_url = 'mst-city.php';

-- Then add the sub-menu (replace 'X' with the actual mm_id from above query)
-- INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
-- (NULL, X, 'Family Deity', 'family-diety.php', 1, 2);

-- Grant access to all users (adjust user IDs as needed)
-- This gives access to user ID 1 (admin) - adjust the log_id as needed
INSERT INTO `tbl_user_rights` (`log_id`, `mm_id`, `sm_ids`) VALUES
(1, 2, '5'); -- Replace '5' with the actual sm_id from the sub-menu insert above

-- If you want to give access to multiple users, repeat the above INSERT with different log_id values
-- INSERT INTO `tbl_user_rights` (`log_id`, `mm_id`, `sm_ids`) VALUES
-- (2, 2, '5'),
-- (3, 2, '5');
