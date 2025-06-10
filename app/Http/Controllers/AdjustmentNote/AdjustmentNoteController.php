<?php

namespace App\Http\Controllers\AdjustmentNote;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustmentNote\AdjustmentNoteRequest;
use App\Http\Resources\AdjustmentNote\AdjustmentNoteResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\AdjustmentNote\AdjustmentNote;
use Illuminate\Http\Request;

class AdjustmentNoteController extends Controller
{
    use SharedFunctions, ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $AdjustmentNotes = AdjustmentNote::where('type', $request->type)->get();

        return $this->success(AdjustmentNoteResource::collection($AdjustmentNotes));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdjustmentNoteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AdjustmentNote $AdjustmentNote)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AdjustmentNoteRequest $request, AdjustmentNote $AdjustmentNote)
    {
        //
    }
}
