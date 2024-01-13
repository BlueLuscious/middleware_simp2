<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Debt;

class AlquimiaPayController extends Controller
{
    public function getApiManagerToken()
    {
        $client = new Client();
        $url = 'https://wso2.alquimiapay.com/token';

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic TjJmdFFqZGVWTjd5RlQyMnA3ejJlV2tJQnVZYTpqOWQxZ0xZel9xekRHNmJhcXhzUFZNems0Sklh',
        ];

        $body = [
            'grant_type' => 'client_credentials',
        ];

        $response = $client->post($url, [
            'headers' => $headers,
            'form_params' => $body,
        ]);

        $json = json_decode($response->getBody(), true);
        $tokenData = $json['access_token'];

        return $tokenData;
    }

    public function getAlquimiaToken()
    {
        $client = new Client(['verify' => false ]);
        $url = 'https://demomatic.alquimiadigital.mx/cpanel/index.php/api/oauth2/token';

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $data = [
            'grant_type' => 'password',
            'username' => 'allen.geier@argonglobal.tech',
            'password' => 'Allen5340$',
            'client_id' => 'testclient',
            'client_secret' => 'testpass',
        ];

        $response = $client->post($url, [
            'headers' => $headers,
            'form_params' => $data,
        ]);

        $json = json_decode($response->getBody(), true);
        $accessToken = $json['access_token'];

        return $accessToken;
    }

    // IT DOESN'T WORK YET //
    public function debtAlquimiaPay(Request $request, $Debtid){
        $debt = Debt::find($Debtid);
        $data = [
            'id_cliente' => $debt->cliente_id,
            'id_servicio' => $debt->servicio_id,
/*             'servicio' => 'AT-T / Iusacell',
            'id_producto' => 16,
            'producto' => 'Recarga $10',
            'tipo_front' => 1,
            'tipo_referencia' => 'a',
            'monto_cobro' => 10.0,
            'monto_comision' => 0.0,
            'comision_proveedor' => 0.0,
            'referencia' => '123456789',
            'telefono' => '7291559953',
            'concepto' => 'prueba pago de serv.',
            'cuenta_ahorro' => '1000000000000001',
            'codigo_seguridad' => '1234',
            'token' => '1234',
            'tipo_cuenta' => 3, */
        ];

        $ApiManagerToken = $this->getApiManagerToken();
        $AlquimiaToken = $this->getAlquimiaToken();

        $headers = [
            'Authorization' => 'Bearer ' . $ApiManagerToken,
            'AuthorizationAlquimia' => 'Bearer ' . $AlquimiaToken,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $response = Http::withoutVerifying()->withHeaders($headers)->asForm()->post('https://wso2.alquimiapay.com/pagoservicios/1.0.0/v2/pago', $data);
        return response($response->body(), $response->status());
    }

}
