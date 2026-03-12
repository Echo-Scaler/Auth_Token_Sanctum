<?php

namespace App\Http\Controllers;
use App\Http\Requests\NoteStoreRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $query = Note::where('user_id',$request->user()->id)->latest();

            if($request->has('search')){
                $searchTerm = $request['search'];

                $query->where(function ($q) use ($searchTerm){
                    $q->where('title','like','%'.$searchTerm.'%')
                      ->orWhere('description','like','%'.$searchTerm.'%');
                });
            }
            $notes = $query->paginate(10);

            return response()->json([
                'message' => 'Note lists retrieved successfully',
                'data' => $notes->items(),
                'total' => $notes->total(),
                'current_page' => $notes->currentPage(),
                'per_page' => $notes->perPage(),
            ]);
        }catch(Exception $e){
           return response()->json([
                'message' => 'Internal Server Error',
                // 'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteStoreRequest $request)
    {
        try{
            $note = Note::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => request()->user()->id,
            ]);
            return response()->json([
                'message' => 'Data create successfully',
                'data' => $note,
            ],201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        try{
            if($note->user_id !== request()->user()->id){
                return response()->json([
                    'message' => 'Unauthorized'],403);
            }
            return response()->json([
                'message' => 'Note retrieved successfully',
                'data' => $note,
            ],200);
        }catch(Exception $e){
             return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        try{
            if($note->user_id !== request()->user()->id){
                return response()->json([
                    'message' => 'Unauthorized'],403);
            }
            $note->update($request->validated());

            return response()->json([
                'message' => 'Note updated successfully',
                'data' => $note,
            ],200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try{
            if($note->user_id !== request()->user()->id){
                return response()->json([
                    'message'=>'Unauthorized'
                ],403);
            }
            $note->delete();
            return response()->json([
                'message' => 'Note deleted successfully'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
