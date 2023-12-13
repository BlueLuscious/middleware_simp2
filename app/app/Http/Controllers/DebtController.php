<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebtController extends Controller
{
    public function index()
    {
        return Debt::all();
    }

    public function show(Debt $debt)
    {
        return $debt;
    }

    public function createDebt(Request $request)
    {
        try {
            $debtsData = $request->input('debtsData');

            // Procesar y almacenar la deuda en la base de datos
            foreach ($debtsData as $debtData) {
                $subdebts = json_encode($debtData['subdebts']);
                $clientData = json_encode($debtData['ccf_client_data']); 

                Debt::create([
                    'code' => $debtData['code'],
                    'status' => $debtData['status'],
                    'api_client' => $debtData['api_client'],
                    'ccf_code' => $debtData['ccf_code'],
                    'ccf_client_id' => $debtData['ccf_client_id'],
                    'ccf_client_data' => $clientData,
                    'client_origin_id' => $debtData['client_origin_id'],
                    'subdebts' => $subdebts,
                ]);
            }

            return response()->json(['message' => 'Deuda(s) almacenada(s) con éxito']);
        } catch (\Exception $e) {
            Log::error('Error al procesar y almacenar la deuda: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo procesar la solicitud'], 500);
        }
    }

    public function showDebt(Request $request) // $id
    {
        try {
            $debts = $request->input('debtsData');
            return response()->json($debts);
        } catch (\Exception $e) {
            Log::error('Error al obtener la deuda de SIMP2: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener la deuda de SIMP2'], 500);
        }
    }
}