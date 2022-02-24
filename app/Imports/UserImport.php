<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow; // if the heading has row names

class UserImport implements ToModel //, WithHeadingRow
{
    /**
     * Heading Row Docs : https://docs.laravel-excel.com/3.1/imports/heading-row.html
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0] == 'نام')  // skip if the row is the first one (heading row in persian cant be used)
        {
            return;
        }

        return new User([
            'name' => $row[0],
            'email' => $row[1],
            'password' => Hash::make($row[3])
        ]);
    }
}
