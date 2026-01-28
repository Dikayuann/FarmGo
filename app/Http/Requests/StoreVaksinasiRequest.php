<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVaksinasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'animal_id' => [
                'required',
                'exists:animals,id',
                function ($attribute, $value, $fail) {
                    $animal = \App\Models\Animal::find($value);
                    if (!$animal || $animal->user_id !== Auth::id()) {
                        $fail('Hewan yang dipilih tidak valid atau bukan milik Anda.');
                    }
                },
            ],
            'tanggal_vaksin' => 'required|date|before_or_equal:today',
            'jenis_vaksin' => 'required|string|max:255',
            'dosis' => 'required|string|max:255',
            'rute_pemberian' => 'required|in:oral,injeksi_im,injeksi_sc,injeksi_iv',
            'masa_penarikan' => 'required|integer|min:0',
            'nama_dokter' => 'required|string|max:255',
            'jadwal_berikutnya' => 'nullable|date|after:today',
            'catatan' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'animal_id.required' => 'Hewan harus dipilih.',
            'animal_id.exists' => 'Hewan yang dipilih tidak ditemukan.',
            'tanggal_vaksin.required' => 'Tanggal vaksinasi harus diisi.',
            'tanggal_vaksin.date' => 'Format tanggal vaksinasi tidak valid.',
            'tanggal_vaksin.before_or_equal' => 'Tanggal vaksinasi tidak boleh di masa depan.',
            'jenis_vaksin.required' => 'Jenis vaksin harus diisi.',
            'jenis_vaksin.max' => 'Jenis vaksin maksimal 255 karakter.',
            'dosis.required' => 'Dosis harus diisi.',
            'dosis.max' => 'Dosis maksimal 255 karakter.',
            'rute_pemberian.required' => 'Rute pemberian harus dipilih.',
            'rute_pemberian.in' => 'Rute pemberian tidak valid.',
            'masa_penarikan.required' => 'Masa penarikan harus diisi.',
            'masa_penarikan.integer' => 'Masa penarikan harus berupa angka.',
            'masa_penarikan.min' => 'Masa penarikan minimal 0 hari.',
            'nama_dokter.required' => 'Nama dokter harus diisi.',
            'nama_dokter.max' => 'Nama dokter maksimal 255 karakter.',
            'jadwal_berikutnya.date' => 'Format jadwal berikutnya tidak valid.',
            'jadwal_berikutnya.after' => 'Jadwal berikutnya harus setelah hari ini.',
        ];
    }
}
