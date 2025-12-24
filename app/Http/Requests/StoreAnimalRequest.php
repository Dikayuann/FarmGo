<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User harus sudah login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $animalId = $this->route('ternak'); // For update request

        return [
            'kode_hewan' => [
                'nullable',
                'string',
                'max:20',
                'unique:animals,kode_hewan,' . ($animalId ?: 'NULL') . ',id,user_id,' . auth()->id()
            ],
            'nama_hewan' => 'required|string|max:255',
            'jenis_hewan' => 'required|in:sapi,kambing,domba',
            'ras_hewan' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'berat_badan' => 'required|numeric|min:0|max:9999.99',
            'status_kesehatan' => 'required|in:sehat,sakit,karantina',
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'kode_hewan' => 'kode hewan',
            'nama_hewan' => 'nama hewan',
            'jenis_hewan' => 'jenis hewan',
            'ras_hewan' => 'ras hewan',
            'tanggal_lahir' => 'tanggal lahir',
            'j enis_kelamin' => 'jenis kelamin',
            'berat_badan' => 'berat badan',
            'status_kesehatan' => 'status kesehatan',
        ];
    }
}
