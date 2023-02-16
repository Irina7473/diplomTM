<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function  index()
    {
        /*return view('fields.index', [
            'projects' => Project::all(),
        ]);*/
    }

    //работает
    public function create(Request $request)
    {
        $field_id = $request->field_id;
        return view('projects.create-project',  [
            'field' => Field::find($field_id),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'projectName' => ['required','min:3', 'max:50'],

        ]);
        //$project = Project::create($request->all());
        $project = Project::create([
            'projectName' => $request->projectName,
            'field_id' => $request->field_id,
        ]);

        //$field = Field::find($project->field_id);

        //return redirect()->route('fields.show-field', $field);
       // return view ('fields.show-field');
        /*return view('/dashboard',  [
            'field' => $field,

        ]);*/
        return back();
    }

    public function edit($id)
    {
        $project = Project::find($id);
        return view('projects.edit-project', [
            'project' => $project,
            'field' =>Field::find($project->field_id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'projectName' => ['required', 'min:3', 'max:50'],
        ]);

        $project = Project::find($id);
        $project -> update($request->all());

        return back();
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return back();
    }
}
