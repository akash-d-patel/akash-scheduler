<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Service\CreateRequest;
use App\Http\Requests\Service\EditRequest;
use App\Http\Resources\ServiceResource;

class ServiceController extends BaseController
{
    /**
     * List
     * @group Service
     */
    public function index(Request $request, Service $service)
    {
        $services = $service->pimp()->paginate();
        $message = "All Records";
        ServiceResource::collection($services);
        return $this->sendResponse(compact('services'), $message);
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
     * @group Service
     */
    public function store(CreateRequest $request)
    {
        $service = Service::addUpdate(new Service, $request);
        $message = "Service added successfully";
        $tax = new ServiceResource($service);
        return $this->sendResponse(compact('service'), $message);
    }

    /**
     * Show
     * @group Service
     */
    public function show(Service $service)
    {
        $message = 'Service listed successfully';
        $service = new ServiceResource($service);
        return $this->sendResponse(compact('service'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update
     * @group Service
     */
    public function update(EditRequest $request, Service $service)
    {
        $service = Service::addUpdate($service, $request);
        $message = "Service updated successfully";
        $service = new ServiceResource($service);
        return $this->sendResponse(compact('service'), $message);
    }

    /**
     * Delete
     * @group Service
     */
    public function destroy(Service $service)
    {
        $service->delete();
        $message = "Service deleted successfully";
        $tax = new ServiceResource($service);
        return $this->sendResponse(compact('service'), $message);
    }
}
