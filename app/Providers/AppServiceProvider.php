<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    $settings = Setting::first();

    if ($settings && $settings->smtp_enabled) {

        Config::set('mail.default', 'smtp');

        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => trim($settings->smtp_host),
            'port'       => (int) $settings->smtp_port,
            'encryption' => trim($settings->smtp_encryption),
            'username'   => trim($settings->smtp_username),
            'password'   => trim($settings->smtp_password),
        ]);

        Config::set('mail.from.address', trim($settings->smtp_username));
        Config::set('mail.from.name', 'PhotoProof');
    }
}
}
