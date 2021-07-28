<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use MongoDB\Model\BSONDocument;

class CreateUser extends Command
{

    protected $signature = 'create:user';
    protected $description = 'Creates a new user for the dashboard';

    private string $firstname;
    private string $lastname;
    private string $password;
    private string $email;
    private int $company_unique_id;
    private string $company_transaction_token;


    public function __construct()
    {
        parent::__construct();
    }


    public function handle(): int
    {
        $confirmed = $this->askForInfo();

        if (!$confirmed) {
            $this->error('Creation aborted');
            $this->line("\n");
            return 1;
        }

        $this->createUser();
        $this->info("User $this->firstname $this->lastname created successfully.");
        return 0;
    }

    private function askForInfo(): bool
    {
        $company = $this->selectCompany();
        $this->company_unique_id = $company['unique_id'];
        $this->company_transaction_token = $company['company_transaction_token'];
        $company_name = $company['name'];

        $this->line("Selected company $company_name.");

        $email = $this->ask("User's email");
        while (!$this->validEmail($email)) {
            $this->error("Please insert a valid email");
            $email = $this->ask("User's email");
        }
        $this->email = Str::of($email)->lower();
        $this->firstname = Str::of($this->ask("User's firstname"))->title();
        $this->lastname = Str::of($this->ask("User's lastname"))->title();
        $this->password = $this->confirm("Use random default password", true) ? Str::random(12) : "password";

        $this->line("Company: $company_name");
        $this->line("User's name:  $this->firstname $this->lastname");
        $this->line("User's email: $this->email");
        $this->line("Default password: $this->password");

        return $this->confirm('Confirm creation?');
    }

    private function createUser(): void
    {
        /*generating api key*/
        $api_key = Str::random(32);

        /*mongo client*/
        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);

        /*inserting new apiClient in mongo*/
        $newApiClient = [
            "name" => $this->firstname,
            "last_name" => $this->lastname,
            "role" => 'user',
            "company_unique_id" => $this->company_unique_id,
            "enabled" => true,
            "id" => strtolower($this->firstname[0] . $this->lastname .'.'. env('SIMP2_ENV', 'dev')),
            "company_transaction_token" => $this->company_transaction_token,
            "api_key" => $api_key,
            "ip_list" => []
        ];

        $mongoClient->simp2->apiClients->insertOne(
            $newApiClient
        );

        /*then, insert into database*/
        $user = User::create([
            'name' => "$this->firstname $this->lastname",
            'email' => $this->email,
            'password' => $this->password,
            'enabled' => true,
            'api_key' => $api_key,
            'company_unique_id' => $this->company_unique_id
        ]);

        $user->assignRole('user');
    }

    private function selectCompany(): BSONDocument
    {
        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);
        $db = $mongoClient->simp2;
        $companies = $db->companies->find(['enabled' => true])->toArray();

        $company_names = array_map(fn($company) => $company['name'], $companies);

        $selected = $this->choice(
            'Select company',
            $company_names,
            0
        );

        $index = array_search($selected, $company_names);

        return $companies[$index];
    }

    private function validEmail($email): bool
    {
        try {
            Validator::make(compact('email'), [
                'email' => ['bail', 'required', 'string', 'email:rfc,dns', 'max:255'],
            ])->validate();
            return true;
        } catch (ValidationException $_) {
            return false;
        }
    }

}
