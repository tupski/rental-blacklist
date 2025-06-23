<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BuktiFileRule;
use App\Models\Attribute;

class StoreBlacklistReportRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            // Data Penyewa
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'nullable|string|size:16',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:1000',
            'foto_penyewa.*' => ['nullable', 'file', new BuktiFileRule()],
            'foto_ktp_sim.*' => ['nullable', 'file', new BuktiFileRule()],

            // Detail Masalah
            'jenis_rental' => 'required|string',
            'jenis_laporan' => 'required|array|min:1',
            'jenis_laporan.*' => 'string',
            'jenis_laporan_lainnya' => 'nullable|string|max:255',
            'tanggal_sewa' => 'required|date|before_or_equal:today',
            'tanggal_kejadian' => 'required|date|before_or_equal:today|after_or_equal:tanggal_sewa',
            'jenis_kendaraan' => 'required|string|max:255',
            'nomor_polisi' => 'nullable|string|max:20',
            'nilai_kerugian' => 'nullable|numeric|min:0|max:999999999999.99',
            'kronologi' => 'required|string|min:50',
            'bukti.*' => ['nullable', 'file', new BuktiFileRule()],

            // Status Penanganan
            'status_penanganan' => 'required|array|min:1',
            'status_penanganan.*' => 'string',
            'status_penanganan_lainnya' => 'nullable|string|max:255',

            // Persetujuan
            'persetujuan' => 'required|accepted',
            'nama_pelapor_ttd' => 'required|string|max:255',
        ];

        // Jika user tidak login (guest), tambah validasi pelapor
        if (!auth()->check()) {
            $rules = array_merge($rules, [
                'nama_perusahaan_rental' => 'required|string|max:255',
                'nama_penanggung_jawab' => 'required|string|max:255',
                'no_wa_pelapor' => 'required|string|max:20',
                'email_pelapor' => 'required|email|max:255',
                'alamat_usaha' => 'nullable|string|max:1000',
                'website_usaha' => 'nullable|url|max:255',
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap penyewa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'nik.size' => 'NIK harus terdiri dari 16 digit.',
            'no_hp.required' => 'Nomor telepon/WhatsApp penyewa wajib diisi.',
            'jenis_rental.required' => 'Kategori rental wajib dipilih.',
            'jenis_rental.in' => 'Kategori rental tidak valid.',
            'jenis_laporan.required' => 'Jenis masalah wajib dipilih.',
            'jenis_laporan.min' => 'Pilih minimal satu jenis masalah.',
            'tanggal_sewa.required' => 'Tanggal sewa wajib diisi.',
            'tanggal_sewa.before_or_equal' => 'Tanggal sewa tidak boleh lebih dari hari ini.',
            'tanggal_kejadian.required' => 'Tanggal kejadian masalah wajib diisi.',
            'tanggal_kejadian.after_or_equal' => 'Tanggal kejadian tidak boleh sebelum tanggal sewa.',
            'jenis_kendaraan.required' => 'Jenis kendaraan/barang yang disewa wajib diisi.',
            'nilai_kerugian.numeric' => 'Nilai kerugian harus berupa angka.',
            'nilai_kerugian.max' => 'Nilai kerugian terlalu besar.',
            'kronologi.required' => 'Deskripsi kejadian wajib diisi.',
            'kronologi.min' => 'Deskripsi kejadian minimal 50 karakter.',
            'status_penanganan.required' => 'Status penanganan wajib dipilih.',
            'status_penanganan.min' => 'Pilih minimal satu status penanganan.',
            'status_lainnya.required_if' => 'Keterangan lainnya wajib diisi jika memilih "Lainnya".',
            'persetujuan.required' => 'Persetujuan wajib dicentang.',
            'persetujuan.accepted' => 'Anda harus menyetujui pernyataan untuk melanjutkan.',
            'nama_pelapor_ttd.required' => 'Nama lengkap pelapor untuk tanda tangan wajib diisi.',

            // Guest reporter validation messages
            'nama_perusahaan_rental.required' => 'Nama perusahaan rental wajib diisi.',
            'nama_penanggung_jawab.required' => 'Nama penanggung jawab wajib diisi.',
            'no_wa_pelapor.required' => 'Nomor WhatsApp/telepon pelapor wajib diisi.',
            'email_pelapor.required' => 'Email pelapor wajib diisi.',
            'email_pelapor.email' => 'Format email tidak valid.',
            'website_usaha.url' => 'Format website/Instagram tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap penyewa',
            'jenis_kelamin' => 'jenis kelamin',
            'nik' => 'nomor KTP',
            'no_hp' => 'nomor telepon/WhatsApp',
            'foto_penyewa.*' => 'foto penyewa',
            'foto_ktp_sim.*' => 'foto KTP/SIM',
            'jenis_rental' => 'kategori rental',
            'jenis_laporan' => 'jenis masalah',
            'tanggal_sewa' => 'tanggal sewa',
            'tanggal_kejadian' => 'tanggal kejadian',
            'jenis_kendaraan' => 'jenis kendaraan/barang',
            'nomor_polisi' => 'nomor polisi',
            'nilai_kerugian' => 'nilai kerugian',
            'kronologi' => 'deskripsi kejadian',
            'bukti.*' => 'bukti pendukung',
            'status_penanganan' => 'status penanganan',
            'status_lainnya' => 'keterangan lainnya',
            'persetujuan' => 'persetujuan',
            'nama_pelapor_ttd' => 'nama pelapor',
            'nama_perusahaan_rental' => 'nama perusahaan rental',
            'nama_penanggung_jawab' => 'nama penanggung jawab',
            'no_wa_pelapor' => 'nomor WhatsApp pelapor',
            'email_pelapor' => 'email pelapor',
            'alamat_usaha' => 'alamat usaha',
            'website_usaha' => 'website/Instagram usaha',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set tanggal pelaporan to now
        $this->merge([
            'tanggal_pelaporan' => now(),
            'tipe_pelapor' => auth()->check() ? 'rental' : 'guest'
        ]);

        // Normalize phone numbers
        if ($this->has('no_hp')) {
            $this->merge([
                'no_hp' => \App\Helpers\PhoneHelper::normalize($this->no_hp)
            ]);
        }

        if ($this->has('no_wa_pelapor')) {
            $this->merge([
                'no_wa_pelapor' => \App\Helpers\PhoneHelper::normalize($this->no_wa_pelapor)
            ]);
        }
    }
}
