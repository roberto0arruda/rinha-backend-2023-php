<?php

use App\Models\Pessoa;
use Laravel\Lumen\Testing\DatabaseMigrations;

class PessoaTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testCriarPessoa()
    {
        $pessoa = Pessoa::factory()->make();

        $this->json('POST', '/pessoas', $pessoa->toArray());

        $this->assertResponseStatus(201);
    }

    public function testNaoCriarPessoaComApelidoJaExistente()
    {
        $pessoa = Pessoa::factory()->create([
            'id' => Ramsey\Uuid\Uuid::uuid4()->toString(),
            'apelido' => 'roberto',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testNaoCriarPessoaComApelidoMaiorQue32Caracteres()
    {
        $pessoa = Pessoa::factory()->make([
            'apelido' => 'robertorobertorobertorobertoroberto',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testNaoCriarPessoaComNomeMaiorQue100Caracteres()
    {
        $pessoa = Pessoa::factory()->make([
            'nome' => 'Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda ',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testDetalhesDeUmaPessoaPeloId()
    {
        $pessoa = Pessoa::factory()->create([
            'id' => Ramsey\Uuid\Uuid::uuid4()->toString(),
            'apelido' => 'roberto',
        ]);

        $this->get("/pessoas/{$pessoa->id}")->seeJson($pessoa->toArray());
    }
}
