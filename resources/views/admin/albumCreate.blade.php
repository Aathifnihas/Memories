@extends('layouts.adminLayout')
@section('subtitle', 'Nieuw Album')
@section('content')
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <form action="{{ route('album.create') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title">Titel</label>
                        <input type="text" class="form-control" name="title" />
                        @if($errors->has('title'))<p class="help-block">{{ $errors->first('title') }}</p>@endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" />
                        @if($errors->has('thumbnail')) <p class="help-block">{{ $errors->first('thumbnail') }}</p>@endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                        <label for="description">Beschrijving</label> <span>Aantal karakters: <span id="remainingChars"></span></span>
                        <textarea id="description" class="form-control" name="description"></textarea>
                        @if($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p>@endif
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="aanmaken" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("#remainingChars").html(1000);
        $('#description').keypress(function(){
           if(this.value.length > 1000){
               return false;
           }

           $('#remainingChars').html(1000-this.value.length);
        });
    </script>
@endsection