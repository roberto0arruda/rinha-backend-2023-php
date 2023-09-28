<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PessoaFactory extends Factory
{
    protected $model = Pessoa::class;

    public function definition(): array
    {
        return [
            'apelido' => strtolower($this->faker->unique()->firstName),
            'nome' => $this->faker->name,
            'nascimento' => $this->faker->date,
        ];
    }
}
