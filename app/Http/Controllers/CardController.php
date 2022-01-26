<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return view('cards.index');
    }


    public function getCards(int $value = null): JsonResponse
    {
        try {

            $cards = Card::orderBy('value')->get();

            if ($value) {
                $cards = Card::where('value', $value)->get();
            }

            return response()->json($cards, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getSum()
    {
        try {
            $cards = Card::selectRaw('value, count(*) as total')
                ->groupBy('value')
                ->get();

            return response()->json($cards, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|min:5',
            'value' => 'required|numeric'
        ]);

        try {
                $card = new Card();
                $card->value = $data['value'];
                $card->code = $data['code'];
                $card->save();

            return response()->json([
                'success' => true,
                'message' => 'Cartão salvo com sucesso'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $card = Card::findOrFail($id);
            $code = $card->code;
            $card->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cartão deletado com sucesso',
                'code' => $code
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
