<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientService
{
    public function getAllClients(Request $request)
    {
        $query = Client::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        return $query->paginate(10)->appends($request->query());
    }

    public function createClient(array $data)
    {
        return Client::create($data);
    }

    public function getClient(Client $client)
    {
        return $client->load('borrowedBooks');
    }

    public function updateClient(Client $client, array $data)
    {
        $client->update($data);
        return $client->load('borrowedBooks');
    }

    public function deleteClient(Client $client)
    {
        $client->delete();
        return true;
    }
}
