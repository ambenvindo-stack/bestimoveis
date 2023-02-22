<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\CidadeRequest;
use App\Models\Cidade;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $subtitulo = 'Lista de Cidades';
        $cidades = Cidade::orderBy('nome', 'asc')->get();
        return response(view('admin.cidades.index', compact('subtitulo', 'cidades')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $action = route('admin.cidades.store');
        return response(view('admin.cidades.form', compact('action')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Cidade::create($request->all());
        $request->session()->flash('sucesso', "Cidade $request->nome incluida com sucesso!");
        return redirect()->route('admin.cidades.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $cidade = Cidade::find($id);
        $action = route('admin.cidades.update', $cidade->id);
        return response(view('admin.cidades.form', compact('cidade', 'action')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CidadeRequest $request, string $id): RedirectResponse
    {
        $cidade = Cidade::find($id);
        $cidade->update($request->all());

        // $request->session()->$request->flash('sucesso', "Cidade $request->nome alterada com sucesso!");
        return redirect()->route('admin.cidades.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        Cidade::destroy($id);
        $request->session()->flash('sucesso', 'Cidade excluida com sucesso!');
        return redirect()->route('admin.cidades.index');
    }
}
