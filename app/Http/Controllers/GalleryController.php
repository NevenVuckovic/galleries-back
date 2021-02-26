<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditGalleryRequest;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $galleriesQuery = Gallery::query();
        $galleriesQuery->with('user', 'images');
        $search = $request->header('searchText');
        $galleriesQuery->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orwhereHas('user', function ($que) use ($search) {
                    $que->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
        });

        $galleries = $galleriesQuery->take($request->header('pagination'))
            ->get();

        $count = $galleriesQuery->count();

        return [$galleries, $count];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();
        $user = User::findOrFail($user->id);
        $gallery = Gallery::create([
            "title" => $data["title"],
            "description" => $data["description"],
            "user_id" => $user['id'],
        ]);
        foreach ($data['images'] as $images) {
            foreach ($images as $url) {
                $gallery->addImages($url, $gallery['id']);
            }
        }
        return $gallery;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::with('images', 'user', 'comments', 'comments.user')->findOrFail($id);
        return response()->json($gallery);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditGalleryRequest $request, $id)
    {
        $data = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $user = auth('api')->user();
        if ($user->id === $gallery->user_id) {
            $gallery->update($data);
            foreach ($data['images'] as $images) {
                foreach ($images as $url) {
                    $gallery->editGalleryImages($url, $gallery['id']);
                }
            }
        }
        return $gallery;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::with('images', 'comments')->findOrFail($id);
        $user = auth('api')->user();
        if ($user->id === $gallery->user_id) {
            $gallery->images()->delete();
            $gallery->comments()->delete();
            $gallery->delete();
        }
        return $gallery;

    }
}
