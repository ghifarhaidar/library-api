<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function getAllClients()
    {
        return Client::with('borrowedBooks')->get();
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
