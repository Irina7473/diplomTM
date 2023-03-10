<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index($field_id)
    {
        //
    }

    //работает
    public function create(Request $request)
    {
        return view('fields.create-field');
    }

    //работает
    public function store(Request $request)
    {
        $request->validate([
            'fieldName' => ['required','min:3', 'max:50'],
        ]);

        $field = Field::create([
            'fieldName' => $request->fieldName,
        ]);

        $field->uploadFile($request->file('fond'));

        $user_id = $request->user_id;
        Team::create([
            'field_id' => $field->id,
            'user_id' => $user_id,
            // по умолчанию создает поля менеждер - продумать связь с БД
            'role_id' => 2,
        ]);

        return redirect()->route('users.show', $user_id);
    }

    //работает
    public function edit($id)
    {
        return view('fields.edit-field', [
            'field' =>Field::find($id),
        ]);
    }

    //добавление участников через приглашение на почту
    //удаление участников
    public function update(Request $request, $id)
    {
        $request->validate([
            'fieldName' => ['required', 'min:3', 'max:50'],
        ]);

        $field = Field::find($id);

        $field -> update([
            'fieldName' => $request->fieldName,
        ]);
        $field -> uploadFile ($request->file('fond'));

        $user_id = $request->user_id;
        return redirect()->route('users.show', $user_id);
    }

    //НЕ ПОЛЬЗОВАТЬСЯ! -доделать с каскадным удалением
    public function destroy($id)
    {
        /*
        $field = Field::find($id);
        $teams = Team::all()->where('field_id', $id);
        $field->removeFile();
        $teams->delete();  //не работает - выдает ошибку
        $field->delete();

        return back();
        */
    }

    //работает
    public function show($field_id)
    {
        $field = Field::find($field_id);
        return view('/dashboard', [
            'users' => $field->users,
            'field' => $field,
            'projects' => Project::all()->where('field_id', $field_id) -> sortBy('projectName'),
            'selected' => $field,
        ]);
    }

}
