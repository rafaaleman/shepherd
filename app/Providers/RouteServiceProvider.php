<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapLoveoneRoutes();
        $this->mapCareteamRoutes();
        $this->mapCarehubRoutes();
        $this->mapLockboxRoutes();
        $this->mapNotificationsRoutes();
        $this->mapMedlistRoutes();
        $this->mapMessagesRoutes();
        $this->mapDiscussionsRoutes();
        //
    }

    /**
     * Define the "loveone" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapLoveoneRoutes()
    {
        Route::prefix('loveone')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/loveone.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }


    /**
     * Define the "careteams" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCareteamRoutes()
    {
        Route::prefix('careteam')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/careteam.php'));
    }


    /**
     * Define the "careteams" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapNotificationsRoutes()
    {
        Route::prefix('notifications')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/notifications.php'));
    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

      /**
     * Define the "carehub" routes for the application.
     
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCarehubRoutes()
    {
        Route::prefix('carehub')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/carehub.php'));
    }
    /**
     * Define the "lockbox" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapLockboxRoutes()
    {
        Route::prefix('lockbox')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/lockbox.php'));
    }
     /**
     * Define the "medlist" routes for the application.
     
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapMedlistRoutes()
    {
        Route::prefix('medlist')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/medlist.php'));
    }
     /**
     * Define the "messages" routes for the application.
     
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapMessagesRoutes()
    {
        Route::prefix('messages')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/messages.php'));
    }
    /**
     * Define the "discussions" routes for the application.
     
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapDiscussionsRoutes()
    {
        Route::prefix('discussions')
            ->middleware('web', 'auth','two_factor')
            ->namespace($this->namespace)
            ->group(base_path('routes/discussions.php'));
    }
}
