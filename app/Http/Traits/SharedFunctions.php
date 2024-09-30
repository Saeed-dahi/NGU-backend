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
        $originalModelValue = $this->getOriginal()[$attribute_name] ?? [];

        if (! is_array($originalModelValue)) {
            $attribute_value = json_decode($originalModelValue, true) ?? [];
        } else {
            $attribute_value = $originalModelValue;
        }

        $files_to_clear = request()->get('clear_' . $attribute_name);

        // if a file has been marked for removal,
        // delete it from the disk and from the db
        if ($files_to_clear) {
            foreach ($files_to_clear as $key => $filename) {
                Storage::disk($disk)->delete($filename);
                $attribute_value = Arr::where($attribute_value, function ($value, $key) use ($filename) {
                    return $value != $filename;
                });
            }
        }
        // to delete all file from db and disk
        if ($files_to_clear[0] == 'all') {
            Storage::disk($disk)->deleteDirectory($destination_path);
            $attribute_value = [];
        }

        // if a new file is uploaded, store it on disk and its filename in the database
        if (request()->hasFile($attribute_name)) {
            foreach (request()->file($attribute_name) as $file) {
                if ($file->isValid()) {
                    // 1. Generate a new file name
                    $new_file_name = md5($file->getClientOriginalName() . random_int(1, 9999) . time()) . '.' . $file->getClientOriginalExtension();

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
