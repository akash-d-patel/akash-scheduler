<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Project\CreateRequest;
use App\Http\Requests\Project\EditRequest;
use App\Http\Resources\ProjectResource;

class ProjectController extends BaseController
{
    /**
     * List
     * @group Project
     */
    public function index()
    {
        $projects = Project::with(['creater'])->pimp()->paginate();
        $message = "All Records";
        ProjectResource::collection($projects);
        return $this->sendResponse(compact('projects'), $message);
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
     * @group Project
     */
    public function store(CreateRequest $request)
    {
        $project = Project::addUpdate(new Project, $request);
        $message = "Project added successfully";
        $project = new ProjectResource($project);
        return $this->sendResponse(compact('project'), $message);
    }

    /**
     * show
     * @group Project
     */
    public function show(Project $project)
    {
        $message = 'Project listed sucessfully';
        $project = new ProjectResource($project);
        return $this->sendResponse(compact('project'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update
     * @group Project
     */
    public function update(EditRequest $request, Project $project)
    {
        $project = Project::addUpdate($project, $request);
        $message = "Project updated successfully";
        $project = new ProjectResource($project);
        return $this->sendResponse(compact('project'), $message);
    }

    /**
     * Delete
     * @group Project
     */
    public function destroy(Project $project)
    {
        $project->delete();
        $message = "Project deleted successfully";
        $project = new ProjectResource($project);
        return $this->sendResponse(compact('project'), $message);
    }
}
