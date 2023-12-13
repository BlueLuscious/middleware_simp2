<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Simp2Middleware
{
    public function handle($request, Closure $next)
    {
        try {
            $code = 49401; // show all subdebts
            $unique_reference = 39168246; // show one of the subdebts

            $response = Http::withHeaders([
                'X-API-KEY' => env('SIMP2_API_KEY'),
                'company-transaction-token' => env('SIMP2_CTT')
            ])->get(env('SIMP2_URL_BASE')."/debt/general/{$unique_reference}");

            if ($response->successful()) {
                $debtsData = $response->json();
                $request->merge(['debtsData' => $debtsData]);
            } else {
                Log::error('Error al obtener la deuda del SIMP2');
            }
        } catch (\Exception $e) {
            Log::error("Error en Simp2Middleware: " . $e->getMessage());
        }

        return $next($request);
    }
}