<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/2/28
 * Time: 9:12
 */

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot() {
        // 基于类的view composer
        View::composer(
            '*', 'App\Http\ViewComposers\AdminComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        //
    }
}