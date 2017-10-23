<?php

namespace App\Http\Controllers\Admin;

use App\Album;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
{
    public function show(){
        // Variable to store all albums.
        $albums = Album::all();

        // Returning the view with the data.
        return view('admin.album', compact('albums'));
    }

    public function createAlbum(Request $request){

        // Checks if request is GET.
        if($request->isMethod("get")){
            return view('admin.albumCreate');
        }

        // Validates inputs
        $this->validate($request, [
            'title' => 'required|max:254|unique:albums,title',
            'description' => 'max:1000',
            'thumbnail' => 'mimes:jpeg,png'
        ],
        [
            'title.required' => "Titel is verplicht!",
            'title.max' => "Titel mag niet meer dan 254 tekens bevatten",
            'title.unique' => "De titel bestaat al! Kies een andere titel",
            'description' => 'Beschrijving mag niet meer dan 1000 tekens bevatten',
            'thumbnail.mimes' => 'Bestand type mag alleen van JPEGof PNG formaat zijn'
        ]);

        // Create new instance of Album
        $album = new Album();

        // Writes the values of the inputs to the database.
        $album->title = $request->input('title');

        $album->description = $request->input('description');

        // Checks if the input field thumbnail is has a file set.
        if($request->hasFile('thumbnail')){

            // Assign the field to $file variable
            $file = $request->file('thumbnail');

            // Create an variable for the file extension
            $extension = $file->getClientOriginalExtension();

            // Create a variable for the destination where the file has to be uploaded
            $destination = public_path()."/images/thumbnail";

            // Create a new name using a unique id with the extension
            $file_name = uniqid().".".$extension;

            // Assign the file name to the thumbnail field
            $album->thumbnail = $file_name;

            // Move the file to destinatiom
            $file->move($destination, $file_name);
        }

        // Last but not least, we create a folder within the albums folder to store pictures.
        File::makeDirectory(public_path().'/albums/'.$album->title.'/', 0777, true);

        // Save new album
        $album->save();

        // Return to album list.
        return redirect(route('album.show'))->with('successMsg', 'Aanmaken van album is gelukt!');
    }

    public function editAlbum(Request $request, $id){
        // Try finding the id of the album
        try {
            $album = Album::findOrFail($id);
        } catch(ModelNotFoundException $e){
            // If album is not found send back to album list with error
            return redirect(route('album.show'))->with('errorMsg', "Album niet gevonden");
        }

        // Check if request is get
        if($request->isMethod('get')){
            return view('admin.albumEdit', compact('album'));
        }

        // Check if validation of input.
        // The album id for unqiue is used to ignore the title if not changed,
        // This way no validation error will be thrown at us :)
        $this->validate($request, [
            'title' => 'required|max:254|unique:albums,title,'.$album->id,
            'description' => 'max:1000',
            'thumbnail' => 'mimes:jpeg,png'
        ],
        [
            'title.required' => "Titel is verplicht!",
            'title.max' => "Titel mag niet meer dan 254 tekens bevatten",
            'title.unique' => "De titel bestaat al! Kies een andere titel",
            'description' => 'Beschrijving mag niet meer dan 1000 tekens bevatten',
            'thumbnail.mimes' => 'Bestand type mag alleen van JPEGof PNG formaat zijn'
        ]);

        // Check if folder exists
        if(file_exists(public_path()."/albums/".$album->title."/")) {

            // Variables storing old path, and new path.
            $old_path = public_path() . "/albums/" . $album->title . "/";
            $new_path = public_path() . "/albums/" . $request->input('title') . "/";

            // Rename old folder name with the new one.
            rename($old_path, $new_path);
        }

        // Overwrite old data with new one
        $album->title = $request->input('title');
        $album->description = $request->input('description');


        // If thumnail field is not set return null
        if(!$request->hasFile('thumbnail')){
            // Do nothing!
        } else {
            // If file exists delete it.
            if(file_exists(public_path()."/images/thumbnail/".$album->thumbnail) && $album->thumbnail != NULL){
                unlink(public_path()."/images/thumbnail/".$album->thumbnail);
            }

            // Assign the field to $file variable
            $file = $request->file('thumbnail');

            // Create an variable for the file extension
            $extension = $file->getClientOriginalExtension();

            // Create a variable for the destination where the file has to be uploaded
            $destination = public_path()."/images/thumbnail";

            // Create a new name using a unique id with the extension
            $file_name = uniqid().".".$extension;

            // Assign the file name to the thumbnail field
            $album->thumbnail = $file_name;

            // Move the file to destinatiom
            $file->move($destination, $file_name);
        }

        // Save album
        $album->save();

        // Return the album list
        return redirect(route('album.show'))->with('successMsg', "Het album is aangepast!");

    }

    public function deleteAlbum($id){
        // Try finding the album by id
        try {
            $album = Album::findOrFail($id);
        } catch(ModelNotFoundException $e){
            // If failed, redirect back to album list
            return redirect(route('album.show'))->with('errorMsg', 'Album niet gevonden');
        }

        // If record has an thumbnail and not equal to NULL, delete it.
        if(file_exists(public_path()."/images/thumbnail/".$album->thumbnail) && $album->thumbnail != NULL){
            unlink(public_path()."/images/thumbnail/".$album->thumbnail);
        }

        // Delete the folder for this album
        if(file_exists(public_path().'/albums/'.$album->title.'/')){
            File::deleteDirectory(public_path().'/albums/'.$album->title.'/');
        }

        // Delete album
        $album->delete();

        // Redirect back to album list
        return redirect(route('album.show'))->with('successMsg', 'Verwijderen van album gelukt');
    }
}
