<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Field;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        //
    }

    //работает
    public function create(Request $request)
    {
        $project_id = $request->field_id;
        return view('tasks.create-task',  [
            'project' => Project::find($project_id),
        ]);
    }

    //работает
    public function store(Request $request)
    {
        $request->validate([
            'taskName' => ['required', 'max:50'],
//            'deadline' => ['required'],
//            'description' => ['required'],
        ]);

        $project_id = $request->project_id;
        Task::create([
            'taskName' => $request->taskName,
            'project_id' => $project_id,
//            'deadline' => $request->deadline,
        ]);

        return redirect()->route('projects.show', $project_id);
    }

    //работает
    public function edit($id)
    {
        $task = Task::find($id);
        return view('tasks.edit-task', [
            'task' => $task,
            'project' => Project::find($task->project_id),
        ]);
    }

    //работает
    public function update(Request $request, $id)
    {
        $request->validate([
            'taskName' => ['required', 'max:50'],
        ]);

        $task = Task::find($id);
        $task->update($request->all());

        $project_id = $task->project_id;
        return redirect()->route('projects.show', $project_id);
    }

    //работает
    public function destroy($id)
    {
        $task = Task::find($id);
        $project_id = $task->project_id;
        $task->delete();
        return redirect()->route('projects.show', $project_id);
    }

    public function show($task_id)
    {
        $task = Task::find($task_id);
        $project = $task->project();
        return view('tasks.show-task', [
//            'field' => $project->field(),
            'project' => $project,
            'task' => $task,
//            'selected' => $task,
            'comments' => Comment::all() -> where('task_id', $task_id),
            'files' => File::all() -> where('task_id', $task_id),
        ]);
    }


}
