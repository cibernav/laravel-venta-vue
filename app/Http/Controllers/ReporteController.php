<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JasperPHP\JasperPHP;
use App\Articulo;

class ReporteController extends Controller
{
    //

    public function getDataBaseConfig(){

        return [
            'driver'   => env('DB_CONNECTION'),
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'jdbc_dir' => base_path() . env('JDBC_DIR', '/vendor/cossou/jasperphp/src/JasperStarter/jdbc'),
        ];
    }

    private function generateRpt($nombre = 'reporte', $rpt = 'example.jasper', $type = 'pdf', array $param = []){

        $nameFile = time() . $nombre;
        $inputFile = base_path('/resources/reports/') . $rpt; //.jasper
        $outputFile = base_path('/resources/reports/') . $nameFile;

        $report = new JasperPHP;
        //public_path()
        $report->process(
            $inputFile,
            $outputFile,
            [$type],
            $param,
            $this->getDatabaseConfig()
        )->execute();

        $file = $outputFile . '.' . $type;
        $path = $file;

        if (!file_exists($file)) {
            abort(404);
        }
        //cargamos el archivo en memoria
        $file = file_get_contents($file);
        //eliminamos el archivo generado
        unlink($path);

        return [
            'name' => $nameFile,
            'file' => $file
        ];
    }

    public function rptArticulo(){

        $array = $this->generateRpt('articulo', 'articulo.jasper', 'pdf');

        return response($array['file'], 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline;filename="' . $array['name'] . '.pdf"')
            ->header('Content-Transfer-Encoding: binary', true)
            ->header('Accept-Ranges: bytes', true);

    }

    public function rptComprobanteVenta($id){

        $param =  [
            'pIdVenta' => $id
        ];

        $array = $this->generateRpt('comprobante', 'comprobanteVenta.jasper', 'pdf', $param);


        return response($array['file'], 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline;filename="' . $array['name'] . '.pdf"')
            ->header('Content-Transfer-Encoding: binary', true)
            ->header('Accept-Ranges: bytes', true);
    }

}
