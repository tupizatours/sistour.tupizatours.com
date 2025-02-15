<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion\Cobro;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Configuracion\Link;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;
use App\Models\Propietario\Guia;
use App\Models\Propietario\Traductor;
use App\Models\Propietario\Cocinero;
use App\Models\Propietario\Chofer;
use App\Models\Propietario;
use App\Models\Servicio\Ticket;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;
use App\Models\Servicio\Accesorio;
use App\Models\Servicio\Turista;
use App\Models\Servicio\Vagoneta;
use App\Models\Servicio\Caballo;
use App\Models\Servicio\Bicicleta;
use App\Models\Servicio;
use App\Models\Tour\Categoria;
use App\Models\Tour\Vip;
use App\Models\Tour;
use App\Models\Reserva;
use App\Models\Venta\Pago;

class EstatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if($request->pagina == "cobros"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Cobro restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Cobro eliminado";
            }

            $cob = Cobro::find($id);
            $cob->estatus = $request->estatus;
            $cob->save();
            
            return redirect()->route('confcobros.index')->with($mensaje, $estados);
        }

        if($request->pagina == "links"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Link de pago restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Link de pago eliminado";
            }

            $lin = Link::find($id);
            $lin->estatus = $request->estatus;
            $lin->save();
            
            return redirect()->route('conflinks.index')->with($mensaje, $estados);
        }

        if($request->pagina == "onlines"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Pago online restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Pago online eliminado";
            }

            $onl = Online::find($id);
            $onl->estatus = $request->estatus;
            $onl->save();
            
            return redirect()->route('confonlines.index')->with($mensaje, $estados);
        }

        if($request->pagina == "qrs"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Qr restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Qr eliminado";
            }

            $qrs = Qr::find($id);
            $qrs->estatus = $request->estatus;
            $qrs->save();
            
            return redirect()->route('confqrs.index')->with($mensaje, $estados);
        }

        if($request->pagina == "guias"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Guia restaurada";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Guia eliminada";
            }

            $gui = Guia::find($id);
            $gui->estatus = $request->estatus;
            $gui->save();

            return redirect()->route('proguias.index')->with($mensaje, $estados);
        }

        if($request->pagina == "traductores"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Traductor restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Traductor eliminado";
            }

            $tra = Traductor::find($id);
            $tra->estatus = $request->estatus;
            $tra->save();
            
            return redirect()->route('protraductores.index')->with($mensaje, $estados);
        }

        if($request->pagina == "cocineros"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Cocinero restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Cocinero eliminado";
            }

            $coc = Cocinero::find($id);
            $coc->estatus = $request->estatus;
            $coc->save();
            
            return redirect()->route('prococineros.index')->with($mensaje, $estados);
        }

        if($request->pagina == "choferes"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Chofer restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Chofer eliminado";
            }

            $cho = Chofer::find($id);
            $cho->estatus = $request->estatus;
            $cho->save();
            
            return redirect()->route('prochoferes.index')->with($mensaje, $estados);
        }

        if($request->pagina == "propietarios"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Propietario restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Propietario eliminado";
            }

            $pro = Propietario::find($id);
            $pro->estatus = $request->estatus;
            $pro->save();
            
            return redirect()->route('propietarios.index')->with($mensaje, $estados);
        }

        if($request->pagina == "tickets"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Ticket restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Ticket eliminado";
            }

            $tic = Ticket::find($id);
            $tic->estatus = $request->estatus;
            $tic->save();
            
            return redirect()->route('servtickets.index')->with($mensaje, $estados);
        }

        if($request->pagina == "hoteles"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Hotel restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Hotel eliminado";
            }

            $hot = Hotel::find($id);
            $hot->estatus = $request->estatus;
            $hot->save();
            
            return redirect()->route('servhoteles.index')->with($mensaje, $estados);
        }

        if($request->pagina == "habitaciones"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Habitación restaurada";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Habitación eliminada";
            }

            $hab = Habitacion::find($id);
            $hab->estatus = $request->estatus;
            $hab->save();
            
            return redirect()->route('servhabitaciones.index')->with($mensaje, $estados);
        }

        if($request->pagina == "accesorios"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Accesorio restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Accesorio eliminado";
            }

            $acc = Accesorio::find($id);
            $acc->estatus = $request->estatus;
            $acc->save();
            
            return redirect()->route('servaccesorios.index')->with($mensaje, $estados);
        }

        if($request->pagina == "turistas"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Turista restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Turista eliminado";
            }

            $tur = Turista::find($id);
            $tur->estatus = $request->estatus;
            $tur->save();
            
            return redirect()->route('servturistas.index')->with($mensaje, $estados);
        }

        if($request->pagina == "vagonetas"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Vagoneta restaurada";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Vagoneta eliminada";
            }

            $vag = Vagoneta::find($id);
            $vag->estatus = $request->estatus;
            $vag->save();
            
            return redirect()->route('servvagonetas.index')->with($mensaje, $estados);
        }

        if($request->pagina == "caballos"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Caballo restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Caballo eliminado";
            }

            $cab = Caballo::find($id);
            $cab->estatus = $request->estatus;
            $cab->save();
            
            return redirect()->route('servcaballos.index')->with($mensaje, $estados);
        }

        if($request->pagina == "bicicletas"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Bicicleta restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Bicicleta eliminado";
            }

            $bic = Bicicleta::find($id);
            $bic->estatus = $request->estatus;
            $bic->save();
            
            return redirect()->route('servbicicletas.index')->with($mensaje, $estados);
        }

        if($request->pagina == "servicios"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Servicio restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Servicio eliminado";
            }

            $ser = Servicio::find($id);
            $ser->estatus = $request->estatus;
            $ser->save();
            
            return redirect()->route('servicios.index')->with($mensaje, $estados);
        }

        if($request->pagina == "tour_categorias"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Categoria restaurada";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Categoria eliminada";
            }

            $cat = Categoria::find($id);
            $cat->estatus = $request->estatus;
            $cat->save();
            
            return redirect()->route('tourcategorias.index')->with($mensaje, $estados);
        }

        if($request->pagina == "tour_vip"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Tour VIP restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Tour VIP eliminado";
            }

            $vip = VIP::find($id);
            $vip->estatus = $request->estatus;
            $vip->save();
            
            return redirect()->route('touvips.index')->with($mensaje, $estados);
        }

        if($request->pagina == "tours"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Tour restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Tour eliminado";
            }

            $tou = Tour::find($id);
            $tou->estatus = $request->estatus;
            $tou->save();
            
            return redirect()->route('tours.index')->with($mensaje, $estados);
        }

        if($request->pagina == "solicitudes"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Solicitud restaurada";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Solicitud eliminada";
            }

            $tou = Reserva::find($id);
            $tou->estatus = 2;
            $tou->save();
            
            return back();
        }

        if($request->pagina == "pagos"){
            if($request->estatus == 1){
                $mensaje = "success";
                $estados = "Pago restaurado";
            }elseif($request->estatus == 2){
                $mensaje = "danger";
                $estados = "Pago eliminado";
            }

            $tou = Pago::find($id);
            $tou->estatus = 2;
            $tou->save();
            
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
