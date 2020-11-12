@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Roles</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <th >{{ $user->name }}</th>
                                        <th > {{ $user->email }}</th>
                                        <th> {{ implode(', ', $user->roles()->get()->pluck('name')->toArray()) }} </th>
                                        <th>
                                            @can ('edit-users')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>
                                            @endcan

                                            @can ('delete-users')
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning">Delete</button>
                                            </form>
                                            @endcan
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $user->name }} - {{ $user->email }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
