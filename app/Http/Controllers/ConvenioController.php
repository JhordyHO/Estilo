<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\CE_Convenio;
use App\CE_Estado;
use App\CE_tipo;
use App\archivo;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ConvenioRequest;
use App\Http\Requests;
use DB;

class ConvenioController extends Controller
{


    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request)
        {
            $tipo=DB::table('tipo');
            $convenio=DB::table('convenio as con')->join('estado as e','con.estado_idestado','=','e.idestado')
            ->join('tipo as t','con.tipo_idtipo','=','t.idtipo')
            ->join('tipoconvenio as tc','con.tipoconvenio_idtipoconvenio','=','tc.idtipoconvenio')
            ->join('ambito as amb','con.ambito_idambito','=','amb.idambito')
            ->join('pais as p','con.pais_idpais','=','p.idpais')
            

            ->select('con.idconvenio','con.titulo','con.codigo','con.resolucion','con.objetivo','con.duracion','con.categoria','con.fecha_ini','con.fecha_fin','con.imagen','e.idestado','e.nombre as nomestado','t.nombre as nomtipo','tc.nombre as tcnom','amb.nombre as ambnom','p.nombre as nompais')


            ->where('e.idestado','=','2')
                ->orderBy('con.idconvenio','ASC')->paginate();

                return view('convenios.index',compact('convenio','tipo'));
        }
    }

    /**

     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $ti= CE_tipo::orderBy('nombre','DES')->get();
        $tc=DB::table('tipoconvenio')->get();
        $amb=DB::table('ambito')->get();
        $pa=DB::table('pais')->get();
        $es=DB::table('estado')->get();
        $ar=DB::table('archivo')->get();
        return view('convenios.create',compact('ti','tc','amb','pa','es','ar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /* ALMACENAR EL OBJETO DEL MODELO CONVENIOS*/
    public function store(ConvenioRequest $request)
    {
        $convenio=new CE_Convenio;
        $convenio->idconvenio=$request->idconvenio;
        $convenio->titulo=$request->titulo;
        $convenio->codigo=$request->codigo;
        $convenio->resolucion=$request->resolucion;
        $convenio->objetivo=$request->objetivo;
        $convenio->duracion=$request->duracion;
        $convenio->categoria=$request->categoria;
        $convenio->fecha_ini=$request->fecha_ini;
        $convenio->fecha_fin=$request->fecha_fin;
        $convenio->tipo_idtipo=$request->idtipo;
        $convenio->tipoconvenio_idtipoconvenio=$request->idtipoconvenio;
        $convenio->ambito_idambito=$request->idambito;
        $convenio->pais_idpais=$request->idpais;
        $convenio->estado_idestado=$request->idestado;
        if(Input::hasFile('imagen')){
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/convenios',$file->getClientOriginalName());
            $convenio->imagen=$file->getClientOriginalName();
        }
        $convenio->save();



        return Redirect::to('convenios');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /* VER DETALLES DE CONVENIO*/
    public function show($id)
    {
        $convenio=CE_Convenio::findOrFail($id);

        return view('convenios.show',compact('convenio'));
        /*return view("convenios.show",["convenio"=>CE_Convenio::findOrFail($id)]);*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $convenio=CE_Convenio::findOrFail($id);
        $Ti=DB::table('tipo')->get();
        $tc=DB::table('tipoconvenio')->get();
        $amb=DB::table('ambito')->get();
        $pa=DB::table('pais')->get();
        $es=DB::table('estado')->get();
        $fi=DB::table('ficha')->get();
        return view('convenios.edit', compact('convenio','Ti','tc','amb','pa','es','fi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConvenioRequest $request, $id)
    {

        $convenio = CE_Convenio::findOrFail($id);
       
        $convenio->titulo=$request->titulo;
        $convenio->codigo=$request->codigo;
        $convenio->resolucion=$request->resolucion;
        $convenio->objetivo=$request->objetivo;
        $convenio->duracion=$request->duracion;
        $convenio->categoria=$request->categoria;
        $convenio->fecha_ini=$request->fecha_ini;
        $convenio->fecha_fin=$request->fecha_fin;
        $convenio->tipo_idtipo=$request->idtipo;
        $convenio->tipoconvenio_idtipoconvenio=$request->idtipoconvenio;
        $convenio->ambito_idambito=$request->idambito;
        $convenio->pais_idpais=$request->idpais;
        $convenio->estado_idestado=$request->idestado;
        $convenio->update();

        return Redirect::to('convenios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*$convenio=CE_Convenio::findOrFail($id);
        $convenio->estado='Inactivo';
        $convenio->update();
        return Redirect::to('convenio');*/
    }
    public function Eliminar($id)
    {
        $convenio=CE_Convenio::findOrFail($id);
        $convenio->estado_idestado='3';
        $convenio->update();
        return Redirect::to('convenios');
    }
}