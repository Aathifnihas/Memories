@extends('layouts.adminLayout')
@section('subtitle', 'Albums')
@section('content')
    <div class="container">
        <div class="modal" id="delete-modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Verwijderen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> U staat op het punt om <strong id="delete-name"></strong> te verwijderen.<br/>
                            weet u dit zeker?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form id="form-delete" method="POST" action="">
                            {{ csrf_field() }}
                            <input type="submit" value="Ja" class="delete-confirm btn btn-success"/>
                            <input type="submit" value="Nee" class="btn btn-default" data-dismiss="modal"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                @if(session()->has('errorMsg'))
                    <div class="alert alert-danger">
                        <strong>{{ session()->get('errorMsg') }}</strong>
                    </div>
                @elseif(session()->has('successMsg'))
                    <div class="alert alert-success">
                        <strong>{{ session()->get('successMsg') }}</strong>
                    </div>
                @endif
            </div>
            <div class="row" style="margin-bottom: 10px">
                <a href="{{ route('album.create') }}" class="btn btn-success">Album maken</a>
            </div>
            <div class="row">
                <table class="table table-striped">
                    <tr>
                        <th>Thumbnail</th>
                        <th>Titel</th>
                        <th></th>
                        <th>Beschrijving</th>
                        <th>Aanpassen</th>
                        <th>Verwijderen</th>
                    </tr>
                        @foreach($albums as $album)
                        <tr>
                            <td style="width: 10%">
                                @if($album->thumbnail)
                                    <img class="thumbnail" style="width: 100%" src="{{ asset('./images/thumbnail/'.$album->thumbnail) }}"/>
                                @else
                                    <img class="thumbnail" style="width: 100%" src="{{ asset('./images/thumbnail/placeholder.png') }}"/>
                                @endif
                            </td>
                            <td>{{ $album->title }}</td>
                            <td><a href="{{ route('photo.show', ['album_id' => $album->id]) }}">Foto's</a></td>
                            <td>{{ (strlen($album->description) >= 50 ) ? substr($album->description, 0 , 30)."..." : $album->description }}</td>
                            <td><a href="{{ route('album.edit', ['id' => $album->id]) }}">Aanpassen</a></td>
                            <td><a style="cursor: pointer" data-backdrop="false" data-url="{{ route('album.delete', ['id' => $album->id ]) }}" data-name="{{ $album->title }}" class="album-delete" data-target="#delete-modal" data-toggle="modal">Verwijderen</a></td>
                        </tr>
                        @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('.table').on('click', '.album-delete', function(){
            $('#form-delete').attr('action', $(this).data('url'));
            $('#delete-name').text($(this).data('name'));
        });
    </script>
@endsection