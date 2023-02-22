<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ImovelRequest;

use App\Models\Cidade;
use App\Models\Tipo;
use App\Models\Finalidade;
use App\Models\Proximidade;
use App\Models\Imovel;
class ImovelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {

        $imoveis = Imovel::join('cidades', 'cidades.id', '=', 'imoveis.cidade_id')
            ->join('enderecos', 'enderecos.imovel_id', '=', 'imoveis.id')
            ->orderBy('cidades.nome', 'asc')
            ->orderBy('enderecos.bairro', 'asc')
            ->orderBy('titulo', 'asc');


        $cidade_id = $request->cidade_id;
        $titulo = $request->titulo;

        //  $imoveis = Imovel::all();

        //Filtro de cidade
        if($cidade_id){
            $imoveis->where('cidades.id', $cidade_id);
        }

        //Filtro de titulo
        if($titulo){
            $imoveis->where('titulo', 'like', "%$titulo%");
        }

        //Pegando os dados retornados a partir da execução da query
        $imoveis = $imoveis->paginate(env('PAGINACAO'))->withQueryString();

        $cidades = Cidade::orderBy('nome')->get();

        return response(view('admin.imoveis.index', compact('imoveis', 'cidades', 'titulo', 'cidade_id')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $cidades = Cidade::all();
        $tipos = Tipo::all();
        $finalidades = Finalidade::all();
        $proximidades = Proximidade::all();

        $action = route('admin.imoveis.store');
        return response(view('admin.imoveis.form', compact('action', 'cidades', 'tipos', 'finalidades', 'proximidades')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImovelRequest $request): RedirectResponse
    {
        \DB::beginTransaction();

        $imovel = Imovel::create($request->all());
        $imovel->endereco()->create($request->all());

        if($request->has('proximidades')){

            $imovel->proximidades()->sync($request->proximidades);
        }

        \DB::Commit();
        $request->session()->flash('sucesso', "Imovel incluido com sucesso!");
        return redirect()->route('admin.imoveis.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $imovel = Imovel::with(['cidade', 'endereco', 'finalidade', 'tipo', 'proximidades'])->find($id);

        return response(view('admin.imoveis.show', compact('imovel')));

    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id): Response
    public function edit(string $id): Response
    {
        $imovel = Imovel::with(['cidade', 'endereco', 'finalidade', 'tipo', 'proximidades'])->find($id);

        $cidades = Cidade::all();
        $tipos = Tipo::all();
        $finalidades = Finalidade::all();
        $proximidades = Proximidade::all();

        $action = route('admin.imoveis.update', $imovel->id);
        return response(view('admin.imoveis.form', compact('imovel', 'action', 'cidades', 'tipos', 'finalidades', 'proximidades')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImovelRequest $request, string $id): RedirectResponse
    {
        $imovel = Imovel::find($id);

        \DB::beginTransaction();

        $imovel->update($request->all());
        $imovel->endereco->update($request->all());

        if($request->has('proximidades')){
            $imovel->proximidades()->sync($request->proximidades);
        }

        \DB::Commit();

        $request->session()->flash('sucesso', "Imovel atualizado com sucesso!");
        return redirect()->route('admin.imoveis.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $imovel = Imovel::find($id);

        \DB::beginTransaction();

        //Remover o endereço
        $imovel->endereco->delete();

        //Remover o imovel
        $imovel->delete();

        \DB::Commit();

        $request->session()->flash('sucesso', "Imovel excluído com sucesso!");
        return redirect()->route('admin.imoveis.index');
    }
}
