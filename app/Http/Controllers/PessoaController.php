<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PessoaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'apelido' => 'bail|required|max:32|unique:pessoas',
            'nome' => 'bail|required|max:100',
            'nascimento' => 'bail|required|date',
            'stack' => 'bail|nullable|array',
        ]);

        $uuid = Uuid::uuid4();

        $index = sprintf(
            '%s %s %s',
            Str::lower($request->input('apelido')),
            Str::lower($request->input('nome')),
            Str::lower(implode(' ', $request->input('stack') ?? []))
        );

        $pessoa = Pessoa::create([
            'id' => $uuid->toString(),
            'apelido' => $request->input('apelido'),
            'nome' => $request->input('nome'),
            'nascimento' => $request->input('nascimento'),
            'stack' => $request->input('stack'),
            'searchable' => $index,
        ]);

        return response()->json([], 201, ['Location' => "/pessoas/{$pessoa->id}"]);
    }

    public function show($id)
    {
        return response()->json(Pessoa::find($id));
    }

    public function search(Request $request)
    {
        $termo = $request->input('t');

        if (!$termo) {
            abort(400);
        }

        $pessoas = Pessoa::query()->where('searchable', 'ilike', '%' . Str::lower($termo) . '%')
            ->limit(50)
            ->get();

        return response()->json($pessoas);
    }

    public function count()
    {
        return Pessoa::count();
    }
}
