<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

trait SharedFunctions
{
    /**
     * To get next, previous, first and list record of specific record
     */
    public function navigateRecord(Model $model, Request $request, $column = 'id')
    {
        $direction = $request->input('direction');
        $record = $model;

        switch ($direction) {
            case 'next':
                $record = $model::where($column, '>', $model->$column)->first() ?? $model::first();
                break;
            case 'previous':
                $record = $model::where($column, '<', $model->$column)->latest($column)->first() ?? $model::latest($column)->first();
                break;
            case 'first':
                $record = $model::first();
                break;
            case 'last':
                $record = $model::latest($column)->first();
                break;
        }

        return $record;
    }

    /**
     * Custom date format
     */
    public function customDateFormat($date)
    {
        return $date->format('Y-m-d h:m');
    }

    /**
     * upload multiple files
     */
    public function uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path)
    {
        info(request());
        $originalModelValue = $this->getOriginal()[$attribute_name] ?? [];

        if (! is_array($originalModelValue)) {
            $attribute_value = json_decode($originalModelValue, true) ?? [];
        } else {
            $attribute_value = $originalModelValue;
        }

        $files_to_delete = request()->get('files_to_delete');
        if ($files_to_delete) {
            $files_to_delete_array = explode(',', $files_to_delete);
            foreach ($files_to_delete_array as $filename) {
                Storage::disk($disk)->delete($filename);
                $attribute_value = Arr::where($attribute_value, function ($value, $key) use ($filename) {
                    return $value != $filename;
                });
            }
        }

        // if a new file is uploaded, store it on disk and its filename in the database
        if (request()->hasFile($attribute_name)) {
            foreach (request()->file($attribute_name) as $file) {
                if ($file->isValid()) {
                    // Get the original name of the uploaded file
                    $original_file_name = str_replace(' ', '_', $file->getClientOriginalName());
                    info('Original file name: ' . $original_file_name);

                    // 1. Generate a new file name
                    // $new_file_name = md5($original_file_name) . '.' . $file->getClientOriginalExtension();
                    $new_file_name = $original_file_name . '.' . $file->getClientOriginalExtension();


                    // 2. Move the new file to the correct path
                    $file_path = $file->storeAs($destination_path, $new_file_name, $disk);

                    // 3. Add the public path to the database
                    $attribute_value[] = $file_path;
                }
            }
        }
        $this->attributes[$attribute_name] = json_encode($attribute_value);
    }
}
