@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Mostramos la sesiones retornadas --}}
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Si el usuario logueado es admin, podremos tener acceso a todas las tareas. Así como editarlas o borrarlas. --}}
            @if(\Auth::user()->rol == 'admin')
                @foreach($tasks_admin as $task)
                <a href="{{route('task.edit', ['id' => $task->id])}}">Edit</a>
                <a  class="ml-5" href="{{route('task.delete', ['id' => $task->id])}}">Delete</a>
                    <div class="card mb-4">
                        <div class="card-header">{{$task->title}} --- {{$task->priority}}</div>

                        <div class="card-body">
                            <p>{{$task->description}}</p>
                        </div>
                    </div>
                @endforeach
            @else

                {{-- Si el usuario logueado es un usuario común, unicamente tendrá acceso a sus propias tareas. Así como editarlas o borrarlas. --}}
                @foreach($tasks_user as $task)
                <a href="{{route('task.edit', ['id' => $task->id])}}">Edit</a>
                <a class="ml-5" href="{{route('task.delete', ['id' => $task->id])}}">Delete</a>
                    <div class="card mb-4">
                        <div class="card-header">{{$task->title}} --- {{$task->priority}}</div>

                        <div class="card-body">
                            <span>{{$task->description}}</span>
                        </div>
                    </div>
                    @endforeach
                    @endif
        </div>
    </div>
</div>
@endsection
