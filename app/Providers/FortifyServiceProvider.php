<?php

namespace App\Providers;

use App\Models\Toko;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
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
    Fortify::createUsersUsing(CreateNewUser::class);
    Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    RateLimiter::for('login', function (Request $request) {
      $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
      return Limit::perMinute(5)->by($throttleKey);
    });

    RateLimiter::for('two-factor', function (Request $request) {
      return Limit::perMinute(5)->by($request->session()->get('login.id'));
    });

    //login
    Fortify::loginView(function () {
      return Auth::check() ? redirect(route('apps.profil.index')) : \Inertia\Inertia::render('Auth/Login', ['toko' => Toko::find(1)]);
    });

    //forgot
    Fortify::requestPasswordResetLinkView(function () {
      return Inertia::render('Auth/ForgotPassword');
    });

    //reset
    Fortify::resetPasswordView(function ($request) {
      return Inertia::render('Auth/ResetPassword', [
        'request' => $request,
      ]);
    });

    /**
     * logout
     */
    $this->app->singleton(\Laravel\Fortify\Contracts\LogoutResponse::class, \App\Http\Responses\LogoutResponse::class);
  }
}
