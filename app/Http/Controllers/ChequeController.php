<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChequeResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Cheque;
use Illuminate\Http\Request;

class ChequeController extends Controller
{
    use ApiResponser, SharedFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cheques = Cheque::all();
        return $this->success(ChequeResource::collection($cheques));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $account = $id == 1 ? Cheque::firstOrFail() : Cheque::findOrFail($id);
        $account = $this->navigateRecord($account, $request);

        return $this->success(ChequeResource::make($account));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cheque $cheque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cheque $cheque)
    {
        //
    }
}
