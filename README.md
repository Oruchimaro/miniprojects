<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This

This is a multi authentication sample code, we are using laravel/ui package (no Fortify/Jetstream).
This projects code can be used in any project using laravel/ui package.

-   Multiple types of user Ex. Admin, Users
-   Authenticating using diffrent guards
-   Having multiple tables for each type of user

## Reacreation Steps

### Install laravel/ui

As Documentation Suggests.

### Setting up user/admin

Run this command to make a admins table migration :

`$ php artisan make:migration create_admins_table --create=admins`

And Fill it like the users table.Then Create an admin model.

For Creaating admin model the simplest way is to duplicate User model and change the name and fillables.

### Configure new provider for guard

In config/auth.php file add a new provider for admins in providers array.
then use that array and define a guard for admin, then add a password reset configuration for admins if needed.

Now you can define the default guard for Admin model in it.

### Add View and Controller For Admin

Copy HomeController to UserController and AdminController, edit some code in them.
Then add the routes for this to web.php file
Finally for testing goto /admin to see the result (you will be able to see admin dashboard).

Now if you add the guard to AdminController it will be protected against users.

### Configure middlewares

For correct redirects after authentication or correct login for specefic gurad
we can configure Authenticate and RedirectIfAuthenticated middlewares.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
