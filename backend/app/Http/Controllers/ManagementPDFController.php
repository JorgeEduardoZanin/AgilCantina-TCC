<?php

namespace App\Http\Controllers;

use App\Models\AnnualManagement;
use App\Models\DailyManagement;
use App\Models\MonthManagement;
use PDF;
use Carbon\Carbon;

class ManagementPDFController extends Controller
{
    public function MonthPDFManagement()
    {
        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
    
        // Obter intervalo do mês atual em UTC (ou ajustado se necessário)
        $startDate = Carbon::now()->startOfMonth(); // Início do mês
        $endDate = Carbon::now()->endOfMonth();    // Fim do mês
        
        // Verifique o timezone dos registros no banco
        $dailyManagements = DailyManagement::where('cantina_id', $cantinaId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        // Verificar se registros foram encontrados
        if ($dailyManagements->isEmpty()) {
            return response()->json(['message' => 'Nenhum dado encontrado para o mês atual.'], 404);
        }
    
        // Obter os dados mensais
        $monthManagement = MonthManagement::where('cantina_id', $cantinaId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->first();

      
        
           
        
        // Gera o PDF
        $pdf = PDF::loadView('monthManagement_pdf', [
            'cantina' => $cantina,
            'dailyManagements' => $dailyManagements,
            'monthManagement' => $monthManagement,
        ]);
    
        return $pdf->stream('monthManagement_pdf.pdf');
    }

    public function AnnualPDFManagement()
    {
        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
    
        // Obter intervalo do mês atual em UTC (ou ajustado se necessário)
        $startDate = Carbon::now()->startOfYear(); // Início do ano
        $endDate = Carbon::now()->endOfYear();    // Fim do ano
        
        // Verifique o timezone dos registros no banco
        $monthManagements = MonthManagement::where('cantina_id', $cantinaId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        // Verificar se registros foram encontrados
        if ($monthManagements->isEmpty()) {
            return response()->json(['message' => 'Nenhum dado encontrado para o mês atual.'], 404);
        }
    
        // Obter os dados mensais
        $annualManagement = AnnualManagement::where('cantina_id', $cantinaId)
            ->whereYear('created_at', Carbon::now()->year)
            ->first();

        
        // Gera o PDF
        $pdf = PDF::loadView('annualManagement_pdf', [
            'cantina' => $cantina,
            'monthManagements' => $monthManagements,
            'annualManagement' => $annualManagement,
        ]);
    
        return $pdf->stream('annualManagement_pdf.pdf');
    }
    
}
