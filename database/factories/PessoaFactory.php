<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class PessoaFactory extends Factory
{
    protected $model = Pessoa::class;

    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'apelido' => strtolower($this->faker->unique()->firstName),
            'nome' => $this->faker->name,
            'nascimento' => $this->faker->date,
        ];
    }
}
