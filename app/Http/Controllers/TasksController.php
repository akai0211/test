<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Task;
use Session;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index')->withTasks($tasks);
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        $image = $request->file('image');
        $source = 'images';
        $imagename = $image->getClientOriginalName();
        $success = $image->move($source, $imagename);
        if($success){
            $task = new Task();
            $task->title = $request->Input('title');
            $task->description = $request->Input('description');
            $task->image = $imagename;
            $task->save();
            Session::flash('flash_message', 'Task successfully added!');
            return redirect()->back();
        }

    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show')->withTask($task);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit')->withTask($task);
    }

    public function update($id, Request $request)
    {
        $task = Task::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $input = $request->all();

        $task->fill($input)->save();

        Session::flash('flash_message', 'Task successfully updated!');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        Session::flash('flash_message', 'Task successfully deleted!');
        return redirect()->route('tasks.index');
    }
}
