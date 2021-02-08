<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller{

    public function __construct() {
        //Con el middleware auth (que ya viene por defecto en laravel)
        //Impedimos que un usuario no autentificado pueda crear, leer, editar, actualizar o borrar las tareas.
        $this->middleware('auth');
    }

    public function getTasks(){
        //Las tareas que podrá visualizar un usuario son únicamente sus propias tareas.
        $tasks_user = Task::where('user_id', '=', \Auth::user()->id)->orderBy('id', 'desc')->get();

        //Un administrador podrá visualizar las tareas de todos los usuarios.
        $tasks_admin = Task::orderBy('id', 'desc')->get();
        return view('tasks.home', [
            'tasks_user' => $tasks_user,
            'tasks_admin' => $tasks_admin
        ]);
    }

    public function create(){
        //Hacemos una consulta a la bbdd para obtener los usuarios, ya que en caso de
        //ser admins podremos crear tareas a otros usuarios.
        $users = User::orderBy('email', 'asc')->get();

        return view('tasks.create', [
            'users' => $users
        ]);
    }

    public function save(Request $request){
        //Validamos los datos que nos llegan del formulario.
        $validate = $this->validate($request, [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'priority' => ['required'],
        ]);

        //Asignamos los valores
        $title = $request->get('title');
        $description = $request->get('description');
        $priority = $request->get('priority');

        //Creamos una instancia del modelo.
        $task = new Task();
        if($task){

            //Si el usuario que está creando la tarea es un administrador
            //podremos elegir el email del usuario al que queremos crearle la tarea.

            //Si el usuario es un usuario común le asignaremos el id del usuario logueado automaticamente.
            if(\Auth::user()->rol == 'admin'){
                $validate = $this->validate($request, [
                    'user' => ['required']
                ]);
                $user_id = $request->get('user');
                $task->user_id = $user_id;
            }else{
                $task->user_id = \Auth::user()->id;
            }

            //Seteamos los datos del formulario en el nuevo objeto del modelo.
            $task->title = $title;
            $task->description = $description;
            $task->priority = $priority;
    
            //Guardamos
            $task->save();
        }else{
            return redirect()->route('home')->with(['message' => 'Error']);
        }
        

        return redirect()->route('home')->with(['message' => 'Task Created!']);

        
    }

    public function edit($id){
        //Usamos find para encontrar el id único en la bbdd que nos llega por la URL
        $task = Task::find($id);

        //Hacemos una consulta a la bbdd para obtener los usuarios, ya que en caso de
        //ser admins podremos crear tareas a otros usuarios.
        $users = User::orderBy('email', 'asc')->get();

        //Si intentamos editar ilegítimamente mediante la url una tarea que no nos pertenece o no somos admin
        //seremos retornados al home.
        if(\Auth::user()->rol != 'admin' && $task->user_id != \Auth::user()->id){
            return redirect()->route('home');
        }

        return view('tasks.edit', [
            'task' => $task,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id){
        //Validamos los datos. Únicamente del título. ¿Por qué?

        //Bien, al tener 2 select es obligatorio clicar el valor de nuevo para cargarlo
        //lo cual es algo incómodo. Así que, nos encargamos de resolver esos "defectos de html"
        //para la comodidad del usuario y/o administrador.

        //También al tener un textarea no podemos cargarle un value, por lo que hacemos una simulación con un placeholder.
        $validate = $this->validate($request, [
            'title' => ['required', 'string', 'max:100'],
        ]);

        //Recogemos el título
        $title = $request->get('title');

        //Buscamos en la base de dato el id único con el param que recogemos por la URL
        $task = Task::find($id);

        //Si existe esta tarea y además el id es el mismo que el usuario o somos el admin, podremos editarlo.
        //Sino es así, seremos retornados.
        if($task && $task->user_id == \Auth::user()->id || $task && \Auth::user()->rol == 'admin'){
            $task->title = $title;

            //Comprobamos si somos administrador (ya que él es el único con poder para crear tareas para otros usuarios)
            //Si es así, realizamos las validaciones y recogemos el usuario que llega por request
            if(\Auth::user()->rol == 'admin' && $request->get('user') != null && $task->user_ud != $request->get('user')){
                $validate = $this->validate($request, [
                    'user' => ['string']
                ]);
                $user_id = $request->get('user');
                $task->user_id = $user_id;
            }else{
                //De no ser así, simplemente recogemos el id del usuario logueado.
                $task->user_id = \Auth::user()->id;
            }

            //Si la descripción no es la misma que la de la bbdd y además no es nula.
            //Realizamos la validación y seteamos los valores.
            if($request->get('description') != $task->description && $request->get('description') != null){
                $validate = $this->validate($request, [
                    'description' => ['string']
                ]);
                $task->description = $request->get('description');
            }
            
            //Al igual que con la descripción, realizamos esta comprobación con la prioridad.
            if($request->get('priority') != $task->priority && $request->get('priority') != null){
                $task->priority = $request->get('priority');
            }
    
            //Actualizamos la tarea.
            $task->update();
        }else{
            return redirect()->route('home')->with(['message' => 'Error']);
        }

        return redirect()->route('home')->with(['message' => 'Task Updated!']);


        
    }

    public function delete($id){
        //Usamos find para encontrar el id único en la bbdd que nos llega por la URL
        $task = Task::find($id);

        //Si esta tarea existe y somos el propietario de la tarea o el administrador:
        if($task && $task->user_id == \Auth::user()->id || $task && \Auth::user()->rol == 'admin'){

            //Borramos la tarea.
            $task->delete();
        }else{
            return redirect()->route('home')->with(['message' => 'Error']);
        }

        return redirect()->route('home')->with(['message' => 'Task deleted!']);
    }
}
