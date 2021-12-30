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

    public function getCards(): JsonResponse
    {
        try {
            $cards1 = Card::where('value', 1)
                ->get();

            $cards2 = Card::where('value', 2)
                ->get();

            $cards3 = Card::where('value', 3)
                ->get();

            $cards4 = Card::where('value', 4)
                ->get();

            $cards5 = Card::where('value', 5)
                ->get();

            $cards6 = Card::where('value', 6)
                ->get();

            $cards = array('1' => $cards1, '2' => $cards2, '3' => $cards3, '4' => $cards4, '5' => $cards5, '6' => $cards6);

            return response()->json([
                'success' => true,
                'data' => $cards
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function create()
    {
        return view("cards.create");
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'array' => 'required',
        ]);

        try {
            foreach ($data['array'] as $d) {
                $card = new Card();
                $card->value = $d['value'];
                $card->code = $d['code'];
                $card->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cartões salvos com sucesso'
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
