<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Crea 10 usuarios activos
        $users = User::factory(100)->create()->each(function ($user) {;
            $user->assignRole('User');
        });

        // Crea 3 usuarios eliminados (soft deleted)
        $deletedUsers = User::factory(30)->create()->each(function ($user) {;
            $user->assignRole('User');
        });;

        // Asigna deleted_at manualmente para simular soft delete
        foreach ($deletedUsers as $user) {
            $user->deleted_at = Carbon::now()->subDays(rand(1, 30));
            $user->save();
        }


        User::create([
            'name' => 'Cesar Valero Rodriguez',
            'email' => 'admin@admin.com',
            'password' => bcrypt('universal'),
        ])->assignRole('Admin');

        foreach (range(1, 1000) as $i) {
            Transaction::factory()->create([
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
