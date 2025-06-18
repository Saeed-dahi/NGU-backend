<?php

namespace App\Http\Requests\AdjustmentNote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustmentNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        if ($this->has('adjustment_note')) {
            $this->merge($this->input('adjustment_note'));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $adjustmentNote = $this->route('adjustment_note');
        return [
            'number' => [
                'numeric',
                Rule::unique('adjustment_notes')->where(fn($query) => $query->where('type', $this->input('type')))
                    ->ignore($adjustmentNote),
            ],
            'document_number' => 'nullable|string|max:255',
            'type' => 'required|in:debit,credit',
            'status' => 'required|in:draft,saved',
            'date' => 'required|date',
            // 'due_date' => 'date|after_or_equal:date',
            'description' => '',
            'sub_total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'primary_account_id' => 'required|exists:accounts,id',
            'secondary_account_id' => 'required|exists:accounts,id',
            'tax_account_id' => 'required|exists:accounts,id',
            'cheque_id' => 'nullable|exists:cheques,id'
        ];
    }
}
