<?php

namespace App\Http\Controllers;

use App\Models\AvailableCard;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function getBalance(int $id = null)
    {
        if (!$id) {
            $client = Client::where('user_id', auth()->user()->id)->first();
            $id = $client->id;
        }

        try {
            $quantity = [];
            for ($i = 1; $i <= 6; $i++) {

                $balance = AvailableCard::selectRaw('value, count(*) as total')
                    ->where('client_id', $id)
                    ->where('value', $i)
                    ->get()->toArray();
                $quantity[$i] = [
                    "value" => $balance[0]["value"],
                    "total" => $balance[0]["total"]
                ];
            }

            return response()->json($quantity);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|unique:clients',
            'name' => 'required',
            'password' => 'required|min:6'
        ]);

        try {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'client' => true,
                'admin' => false
            ]);

            $data['user_id'] = $user->id;

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
            'value' => 'required',
            'quantity' => 'required'
        ]);

        try {

            $available = AvailableCard::where('client_id', $data['client_id'])
                ->where('value', $data['value'])->get();

            if ($data['quantity'] > 0) {
                for ($i = 1; $i <= $data['quantity']; $i++) {
                    AvailableCard::create([
                        'client_id' => $data['client_id'],
                        'value' => $data['value']
                    ]);
                }
            } elseif ($data['quantity'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum saldo enviado'
                ], 400);
            } elseif (($data['quantity'] < 0) && ($available->count() >= abs($data['quantity']))) {
                for ($i = -1; $i >= $data['quantity']; $i--) {
                    $card = AvailableCard::where('client_id', $data['client_id'])->
                    where('value', $data['value'])->first();

                    $card->delete();

                }
            } else //if ($available->count() >= abs($data['quantity']))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro'
                ], 400);
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
            $data['password'] = Hash::make($data['password']);
            $user = User::find($client->user_id);
            $user->update($data);

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
            $user = User::find($client->user_id);
            $user->delete();

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
