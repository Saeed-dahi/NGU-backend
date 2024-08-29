<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait SharedFunctions
{
    public function navigateRecord(Model $model, Request $request)
    {
        $direction = $request->input('direction');
        $record = $model;

        switch ($direction) {

            case 'next':
                $record = $model::where('id', '>', $model->id)->first() ?? $model::first();
                break;
            case 'previous':
                $record = $model::where('id', '<', $model->id)->latest('id')->first() ?? $model::latest('id')->first();
                break;
            case 'first':
                $record = $model::first();
                break;
            case 'last':
                $record = $model::latest('id')->first();
                break;
        }

        return $record;
    }

    public function customDateFormat($date)
    {
        return $date->format('Y-m-d h:m');
    }
}
