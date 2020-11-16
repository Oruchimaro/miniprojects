<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

This is a simple multi language website using laravel

## Steps to do stuff

    1) create a middleware for localization and add it to web middleware group

    $ php artisan make:middleware LocalizationMiddleware

        2)after adding the language files we need a route to set up
    a laguage for our app.we can add the functionality to web.php
    inside a closure like this :

    ```PHP
        Route::get('locale/{locale}', function($locale){
            Session::put('locale', $locale);

            return redirect()->back();
        });
    ```

    or we can add a controller for it .

        $ php artisan make:controller LanguageController --invokable

    3)Set up the anchor tags for language changing in views.

    ```HTML
       <a href="{{ url('locale/fr') }}"> fr </a>
    ```

    and in order to know wich language is being used

    ```HTML
        <html lang={{ app()->getLocale() }}>

4)set up a migration for a post table with difrrent language inputs
then add a record manually and use it for demonstration on how to use
database with diffrent locales.

    $ php artisan make:migration post

    add schema for diffrent languages

5)set up custom blade if/else statements in AppServericeProvider.php
then use it in views.
