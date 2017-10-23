@extends('layouts.adminLayout')
@section('subtitle', 'Album Aanpassen')
@section('content')
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <form action="{{ route('album.edit', ['id' => $album->id]) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title">Titel</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title', $album->title) }}"/>
                        @if($errors->has('title'))<p class="help-block">{{ $errors->first('title') }}</p>@endif
                    </div>
                    <div class="form-group @if($errors->has('thumbnail')) has-error @endif">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" />
                        @if($errors->has('thumbnail')) <p class="help-block">{{ $errors->first('thumbnail') }}</p>@endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                        <label for="description">Beschrijving</label>
                        <textarea class="form-control" name="description">{{ old('description', $album->description) }}</textarea>
                        @if($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p>@endif
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="aanpassen" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection