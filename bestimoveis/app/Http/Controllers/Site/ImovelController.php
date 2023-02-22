<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Cidade;
use App\Models\Imovel;

class ImovelController extends Controller
{
    public function index($idCidade)
    {

        $cidade = Cidade::find($idCidade);

        $imoveis = Imovel::with(['finalidade', 'fotos'])
            ->where('cidade_id', $idCidade);
            // ->paginate(env('PAGINACAO_SITE'));

        //Pegando os dados retornados a partir da execução da query
        $imoveis = $imoveis->paginate(2)->withQueryString();



        return view('site.cidades.imoveis.index', compact('cidade', 'imoveis'));
    }

    // public function show(string $id): Response
    public function show($idCidade, $idImovel)
    {
        $imovel = Imovel::find($idImovel);
        return view('site.cidades.imoveis.show', compact('imovel'));
    }

}
