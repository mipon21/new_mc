<?php

return [
    'name' => 'Announcementadmins',
    'create' => 'New announcementadmin',
    'settings' => [
        'email' => [
            'templates' => [
                'title' => 'Announcement Admin',
                'description' => 'Config announcementadmins email templates',
                'to_admin' => [
                    'title' => 'Email send to admin',
                    'description' => 'Template for sending email to admin',
                    'subject' => 'New user subscribed your announcementadmins',
                    'announcementadmin_email' => 'Email of user',
                ],
                'to_user' => [
                    'title' => 'Email send to user',
                    'description' => 'Template for sending email to subscriber',
                    'subject' => '{{ site_title }}: Subscription Confirmed!',
                    'announcementadmins_name' => 'Full name of user who subscribe announcementadmins',
                    'announcementadmins_email' => 'Email of user who subscribe announcementadmins',
                    'announcementadmins_unsubscribe_link' => 'Link for unsubscribe announcementadmins',
                    'announcementadmins_unsubscribe_url' => 'URL for unsubscribe announcementadmins',
                ],
            ],
        ],
        'title' => 'Announcementadmins',
        'panel_description' => 'View and update Announcementadmins settings',
        'description' => 'Settings for Announcementadmins (auto send Announcementadmins email to SendGrid, Mailchimp... when someone register Announcementadmins on website).',
        'mailchimp_api_key' => 'Mailchimp API Key',
        'mailchimp_list_id' => 'Mailchimp List ID',
        'mailchimp_list' => 'Mailchimp List',
        'sendgrid_api_key' => 'Sendgrid API Key',
        'sendgrid_list_id' => 'Sendgrid List ID',
        'sendgrid_list' => 'Sendgrid List',
        'enable_announcementadmins_contacts_list_api' => 'Enable Announcementadmins contacts list API?',
    ],
];
