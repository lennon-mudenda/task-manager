<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->query('project_id')) {
            $query->where('project_id', $request->query('project_id'));
        }

        $tasks = $query->orderBy('priority')->paginate(20);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        $data = $request->validated();

        $data['priority'] = Task::getDefaultPriority();


        if (!$request->input('project_id')) {
            $data['project_id'] = Project::firstOrCreate(['name' => 'Default']);
        }

        // TODO: Ensure there is a lock on this operation to avoid race conditions.
        $task = Task::create($data);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        // TODO: Ensure there is a lock on this operation to avoid race conditions.
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->forceDelete();

        // TODO: Trigger a reordering job that handles priority reassignments appropriately.
        return new TaskResource($task);
    }
}
