<?php

return [
    'env'             => env('APP_ENV', 'production'),
    'debug'           => env('APP_DEBUG', false),

    'url'             => env('APP_URL', 'http://localhost/dev/tucanov3'),

    'timezone'        => 'America/Sao_Paulo',
    'locale'          => 'pt_BR',
    'fallback_locale' => 'en',

    'key'             => env('APP_KEY', 'carioquinha20161carioquinha20161'),

    'cipher'          => 'AES-256-CBC',
    'log'             => env('APP_LOG', 'daily'),
    'log_max_files'   => env('APP_LOG_MAX_FILES', 30),

    'providers' => [
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Laracasts\Generators\GeneratorsServiceProvider::class,
        Zizaco\Entrust\EntrustServiceProvider::class,
        Arcanedev\LogViewer\LogViewerServiceProvider::class,
        Spatie\Browsershot\BrowsershotServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Vinelab\Cdn\CdnServiceProvider::class,
        Sentry\SentryLaravel\SentryLaravelServiceProvider::class,
        Jenssegers\Mongodb\MongodbServiceProvider::class,
        Nwidart\Modules\LaravelModulesServiceProvider::class,
        Spatie\Backup\BackupServiceProvider::class,
        Spatie\FailedJobMonitor\FailedJobMonitorServiceProvider::class,
        Sofa\Eloquence\ServiceProvider::class
    ],


    'aliases' => [
        'App'          => Illuminate\Support\Facades\App::class,
        'Artisan'      => Illuminate\Support\Facades\Artisan::class,
        'Auth'         => Illuminate\Support\Facades\Auth::class,
        'Blade'        => Illuminate\Support\Facades\Blade::class,
        'Cache'        => Illuminate\Support\Facades\Cache::class,
        'Config'       => Illuminate\Support\Facades\Config::class,
        'Cookie'       => Illuminate\Support\Facades\Cookie::class,
        'Crypt'        => Illuminate\Support\Facades\Crypt::class,
        'DB'           => Illuminate\Support\Facades\DB::class,
        'Eloquent'     => Illuminate\Database\Eloquent\Model::class,
        'Event'        => Illuminate\Support\Facades\Event::class,
        'File'         => Illuminate\Support\Facades\File::class,
        'Gate'         => Illuminate\Support\Facades\Gate::class,
        'Hash'         => Illuminate\Support\Facades\Hash::class,
        'Lang'         => Illuminate\Support\Facades\Lang::class,
        'Log'          => Illuminate\Support\Facades\Log::class,
        'Mail'         => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password'     => Illuminate\Support\Facades\Password::class,
        'Queue'        => Illuminate\Support\Facades\Queue::class,
        'Redirect'     => Illuminate\Support\Facades\Redirect::class,
        'Redis'        => Illuminate\Support\Facades\Redis::class,
        'Request'      => Illuminate\Support\Facades\Request::class,
        'Response'     => Illuminate\Support\Facades\Response::class,
        'Route'        => Illuminate\Support\Facades\Route::class,
        'Schema'       => Illuminate\Support\Facades\Schema::class,
        'Session'      => Illuminate\Support\Facades\Session::class,
        'Storage'      => Illuminate\Support\Facades\Storage::class,
        'URL'          => Illuminate\Support\Facades\URL::class,
        'Validator'    => Illuminate\Support\Facades\Validator::class,
        'View'         => Illuminate\Support\Facades\View::class,

        'JWTAuth'      => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory'   => Tymon\JWTAuth\Facades\JWTFactory::class,

        'Debugbar'     => Barryvdh\Debugbar\Facade::class,

        'Entrust'      => Zizaco\Entrust\EntrustFacade::class,

        'Excel'        => Maatwebsite\Excel\Facades\Excel::class,

        'Sentry'       => Sentry\SentryLaravel\SentryFacade::class,

        'Moloquent'    => Jenssegers\Mongodb\Eloquent\Model::class,

        'Module' => Nwidart\Modules\Facades\Module::class,
    ],

];
