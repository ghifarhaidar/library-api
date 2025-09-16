<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClientController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:clients.view', only: ['index']),
            new Middleware('permission:clients.create', only: ['store']),
            new Middleware('permission:clients.view_details', only: ['show']),
            new Middleware('permission:clients.update', only: ['update']),
            new Middleware('permission:clients.delete', only: ['destroy']),

        ];
    }
    public function __construct(protected ClientService $clientService)
    {
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = $this->clientService->getAllClients($request);
        return response()->json([
            'data' => ClientResource::collection($clients),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page'    => $clients->lastPage(),
                'per_page'     => $clients->perPage(),
                'total'        => $clients->total(),
            ],
            'links' => [
                'first' => $clients->url(1),
                'last'  => $clients->url($clients->lastPage()),
                'prev'  => $clients->previousPageUrl(),
                'next'  => $clients->nextPageUrl(),
            ],
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->createClient($request->validated());
        return response()->json([
            'client' => new ClientResource($client),
            'meta' => [
                'current_page' => $client->currentPage(),
                'last_page'    => $client->lastPage(),
                'per_page'     => $client->perPage(),
                'total'        => $client->total(),
            ],
            'links' => [
                'first' => $client->url(1),
                'last'  => $client->url($client->lastPage()),
                'prev'  => $client->previousPageUrl(),
                'next'  => $client->nextPageUrl(),
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client = $this->clientService->getClient($client);
        return response()->json(['client' => new ClientResource($client)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client = $this->clientService->updateClient($client, $request->validated());
        return response()->json(['client' => new ClientResource($client)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->clientService->deleteClient($client);
        return response()->json(['message' => 'Client deleted'], 204);
    }
}
