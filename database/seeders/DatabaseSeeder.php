<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for($i=1; $i<=20; $i++){
            $exists = DB::table("seats")->where("seat_no",$i)->exists();
            if($exists != 1){
                DB::table('seats')->insert([
                    "seat_no" => $i,
                    "created_at" => now()
                ]);
            }
        }
    }
}
