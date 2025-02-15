<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', 'App\Http\Controllers\WelcomeController@welcome')->name('welcome');
    Route::get('/terms', 'App\Http\Controllers\TermsController@terms')->name('terms');
});

/*Tienda*/
Route::resource('archivos', App\Http\Controllers\ArchivoController::class)->names('archivos');
Route::resource('tienda', App\Http\Controllers\TiendaController::class)->names('tienda');

Route::resource('reservas', App\Http\Controllers\ReservaController::class)->names('reservas');
Route::get('ventas/resclis/user/{id}', 'App\Http\Controllers\Venta\RescliController@user')->name('venresclisuser');

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {
    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {
    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    // Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {
    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', [
        'as'   => 'public.home',
        'uses' => 'App\Http\Controllers\UserController@index',
        'name' => 'home',
    ]);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);

    /*Ventas*/
    Route::get('venta/eliminados', 'App\Http\Controllers\Venta\SolicitudController@eliminados')->name('eliminados');
    Route::resource('ventas/solicitudes', App\Http\Controllers\Venta\SolicitudController::class)->names('vensolicitudes');
    Route::resource('ventas/pagos', App\Http\Controllers\Venta\PagoController::class)->names('venpagos');

    Route::resource('ventas/reservas', App\Http\Controllers\Venta\ReservaController::class)->names('venreservas');
    Route::resource('ventas/resclis', App\Http\Controllers\Venta\RescliController::class)->names('venresclis');
    Route::resource('ventas/vips', App\Http\Controllers\Venta\VipController::class)->names('venvips');
    
    Route::get('/despachos/vagonetas/{propietario_id}', 'App\Http\Controllers\Despacho\GestionController@obtenerVagonetas')->name('obtenerVagonetas');
    Route::get('/despachos/caballos/{propietario_id}', 'App\Http\Controllers\Despacho\GestionController@obtenerCaballos')->name('obtenerCaballos');
    Route::get('/despachos/bicicletas/{propietario_id}', 'App\Http\Controllers\Despacho\GestionController@obtenerBicicletas')->name('obtenerBicicletas');
    Route::post('/despachos/gestiones/anticipos', 'App\Http\Controllers\Despacho\GestionController@gesanticipos')->name('gesanticipos');

    Route::resource('despachos/gestiones', App\Http\Controllers\Despacho\GestionController::class)->names('desges');
    Route::resource('despachos/transitos', App\Http\Controllers\Despacho\TransitoController::class)->names('destra');
    Route::resource('despachos/finalizados', App\Http\Controllers\Despacho\FinalizadoController::class)->names('desfin');
    
    /*Propietarios CRUD*/
    Route::get('propietarios/eliminados', 'App\Http\Controllers\PropietarioController@eliminados')->name('eliminados');
    Route::resource('propietarios', App\Http\Controllers\PropietarioController::class)->names('propietarios');

    Route::get('prestatario/guias/eliminados', 'App\Http\Controllers\Propietario\GuiasController@eliminados')->name('eliminados');
    Route::resource('prestatario/guias', App\Http\Controllers\Propietario\GuiasController::class)->names('proguias');

    Route::get('prestatario/cocineros/eliminados', 'App\Http\Controllers\Propietario\CocineroController@eliminados')->name('eliminados');
    Route::resource('prestatario/cocineros', App\Http\Controllers\Propietario\CocineroController::class)->names('prococineros');
    
    Route::get('prestatario/choferes/eliminados', 'App\Http\Controllers\Propietario\ChoferController@eliminados')->name('eliminados');
    Route::resource('prestatario/choferes', App\Http\Controllers\Propietario\ChoferController::class)->names('prochoferes');
    
    Route::get('prestatario/traductores/eliminados', 'App\Http\Controllers\Propietario\TraductorController@eliminados')->name('eliminados');
    Route::resource('prestatario/traductores', App\Http\Controllers\Propietario\TraductorController::class)->names('protraductores');


    /*Servicios CRUD*/
    Route::get('servicios/eliminados', 'App\Http\Controllers\ServicioController@eliminados')->name('eliminados');
    Route::resource('servicios', App\Http\Controllers\ServicioController::class)->names('servicios');

    Route::get('service/tickets/eliminados', 'App\Http\Controllers\Servicio\TicketController@eliminados')->name('eliminados');
    Route::resource('service/tickets', App\Http\Controllers\Servicio\TicketController::class)->names('servtickets');

    Route::get('service/hoteles/eliminados', 'App\Http\Controllers\Servicio\HotelController@eliminados')->name('eliminados');
    Route::resource('service/hoteles', App\Http\Controllers\Servicio\HotelController::class)->names('servhoteles');

    Route::get('service/habitaciones/eliminados', 'App\Http\Controllers\Servicio\HabitacionController@eliminados')->name('eliminados');
    Route::resource('service/habitaciones', App\Http\Controllers\Servicio\HabitacionController::class)->names('servhabitaciones');

    Route::get('service/accesorios/eliminados', 'App\Http\Controllers\Servicio\AccesorioController@eliminados')->name('eliminados');
    Route::resource('service/accesorios', App\Http\Controllers\Servicio\AccesorioController::class)->names('servaccesorios');

    Route::get('service/turistas/eliminados', 'App\Http\Controllers\Servicio\TuristaController@eliminados')->name('eliminados');
    Route::resource('service/turistas', App\Http\Controllers\Servicio\TuristaController::class)->names('servturistas');

    Route::get('service/vagonetas/eliminados', 'App\Http\Controllers\Servicio\VagonetaController@eliminados')->name('eliminados');
    Route::resource('service/vagonetas', App\Http\Controllers\Servicio\VagonetaController::class)->names('servvagonetas');

    Route::get('service/caballos/eliminados', 'App\Http\Controllers\Servicio\CaballoController@eliminados')->name('eliminados');
    Route::resource('service/caballos', App\Http\Controllers\Servicio\CaballoController::class)->names('servcaballos');

    Route::get('service/bicicletas/eliminados', 'App\Http\Controllers\Servicio\BicicletaController@eliminados')->name('eliminados');
    Route::resource('service/bicicletas', App\Http\Controllers\Servicio\BicicletaController::class)->names('servbicicletas');


    /*Tours CRUD*/
    Route::get('/tours/filtrar', 'App\Http\Controllers\TourController@filtrarPorTipo')->name('filtrarPorTipo');
    Route::get('tours/eliminados', 'App\Http\Controllers\TourController@eliminados')->name('eliminados');
    Route::resource('tours', App\Http\Controllers\TourController::class)->names('tours');

    Route::get('tour/categorias/eliminados', 'App\Http\Controllers\Tour\CategoriaController@eliminados')->name('eliminados');
    Route::resource('tour/categorias', App\Http\Controllers\Tour\CategoriaController::class)->names('tourcategorias');
    
    Route::get('tour/vips/eliminados', 'App\Http\Controllers\Tour\VipController@eliminados')->name('eliminados');
    Route::resource('tour/vips', App\Http\Controllers\Tour\VipController::class)->names('touvips');

    Route::resource('equipo', App\Http\Controllers\EquipoController::class)->names('equipo');
    Route::resource('reportes', App\Http\Controllers\ReporteController::class)->names('reportes');

    Route::resource('estatus', App\Http\Controllers\EstatusController::class)->names('estatus');
    Route::resource('miembros', App\Http\Controllers\MiembroController::class)->names('miembros');

    Route::resource('caja', App\Http\Controllers\CajaController::class)->names('caja');
    Route::resource('cajas/porcobros', App\Http\Controllers\Caja\PorcobroController::class)->names('cajacobros');
    Route::resource('cajas/porpagos', App\Http\Controllers\Caja\PorpagoController::class)->names('cajapagos');

    /*Configuración CRUD*/
    Route::resource('configuracion', App\Http\Controllers\ConfiguracionController::class)->names('configuracion');
    Route::resource('configuraciones/idiomas', App\Http\Controllers\Configuracion\IdiomaController::class)->names('confidiomas');
    Route::resource('configuraciones/alergias', App\Http\Controllers\Configuracion\AlergiaController::class)->names('confalergias');
    Route::resource('configuraciones/alimentacion', App\Http\Controllers\Configuracion\AlimentacionController::class)->names('confalimentacion');
    Route::resource('configuraciones/bancos', App\Http\Controllers\Configuracion\BancoController::class)->names('confbancos');
    Route::resource('configuraciones/impuestos', App\Http\Controllers\Configuracion\impuestoController::class)->names('confimpuestos');
    Route::resource('configuraciones/monedas', App\Http\Controllers\Configuracion\MonedaController::class)->names('confmonedas');
    Route::resource('configuraciones/empresas', App\Http\Controllers\Configuracion\EmpresaController::class)->names('confempresas');
    Route::resource('configuraciones/cobros', App\Http\Controllers\Configuracion\CobroController::class)->names('confcobros');
    Route::resource('configuraciones/onlines', App\Http\Controllers\Configuracion\OnlineController::class)->names('confonlines');
    Route::resource('configuraciones/links', App\Http\Controllers\Configuracion\LinkController::class)->names('conflinks');
    Route::resource('configuraciones/qrs', App\Http\Controllers\Configuracion\QrController::class)->names('confqrs');
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {
    // User Profile and Account Routes
    Route::resource(
        'profile',
        \App\Http\Controllers\ProfilesController::class,
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => 'profile.updateUserAccount',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => 'profile.updateUserPassword',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => 'profile.deleteUserAccount',
        'uses' => 'App\Http\Controllers\ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', \App\Http\Controllers\SoftDeletesController::class, [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', \App\Http\Controllers\UsersManagementController::class, [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::resource('themes', \App\Http\Controllers\ThemesManagementController::class, [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    // Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');
});

Route::redirect('/php', '/phpinfo', 301);
