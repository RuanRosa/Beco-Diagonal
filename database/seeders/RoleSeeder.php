<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = (object) [
            [
                "name" => "shopkeepers"
            ],
            [
                "name" => "common"
            ]
        ];

        foreach ($roles as $roleInsert) {
            $role = new Role();
            $role->type = $roleInsert['name'];
            $role->save();
        }
    }
}
