<?php

namespace App\Providers;

use Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use App\Helpers\LAHelper;

class LAProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
		if(LAHelper::laravel_ver() == 5.3) {
			
			// Call to Entrust::hasRole
			Blade::directive('role', function($expression) {
				return "<?php if (\\Entrust::hasRole({$expression})) : ?>";
			});
			
			// Call to Entrust::can
			Blade::directive('permission', function($expression) {
				return "<?php if (\\Entrust::can({$expression})) : ?>";
			});
			
			// Call to Entrust::ability
			Blade::directive('ability', function($expression) {
				return "<?php if (\\Entrust::ability({$expression})) : ?>";
			});
		}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/../../routes/web.php';

		// For LAEditor
		if(file_exists(__DIR__.'/../../laeditor')) {
			include __DIR__.'/../../laeditor/src/routes.php';
		}
        
        
        /*
        |--------------------------------------------------------------------------
        | Register the Alias
        |--------------------------------------------------------------------------
        */

        
        $loader = AliasLoader::getInstance();

        // For LaraAdmin Form Helper
        $loader->alias('LAFormMaker', \App\Helpers\LAFormMaker::class);
        
                
        /*
        |--------------------------------------------------------------------------
        | Register the Controllers
        |--------------------------------------------------------------------------
        */
        
        $this->app->make('App\Http\Controllers\ModuleController');
        $this->app->make('App\Http\Controllers\FieldController');
        $this->app->make('App\Http\Controllers\MenuController');
		
		// For LAEditor
		if(file_exists(__DIR__.'/../../laeditor')) {
			$this->app->make('Dwij\Laeditor\Controllers\CodeEditorController');
		}

		/*
        |--------------------------------------------------------------------------
        | Blade Directives
        |--------------------------------------------------------------------------
        */
        
        // LAForm Input Maker
        Blade::directive('la_input', function($expression) {
			if(LAHelper::laravel_ver() == 5.3) {
				$expression = "(".$expression.")";
			}
            return "<?php echo LAFormMaker::input$expression; ?>";
        });
        
        // LAForm Form Maker
        Blade::directive('la_form', function($expression) {
			if(LAHelper::laravel_ver() == 5.3) {
				$expression = "(".$expression.")";
			}
            return "<?php echo LAFormMaker::form$expression; ?>";
        });
        
        // LAForm Maker - Display Values
        Blade::directive('la_display', function($expression) {
			if(LAHelper::laravel_ver() == 5.3) {
				$expression = "(".$expression.")";
			}
            return "<?php echo LAFormMaker::display$expression; ?>";
        });
        
        // LAForm Maker - Check Whether User has Module Access
        Blade::directive('la_access', function($expression) {
			if(LAHelper::laravel_ver() == 5.3) {
				$expression = "(".$expression.")";
			}
            return "<?php if(LAFormMaker::la_access$expression) { ?>";
        });
        Blade::directive('endla_access', function($expression) {
            return "<?php } ?>";
        });
        
        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

		$commands = [
            /*App\Commands\Migration::class,
            App\Commands\Crud::class,*/
        ];
        
		// For LAEditor
		if(file_exists(__DIR__.'/../../laeditor')) {
			$commands[] = \Dwij\Laeditor\Commands\LAEditor::class;
		}

        $this->commands($commands);
    }
}
