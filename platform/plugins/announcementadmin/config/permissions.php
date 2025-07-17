<?php

return [
    [
        'name' => 'Announcementadmins',
        'flag' => 'announcementadmin.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'announcementadmin.create',
        'parent_flag' => 'announcementadmin.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'announcementadmin.edit',
        'parent_flag' => 'announcementadmin.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'announcementadmin.destroy',
        'parent_flag' => 'announcementadmin.index',
    ],
];
