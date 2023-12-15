<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Services\ConektaService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConektaController extends Controller
{
    protected $conektaService;

    public function __construct(ConektaService $conektaService)
    {
        $this->conektaService = $conektaService;
    }

    public function getPaymentLinkConekta(Request $request, $debtId)
    {
        try {
            // Obtener datos de la deuda desde la base de datos
            $debt = Debt::findOrFail($debtId);

            $subdebts = json_decode($debt->subdebts, true);
            $clientData = json_decode($debt->ccf_client_data, true);
            $orderData = [];
    
            foreach ($subdebts as $subdebt) {
                $orderData = [
                    'name' => 'Product Name',
                    'unit_price' => (int)$subdebt['amount'],
                    'quantity' => 1,
                ];
            }

            $name = $clientData['first_name'];
    
            if (!$debt) {
                return response()->json(['error' => 'La deuda no existe en la base de datos'], 404);
            }
    
            $debtData = [
                'name' => 'Payment Link Name',
                'type' => 'PaymentLink',
                'recurrent' => false,
                'expires_at' => time() + (24 * 60 * 60), // Expira en 24 horas
                'allowed_payment_methods' => ["cash"],
                'needs_shipping_contact' => true,
                'order_template' => [
                    'line_items' => $orderData,
                    'currency' => 'MXN',
                    'customer_info' => [
                        'name' => $name,
                        'email' => 'ejemplo@gmail.com',
                        'phone' => '+52333333332',
                    ],
                ],
            ];

            $postPaymentLink = $this->conektaService->postPaymentLink($debtData);
            $getPaymentLink = $this->conektaService->getPaymentLinkById($debtData, $debtId);

            if ($getPaymentLink) {
                //dd($response);
                return response()->json(['message' => 'Exito al obtener el link de pago', 'response' => $getPaymentLink], 201);
            } else {
                return response()->json(['error' => 'No se pudo obtenet el link de pago'], 500);
            }
          
        } catch (\Exception $e) {
            Log::error('Error al obtener el link de pago: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el link de pago'], 500);
        }
    }
    
}