@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600">Pengaturan sistem</p>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <form class="bg-white rounded-lg shadow p-6 space-y-4">
        <div>
            <label class="block font-medium mb-1">Nama Aplikasi</label>
            <input type="text" name="app_name" value="{{ config('app.name') }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Logo Aplikasi</label>
            <div class="flex items-center gap-4">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-16 h-16 object-contain bg-gray-100 rounded border">
                <input type="file" name="logo" class="border rounded px-3 py-2 w-full" disabled>
            </div>
        </div>
        <div>
            <label class="block font-medium mb-1">Email Pengirim</label>
            <input type="email" name="mail_from" value="{{ config('mail.from.address') }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Timezone</label>
            <input type="text" name="timezone" value="{{ config('app.timezone') }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Bahasa Default</label>
            <select name="locale" class="w-full border rounded px-3 py-2" disabled>
                <option value="id" {{ config('app.locale') == 'id' ? 'selected' : '' }}>Indonesia</option>
                <option value="en" {{ config('app.locale') == 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1">Format Tanggal/Waktu</label>
            <input type="text" name="date_format" value="d M Y H:i" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Alamat Toko</label>
            <textarea name="store_address" class="w-full border rounded px-3 py-2" rows="2" disabled>Jl. Contoh Alamat No. 123, Kota, Negara</textarea>
        </div>
        <div>
            <label class="block font-medium mb-1">Nomor Telepon</label>
            <input type="text" name="phone" value="08123456789" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">WhatsApp</label>
            <input type="text" name="whatsapp" value="08123456789" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Link Sosial Media</label>
            <div class="flex flex-col gap-2">
                <input type="text" name="facebook" value="https://facebook.com/toko" class="w-full border rounded px-3 py-2" placeholder="Facebook" disabled>
                <input type="text" name="instagram" value="https://instagram.com/toko" class="w-full border rounded px-3 py-2" placeholder="Instagram" disabled>
                <input type="text" name="twitter" value="https://twitter.com/toko" class="w-full border rounded px-3 py-2" placeholder="Twitter" disabled>
            </div>
        </div>
        <div>
            <label class="block font-medium mb-1">Custom Footer Text</label>
            <input type="text" name="footer_text" value="&copy; {{ date('Y') }} Toko Sepatu. All rights reserved." class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-medium mb-1">Maintenance Mode</label>
            <select name="maintenance" class="w-full border rounded px-3 py-2" disabled>
                <option value="0">Nonaktif</option>
                <option value="1">Aktif</option>
            </select>
        </div>
        <div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 opacity-50 cursor-not-allowed">Simpan (Demo)</button>
        </div>
    </form>
</div>
@endsection 