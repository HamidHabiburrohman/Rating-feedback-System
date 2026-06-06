{{-- TODO: Implement this view --}}
{{-- File: ' . $viewPath . ' --}}

@extends('layouts.' . explode('/', $folderPath)[0] . '.app')

@section('title', ' . ucfirst($viewName) . ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h4>View: ' . $viewPath . '</h4>
                <p>This view is under construction.</p>
            </div>
        </div>
    </div>
</div>
@endsection