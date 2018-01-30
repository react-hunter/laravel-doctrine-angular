<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

/**
 * Show Task Dashboard
 */
Route::get('/', function (TaskRepository $repository) {
    return view('tasks', [
        'tasks' => $repository->all('createdAt', 'ASC')
    ]);
});

/**
 * Add New Task
 */
Route::post('/task', function (Request $request, EntityManagerInterface $em) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task(
        $request->get('name')
    );

    $em->persist($task);
    $em->flush();

    return redirect('/');
});

/**
 * Delete Task
 */
Route::delete('/task/{id}', function ($id, TaskRepository $repository, EntityManagerInterface $em) {

    $task = $repository->find($id);

    $em->remove($task);
    $em->flush();

    return redirect('/');
});
