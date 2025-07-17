<?php

namespace Botble\Announcementadmin;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('Announcementadmins');
        Schema::dropIfExists('Announcementadmins_translations');
    }
}
