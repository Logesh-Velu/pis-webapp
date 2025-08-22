# How to Add Family Deity Menu to Sidebar

## Step 1: Check Your Current Menu Structure
First, run these SQL queries in your database to see the current menu setup:

```sql
-- Check all main menus
SELECT * FROM mst_main_menu ORDER BY mm_id;

-- Check all sub-menus
SELECT sm.*, mm.mm_name as main_menu_name 
FROM mst_sub_menu sm 
JOIN mst_main_menu mm ON sm.mm_id = mm.mm_id 
ORDER BY sm.mm_id, sm.sm_index;

-- Find where City Master is located
SELECT * FROM mst_main_menu WHERE mm_url = 'mst-city.php';
```

## Step 2: Choose Where to Add Family Deity

### Option A: Add as Sub-menu under City Master's main menu
If City Master is under a main menu (like "Masters" or "Settings"):

1. **Find the main menu ID** where City Master is located
2. **Add Family Deity as a sub-menu** under that main menu

### Option B: Add as a new main menu item
If you want Family Deity as a top-level menu item.

## Step 3: Add the Menu Items

### If adding as sub-menu (Recommended):
```sql
-- Replace 'X' with the actual main menu ID from Step 1
INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
(NULL, X, 'Family Deity', 'family-diety.php', 1, 5);
```

### If adding as main menu:
```sql
INSERT INTO `mst_main_menu` (`mm_id`, `mm_name`, `mm_url`, `mm_class`, `mm_show`, `mm_index`) VALUES
(NULL, 'Family Deity', 'family-diety.php', 'ti-star', 1, 10);
```

## Step 4: Grant User Access

### For sub-menu:
```sql
-- Replace 'X' with main menu ID, 'Y' with sub-menu ID from Step 3
INSERT INTO `tbl_user_rights` (`log_id`, `mm_id`, `sm_ids`) VALUES
(1, X, 'Y'); -- For user ID 1
```

### For main menu:
```sql
-- Replace 'X' with main menu ID from Step 3
INSERT INTO `tbl_user_rights` (`log_id`, `mm_id`, `sm_ids`) VALUES
(1, X, ''); -- For user ID 1
```

## Step 5: Test the Menu
1. Refresh your browser
2. Log out and log back in
3. Check if Family Deity appears in the sidebar

## Common Issues & Solutions:

### Issue 1: Menu doesn't appear
- Check if `mm_show` and `sm_show` are set to 1
- Verify user rights are properly set
- Check if the user has access to the parent main menu

### Issue 2: Permission denied
- Ensure the user ID in `tbl_user_rights` matches the logged-in user
- Check if `sm_ids` contains the correct sub-menu ID

### Issue 3: Menu appears but link doesn't work
- Verify the file `family-diety.php` exists
- Check file permissions
- Ensure the user has admin access to the page

## Example Complete Setup:
```sql
-- Assuming City Master is under main menu ID 2
-- Add Family Deity sub-menu
INSERT INTO `mst_sub_menu` (`sm_id`, `mm_id`, `sm_name`, `sm_url`, `sm_show`, `sm_index`) VALUES
(NULL, 2, 'Family Deity', 'family-diety.php', 1, 5);

-- Get the sub-menu ID that was just created
SET @new_sm_id = LAST_INSERT_ID();

-- Grant access to user ID 1
INSERT INTO `tbl_user_rights` (`log_id`, `mm_id`, `sm_ids`) VALUES
(1, 2, @new_sm_id);
```

## Notes:
- The `mm_index` and `sm_index` control the order of menus
- `mm_show` and `sm_show` control visibility (1 = visible, 0 = hidden)
- `mm_class` is for Font Awesome icons (e.g., 'ti-star', 'ti-home')
- Always backup your database before making changes
