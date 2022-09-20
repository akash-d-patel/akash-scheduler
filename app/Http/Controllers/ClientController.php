<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\Client\CreateRequest;
use App\Http\Requests\Client\EditRequest;
use App\Http\Resources\ClientResource;
use App\Http\Controllers\Api\BaseController;

class ClientController extends BaseController
{

    public function __construct()
    {
        $this->authorizeResource(Client::class);
        $this->getMiddleware();
    }

    /**
     * List
     * @group Client
     */
    public function index(Request $request)
    {
        $clients = Client::with(['creater'])->pimp()->paginate();
        ClientResource::collection($clients);
        return $this->sendResponse(compact('clients'), "All Record");   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Add
     * @group Client
     */
    public function store(CreateRequest $request)
    {
        $client = Client::createUpdate(new Client, $request);
        $message = "Client added successfully";
        $client = new ClientResource($client);
        return $this->sendResponse(compact('client'), $message);
    
    }

    /**
     * Show
     * @group Client
     */
    public function show(Client $client, Request $request)
    {
        $message = 'Client listed successfully';
        $client = new ClientResource($client);
        return $this->sendResponse(compact('client'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        // 
    }

    /**
     * Update
     * @group Client
     */
    public function update(EditRequest $request, Client $client)
    {
        $client = Client::createUpdate($client, $request);
        $message = "Client updated successfully";
        $client = new ClientResource($client);
        return $this->sendResponse(compact('client'), $message);
    }

    /**
     * Delete
     * @group Client
     */
    public function destroy(Client $client, Request $request)
    {
        $client->delete();
        $message = "Client deleted successfully";
        $client = new ClientResource($client);
        return $this->sendResponse(compact('client'), $message);
    }
}
