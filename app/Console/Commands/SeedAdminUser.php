<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class SeedAdminUser extends Command
{
    protected $signature = 'seed:admin';
    protected $description = 'Creates the base admin user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);
        $db = $mongoClient->simp2;
        $company = $db->companies->find(['unique_id' => 0])->toArray()[0] ?? null;

        if (!$company) {
            $this->error('The company 0 does not exist.');
            return 1;
        }

        if ($company->name !== 'argon') {

            $this->error('The company 0 is not Argon.');
            return 1;
        }
        try {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@simp2.com',
                'password' => Hash::make('admin'),
                'enabled' => true,
                'api_key' => 'admin_argon_api_key',
                'company_unique_id' => 0,
            ]);
            $user->assignRole('admin');

        } catch (QueryException $e) {
            $this->error("Error inserting user. {$e->getMessage()}");
            return 1;
        }


        $this->info('Admin user created successfully');

        return 0;
    }
}
