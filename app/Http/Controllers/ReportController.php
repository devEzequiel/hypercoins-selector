<?php

namespace App\Http\Controllers;

use App\Models\AvailableCard;
use App\Models\Card;
use App\Models\Client;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function show()
    {
        return view("reports.show");
    }

    public function getReport($start_date = null, $end_date = null)
    {
        try {
            $from = Carbon::now();
            $to = Carbon::now()->addMonth();

            if ($start_date != null && $end_date != null) {
                $from = date($start_date);
                $to = date($end_date);
            }

            $report = Report::whereBetween('reservation_from',
                [$from, $to]
            )->get();

            return response()->json([
                'success' => true,
                'message' => 'Relatório gerado com sucesso',
                'data' => $report
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function create()
    {
        return view("reports.create");
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'value' => 'required',
                'quantity' => 'required'
            ]);

            $id = 2;//Auth::id();

            $cards = AvailableCard::where('client_id', $id)
                ->where('value', $data['value'])->get();

            if ($data['quantity'] > $cards->count()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente'
                ], 422);
            }

            $cards = Card::where('value', $data['value'])->get();

            if ($data['quantity'] > $cards->count()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não temos cartões suficientes para esse valor. Comunique ao suporte'
                ], 422);
            }

            $cards = [];
            for ($i = 1; $i <= $data['quantity']; $i++) {
                $card = Card::where('value', $data['value'])
                    ->first()
                    ->makeHidden(['created_at', 'updated_at']);

                $cards[] = $card;
                $card->delete();

                $av_card = AvailableCard::where('value', $data['value'])
                    ->where('client_id', $id)
                    ->first();

                $av_card->delete();
            }

            $amount = $data['quantity'] * $cards[0]['value'];

            Report::create([
                'client_id' => $id,
                'amount' => $amount
            ]);

            return response()->json([
                'success' => true,
                'data' => $cards
            ], 200);
        } catch
        (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
