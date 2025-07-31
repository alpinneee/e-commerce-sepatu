@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-900">Tambah Produk</h1>
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali</a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow space-y-3">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-2 py-1.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" class="w-full border rounded px-2 py-1.5 text-sm" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Harga</label>
                <input type="number" name="price" value="{{ old('price') }}" class="w-full border rounded px-2 py-1.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock') }}" class="w-full border rounded px-2 py-1.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="w-full border rounded px-2 py-1.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Berat (gram)</label>
                <input type="number" name="weight" value="{{ old('weight') }}" class="w-full border rounded px-2 py-1.5 text-sm" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="is_active" class="border rounded px-2 py-1.5 text-sm">
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Gambar Produk</label>
            <input type="file" name="images[]" multiple class="w-full border rounded px-2 py-1.5 text-sm">
        </div>
        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 bg-gray-300 text-gray-800 rounded text-sm hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection 