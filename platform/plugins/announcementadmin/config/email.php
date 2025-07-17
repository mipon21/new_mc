<?php

return [
    'name' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.title',
    'description' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.description',
    'templates' => [
        'announcementadmin_admin_email' => [
            'title' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.to_admin.title',
            'description' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.to_admin.description',
            'subject' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.to_admin.subject',
            'can_off' => true,
            'variables' => [
                'announcementadmin_email' => 'plugins/announcementadmin::announcementadmin.settings.email.templates.to_admin.announcementadmin_email',
            ],
        ],
    ],
    'variables' => [],
];
