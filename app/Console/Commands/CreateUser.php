<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

#[Signature('app:create-user {--name= : Nome do usuário} {--email= : E-mail usado no login}')]
#[Description('Cria um usuário com acesso ao sistema')]
class CreateUser extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $data = [
            'name' => $this->option('name') ?: $this->ask('Nome'),
            'email' => $this->option('email') ?: $this->ask('E-mail'),
            'password' => $this->secret('Senha'),
        ];
        $data['password_confirmation'] = $this->secret('Confirme a senha');

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], attributes: ['name' => 'nome', 'email' => 'e-mail', 'password' => 'senha']);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create($validator->safe()->only(['name', 'email', 'password']));

        $this->components->info("Usuário {$user->email} criado com sucesso.");

        return self::SUCCESS;
    }
}
