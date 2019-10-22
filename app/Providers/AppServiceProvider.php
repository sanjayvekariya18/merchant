<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Activity;
use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Schema::defaultStringLength(191);*/
        view()->composer('layouts.default', function($view)
        {
            $merchantId = session()->get('merchantId');
            if($merchantId == 0){
                
                $totalNewActivity = Activity::where('status','=',1)->count();

                $hase_activities = DB::table('activities')
                                ->orderBy('date_added','desc')
                                ->limit(5)->get();    
            }else{

                $totalNewActivity = Activity::
                                    where('merchant_id',$merchantId)
                                    ->where('status','=',1)->count();

                $hase_activities = DB::table('activities')
                                ->orderBy('date_added','desc')
                                ->where('merchant_id',$merchantId)
                                ->limit(5)->get();    
            }

            $view->with('hase_activities', $hase_activities)
                 ->with('totalActivity',$totalNewActivity);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
