<?php

namespace Botble\DHL\Commands;

use Botble\Setting\Facades\Setting;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand('cms:dhl:init', 'DHL initialization')]
class InitDHLCommand extends Command implements PromptsForMissingInput
{
    use ConfirmableTrait;

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $key = $this->option('key');
        $apiEndpoint = $this->option('endpoint') ?: 'https://express.api.dhl.com/mydhlapi/test';

        $settings = [
            'shipping_dhl_test_key' => $key,
            'shipping_dhl_api_endpoint' => $apiEndpoint,
            'shipping_dhl_status' => $key ? '1' : '0',
            'shipping_dhl_sandbox' => '1',
            'shipping_dhl_logging' => '1',
        ];

        Setting::delete(array_keys($settings));
        Setting::set($settings)->save();

        $this->components->info('DHL configuration initialized successfully!');

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addOption('key', null, InputOption::VALUE_REQUIRED, 'The API Key to use')
            ->addOption('endpoint', null, InputOption::VALUE_OPTIONAL, 'The API endpoint')
            ->addOption('force', 'f', null, 'Force the operation to run when in production');
    }
} 