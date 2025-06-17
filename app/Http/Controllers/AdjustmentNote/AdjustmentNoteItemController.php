<?php

namespace App\Http\Controllers\AdjustmentNote;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustmentNote\PreviewAdjustmentNoteItemRequest;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Services\AdjustmentNote\AdjustmentNoteItemsService;

class AdjustmentNoteItemController extends Controller
{
    use ApiResponser;
    protected $adjustmentNoteItemsServices;
    public function __construct(AdjustmentNoteItemsService $adjustmentNoteItemsServices)
    {
        $this->adjustmentNoteItemsServices = $adjustmentNoteItemsServices;
    }


    function previewAdjustmentNoteItem(PreviewAdjustmentNoteItemRequest $previewAdjustmentNoteItemRequest)
    {
        $data = $this->adjustmentNoteItemsServices->previewAdjustmentNoteItem($previewAdjustmentNoteItemRequest);

        return $this->success($data);
    }
}
