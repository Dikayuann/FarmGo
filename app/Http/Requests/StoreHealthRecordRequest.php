<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthRecordRequest extends FormRequest
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
            'animal_id' => 'required|exists:animals,id',
            'tanggal_pemeriksaan' => 'required|date|before_or_equal:now',
            'jenis_pemeriksaan' => 'required|in:rutin,darurat,follow_up',
            'berat_badan' => 'required|numeric|min:0',
            'suhu_tubuh' => 'nullable|numeric|min:0|max:50',
            'status_kesehatan' => 'required|in:sehat,sakit,dalam_perawatan,sembuh',
            'gejala' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'obat' => 'nullable|string|max:255',
            'biaya' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'pemeriksaan_berikutnya' => 'nullable|date|after:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'animal_id.required' => 'Hewan harus dipilih.',
            'animal_id.exists' => 'Hewan yang dipilih tidak valid.',
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan harus diisi.',
            'tanggal_pemeriksaan.date' => 'Format tanggal tidak valid.',
            'jenis_pemeriksaan.required' => 'Jenis pemeriksaan harus dipilih.',
            'jenis_pemeriksaan.in' => 'Jenis pemeriksaan tidak valid.',
            'berat_badan.required' => 'Berat badan harus diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'berat_badan.min' => 'Berat badan tidak boleh negatif.',
            'suhu_tubuh.numeric' => 'Suhu tubuh harus berupa angka.',
            'suhu_tubuh.min' => 'Suhu tubuh tidak boleh negatif.',
            'suhu_tubuh.max' => 'Suhu tubuh tidak valid.',
            'status_kesehatan.required' => 'Status kesehatan harus dipilih.',
            'status_kesehatan.in' => 'Status kesehatan tidak valid.',
            'obat.max' => 'Nama obat terlalu panjang.',
            'biaya.numeric' => 'Biaya harus berupa angka.',
            'biaya.min' => 'Biaya tidak boleh negatif.',
            'pemeriksaan_berikutnya.date' => 'Format tanggal tidak valid.',
            'pemeriksaan_berikutnya.after' => 'Tanggal pemeriksaan berikutnya harus setelah hari ini.',
        ];
    }
}
