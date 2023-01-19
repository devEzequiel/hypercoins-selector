<?php

namespace App\Http\Controllers;

use App\Mail\CardsSent;
use App\Mail\SendCards;
use App\Models\AvailableCard;
use App\Models\Card;
use App\Models\Client;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function show()
    {
        return view("reports.index");
    }

    public function getReport($start_date = null, $end_date = null)
    {
        try {
            $from = Carbon::now()->subMonth();
            $to = Carbon::now();

            if ($start_date != null && $end_date != null) {
                $from = date($start_date);
                $to = date($end_date);

            }

            $report = Report::whereBetween('created_at',
                [$from, $to]
            )->with(array('client' => function ($query) {
                $query->select('id', 'name');
            }))
                ->get();

//            $report = DB::table('clients')
//                ->join('reports', 'clients.id', '=', 'reports.client_id')
//                ->select('clients.name', 'reports.amount', 'reports.created_at')
//                ->get();

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


    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'value' => 'required',
                'quantity' => 'required|integer'
            ]);

            $client = Client::where('user_id', auth()->user()->id)->first();

            $cards = AvailableCard::where('client_id', $client->id)
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
            DB::beginTransaction();
            $cards = [];
            for ($i = 1; $i <= $data['quantity']; $i++) {
                $card = Card::where('value', $data['value'])
                    ->first()
                    ->makeHidden(['created_at', 'updated_at']);

                $cards[] = $card;
                $card->delete();

                $av_card = AvailableCard::where('value', $data['value'])
                    ->where('client_id', $client->id)
                    ->first();

                $av_card->delete();
            }

            $amount = $data['quantity'] * $cards[0]['value'];


            Report::create([
                'client_id' => $client->id,
                'amount' => $amount
            ]);
            DB::commit();

            Mail::send(new SendCards($cards, $client->name));
            Mail::send(new CardsSent($cards, $client->name));

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
