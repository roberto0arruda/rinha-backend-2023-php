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
    public function testExample(): void
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testCriarPessoa(): void
    {
        $pessoa = Pessoa::factory()->make();

        $response = $this->json('POST', '/pessoas', $pessoa->toArray());

        $response->seeHeader('Location');
        $this->assertResponseStatus(201);
        $this->seeInDatabase('pessoas', ['apelido' => $pessoa->apelido]);
    }

    public function testNaoCriarPessoaComApelidoJaExistente(): void
    {
        $pessoa = Pessoa::factory()->create([
            'id' => Ramsey\Uuid\Uuid::uuid4()->toString(),
            'apelido' => 'roberto',
            'searchable' => 'fake',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testNaoCriarPessoaComApelidoMaiorQue32Caracteres(): void
    {
        $pessoa = Pessoa::factory()->make([
            'apelido' => 'robertorobertorobertorobertoroberto',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testNaoCriarPessoaComNomeMaiorQue100Caracteres(): void
    {
        $pessoa = Pessoa::factory()->make([
            'nome' => 'Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda Roberto Arruda ',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray())
            ->assertResponseStatus(422);
    }

    public function testDetalhesDeUmaPessoaPeloId(): void
    {
        $pessoa = Pessoa::factory()->create([
            'id' => Ramsey\Uuid\Uuid::uuid4()->toString(),
            'apelido' => 'roberto',
            'searchable' => 'fake',
        ]);

        $this->get("/pessoas/{$pessoa->id}")->seeJson($pessoa->toArray());
    }

    public function testBuscaDePessoasPorTermo(): void
    {
        $pessoa = Pessoa::factory()->make([
            'apelido' => 'roberto',
            'nome' => 'Roberto Arruda',
        ]);

        $this->json('POST', '/pessoas', $pessoa->toArray());
        $this->seeInDatabase('pessoas', ['apelido' => $pessoa->apelido]);

        $response = $this->get("/pessoas?t={$pessoa->apelido}");

        $response->seeJsonContains(['apelido' => $pessoa->apelido])
            ->assertResponseStatus(200);

        $response = $this->get("/pessoas?t=joao");

        $response->seeJsonEquals([])
            ->assertResponseStatus(200);

        $response = $this->get("/pessoas?t=");

        $response->assertResponseStatus(400);
    }

    public function testContagemDePessoas(): void
    {
        Pessoa::factory()->count(10)->create([
            'stack' => null,
            'searchable' => 'fake',
        ]);

        $this->get("/contagem-pessoas");

        $this->assertEquals(10, $this->response->getContent());
    }
}
