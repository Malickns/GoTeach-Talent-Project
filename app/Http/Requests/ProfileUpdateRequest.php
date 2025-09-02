<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'preferences_emploi' => ['nullable','string','max:1000'],
            'types_contrat_preferes' => ['nullable','array'],
            'types_contrat_preferes.*' => ['in:cdi,cdd,stage,formation,freelance,autre'],
            'secteurs_preferes' => ['nullable','array'],
            'secteurs_preferes.*' => ['string','max:100'],
        ];
    }
}
