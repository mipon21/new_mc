<?php

namespace Botble\Announcementadmin\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class SendMailsAfterCustomerRegistered
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        $customer = $event->user;

        // Ensure the user is an instance of Customer
        if (! $customer instanceof Customer) {
            Log::warning('Registered user is not a Customer instance.', ['user' => $customer]);
            return;
        }

        // Send email using the EmailHandler
        EmailHandler::setModule(ANNOUNCEMENTADMIN_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'announcementadmin_email' => $customer->email,
            ])
            ->sendUsingTemplate(
                'announcementadmin_admin_email',
                setting('email_template_email_contact', get_admin_email()->first() ?: 'demo@example.com')
            );

        Log::info('Email sent to customer after registration.', ['customer_email' => $customer->email]);
    }
}
