@extends('layouts.app')

@section('title')
Create Task
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('message'))
            <h1>{{session('message')}}</h1>
            @endif
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class="card">
                <div class="card-header">Create task</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('task.save') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user" class="col-md-4 col-form-label text-md-right">User</label>

                            <div class="col-md-6">
                                @if(\Auth::user()->rol == 'admin')
                                <select class="form-control" name="user">
                                    <option value="" selected disabled hidden>Choose user...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->email}}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class="form-control" value="{{\Auth::user()->email}}" disabled/>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control" name="description"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="priority" class="col-md-4 col-form-label text-md-right">Priority</label>

                            <div class="col-md-6">
                                <select name="priority" class="form-control">
                                <option value="" selected disabled hidden>Choose priority...</option>
                                    <option value="high">Alta</option>
                                    <option value="medium">Media</option>
                                    <option value="low">Baja</option>
                                </select>
                            </div>
                        </div>
                        
                        <input class="form-control btn btn-primary" type="submit" value="Create"/>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
