<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Imovel;
use App\Models\Foto;
use App\Models\Endereco;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\FotoRequest;
class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idImovel): Response
    {

        $imovel = Imovel::find($idImovel);

        $fotos  = Foto::where('imovel_id', $idImovel)->get();

        return response(view('admin.imoveis.fotos.index', compact('imovel', 'fotos')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idImovel): Response
    {
        $imovel = Imovel::find($idImovel);
        return response(view('admin.imoveis.fotos.form', compact('imovel')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FotoRequest $request, $idImovel): RedirectResponse
    {
        //checar se veio a imagem na requisição
        if($request->hasFile('foto')){

            //checar se não houve erro no upload da imagem
            if($request->foto->isValid()){

                //Pegando o caminho e o nome do arquivo pra salvar no disco
                $fotoURL = $request->foto->hashName("imoveis/$idImovel");

                //Redimensionar a image
                $imagem = Image::make($request->foto)->fit(env('FOTO_LARGURA'), env('FOTO_ALTURA'));

                //Salvar no disco
                Storage::disk('public')->put($fotoURL, $imagem->encode());

                //Armazenando os caminho da foto no BD
                $foto = new Foto();
                $foto->url = $fotoURL;
                $foto->imovel_id = $idImovel;
                $foto->save();
            }
        }

        $request->session()->flash('sucesso', 'Foto incluída com sucesso!');
        return redirect()->route('admin.imoveis.fotos.index', $idImovel);
    }

    public function destroy(Request $request, $idImovel, $idFoto): RedirectResponse
    {
        $foto = Foto::find($idFoto);

        //Apagar a imagem no disco
        Storage::disk('public')->delete($foto->url);

        //Apagando o registro na BD
        $foto->delete();

        $request->session()->flash('sucesso', 'Foto excluída com sucesso!');
        return redirect()->route('admin.imoveis.fotos.index', $idImovel);
    }
}
