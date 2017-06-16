INSERT IGNORE INTO `user_perms` ( `name`, `group`, `role`, `about` ) VALUES
    ( 'create_post_tag',  'Post Tag', 'Management', 'Allow user to create new post tag' ),
    ( 'read_post_tag',    'Post Tag', 'Management', 'Allow user to view all post tags' ),
    ( 'remove_post_tag',  'Post Tag', 'Management', 'Allow user to remove exists post tag' ),
    ( 'update_post_tag',  'Post Tag', 'Management', 'Allow user to update exists post tag' );