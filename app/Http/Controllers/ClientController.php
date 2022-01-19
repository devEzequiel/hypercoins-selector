<?php

namespace App\Http\Controllers;

use App\Models\AvailableCard;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        return view("clients.index");
    }

    public function getClients(): JsonResponse
    {
        try {
            $clients = Client::all()->toArray();

            $data = [];
            foreach ($clients as $client) {
                $balance = AvailableCard::selectRaw('value, count(*) as total')
                    ->where('client_id', $client['id'])
                    ->groupBy('value')
                    ->get()->toArray();

                $quantity = [];
                foreach ($balance as $b) {
                    $quantity[] = ['value' => $b['value'], 'quantity' => $b['total']];
                }

                $data[] = ['client' => $client, 'balance' => $quantity];
            }

            return response()->json($clients, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getClient(int $id)
    {
        $client = Client::findOrFail($id);

        return response()->json($client);
    }

    public function show()
    {
        return view("clients.show");
    }

    public function create()
    {
        return view("clients.create");
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|unique:clients',
            'name' => 'required',
            'password' => 'required|min:6'
        ]);

        try {
            $client = Client::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Cliente adicionado com sucesso',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function addBalance(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'array' => 'required'
        ]);

        try {
            foreach ($data['array'] as $id => $cards) {
                if ($cards['quantity'] > 0) {
                    for ($i = 1; $i <= $cards['quantity']; $i++) {
                        AvailableCard::create([
                            'client_id' => $data['client_id'],
                            'value' => $id
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Saldo adicionado com sucesso'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'nullable|email|unique:clients',
            'name' => 'nullable',
            'password' => 'nullable'
        ]);

        try {
            $client = Client::find($id);
            $client->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Cliente atualizado com sucesso',
                'data' => $client
            ], 200);
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
            $client = Client::findOrFail($id);
            $name = $client->name;
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente removido com sucesso',
                'name' => $name
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
