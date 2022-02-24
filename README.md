# Laravel 8 Import Export Excel & CSV File Tutorial

**Last updated on: January 31, 2022 by Digamber**

<p>This tutorial helps you understand how to comfortably import export excel or CSV file to Database with Laravel 8.

If you want to create easy import and export, excel file functionality, this laravel maatwebsite/excel tutorial is best for you.

At the end of this tutorial, you will be able to download or import excel & CSV file directly from the database in laravel application, respectively.

Generically, we will follow all the imperative that are needed to build a general laravel application. We will go from point a to b, something like creating or importing data to xls or CSV.</p>

<hr>

## Install Excel (maatwebsite) Pacakage


<p> Commonly, to complete our foundational work, we require a third-party package. Ideally, we are talking about the [Laravel-Excel](https://laravel-excel.com/) plugin by [Maatwebsite](https://github.com/Maatwebsite/Laravel-Excel). It provides the robust mechanism to deal with Excel exports and imports in Laravel. In response, It has got the immense love of artisan’s on GitHub.</p>

Run command to install the package.
```
composer require maatwebsite/excel
```

<hr>

## Register Plugin’s Service in Providers & Aliases

<p>You can have the following code placed inside the config/app.php file.</p>

```
'providers' => [
  .......
  .......
  .......
  Maatwebsite\Excel\ExcelServiceProvider::class,
 
 ],  
'aliases' => [ 
  .......
  .......
  .......
  'Excel' => Maatwebsite\Excel\Facades\Excel::class,
], 
```

<p>Execute the vendor, publish command, and publish the config.</p>

```
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

<p>This will formulate a new config file as config/excel.php.</p>


<hr>

## Generate Fake Records, Migrate Table


<p>Often, this step consists of two sub-steps. In the first one, we migrate the User table. Laravel comes with the User model and migration with default values, and we can use it and migrate to the database.</p>

```
php artisan migrate
```
<p>Once the migration is completed, then execute the command to generate the fake records.</p>

```
php artisan tinker
User::factory()->count(50)->create();
exit
```

<p>Eventually, the above command has created the dummy data in our database.</p>




<hr>

## Construct Route


<p>
Usually, routing in laravel is the foundational mechanism that interprets the URI endpoint and conjugates it into parameters to shape which module or controller is associated.

Define 3 routes in **routes/web.php** that handle the import and export for Excel and CSV files.
</p>

```
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('file-import-export', [UserController::class, 'fileImportExport']);
Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');
Route::get('file-export', [UserController::class, 'fileExport'])->name('file-export');
```



<hr>

## Make Import Class


<p>
The maatwebsite module offers an imperative method to develop an import class. Obviously, it should be used along with the laravel controller, and i believe you already know this has been the best way to generate a new import class.

Execute the below command:
</p>

```
php artisan make:import UsersImport --model=User
```

<p> Place the following code inside the **app/Imports/UsersImport.php** file. </p>

```
<?php
namespace App\Imports;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make($row[2])
        ]);
    }
}
```



<hr>

## Construct Export Class


<p>The maatwebsite module provides an essential method to construct an export class. Preferably, it needs to get along with the laravel controller, and i know it doesn’t sound vague.

Run the following command in your terminal:
</p>

```
php artisan make:export UsersExport --model=User
```

<p> Here is the final code that is conjugated in **app/Exports/UsersExport.php** .</p>

```
<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
}

```



<hr>

## Create and Prepare Controller

<p>
Now, we have reached an essential step in this tutorial. We will evoke this step by creating a controller. Altogether all the logic goes in here to manage the import and export file such as Excel and CSV.

Invoke the command to generate UserController.
</p>

```
php artisan make:controller UserController
```

<p>Place the following code in the **app/Http/Controllers/UserController.php file.</p>

```
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
class UserController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileImportExport()
    {
       return view('file-import');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileImport(Request $request) 
    {
        Excel::import(new UsersImport, $request->file('file')->store('temp'));
        return back();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileExport() 
    {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
    }    
}
```

<hr>

## Write Down Blade View


<p>Ultimately, we have reached the last step. In general, here we need to formulate the view for handling importing and exporting through the frontend.

Create a **resources/views/file-import.blade.php** file to set up the view. Place the following code inside the blade view file: </p>

```
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Export Excel & CSV to Database in Laravel 7</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5 text-center">
        <h2 class="mb-4">
            Laravel 7 Import and Export CSV & Excel to Database Example
        </h2>
        <form action="{{ route('file-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <button class="btn btn-primary">Import data</button>
            <a class="btn btn-success" href="{{ route('file-export') }}">Export data</a>
        </form>
    </div>
</body>
</html>
```

<p>We have followed every step, respectively, and consecutively, now its time to run the app to test what we build so far.</p>

```
php artisan serve
```

<p>Here is the endpoint that you can finally test:</p>

```
http://localhost:8000/file-import-export
```


<hr>

## Summary

<p> So this was it, we have completed the tutorial. In this tutorial, we threw light on importing-exporting and downloading the Excel & CSV file from the database with the **maatwebsite/excel** composer package.

You can also check the [documentation](https://docs.laravel-excel.com/3.1/getting-started/) of the plugin that we assimilated in this tutorial.

You can download the full code of this tutorial from [GitHub](https://github.com/SinghDigamber/LaravelExcelCsv).

I hope you must have liked this tutorial, we covered the basic functionality but good for getting started.
</p>




<hr>
Tutorial [LINK](https://www.positronx.io/laravel-import-expert-excel-and-csv-file-tutorial-with-example/)
