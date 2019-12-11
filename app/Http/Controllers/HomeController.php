<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        //dd(auth::user()->rol->nombre);
        return view('partial.contenido');
    }

    public function testSoap(){
        $wsdl = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl";
        $options = [
            'login' => 'MODDATOS',
            'password' => 'MODDATOS',
            'encoding' => 'utf-8',
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'soap_version' => SOAP_1_1
        ];

        try {
            $cliente = new \SoapClient($wsdl, $options);
            $result = $cliente->__soapCall('getStatus');

            print_r();

        } catch (\Exception $ex) {
            throw $ex;
        }



    }
}
