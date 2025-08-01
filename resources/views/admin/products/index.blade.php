@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
            <p class="text-gray-600">Kelola produk di toko sepatu</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="inline-block px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition font-semibold">+ Tambah Kategori</a>
            <a href="{{ route('admin.products.create') }}" class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition font-semibold">+ Tambah Produk</a>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center bg-white p-4 rounded shadow">
        <select name="category" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="border rounded px-3 py-2 focus:ring focus:ring-blue-200" autocomplete="off">
        <select name="status" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Filter</button>
    </form>

    <!-- Tabel Produk -->
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border-b text-left">#</th>
                    <th class="px-4 py-3 border-b text-left">Nama</th>
                    <th class="px-4 py-3 border-b text-left">Kategori</th>
                    <th class="px-4 py-3 border-b text-left">Harga</th>
                    <th class="px-4 py-3 border-b text-left">Stok</th>
                    <th class="px-4 py-3 border-b text-left">Status</th>
                    <th class="px-4 py-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-4 py-2 border-b align-middle">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td class="px-4 py-2 border-b align-middle font-semibold text-gray-900">{{ $product->name }}</td>
                    <td class="px-4 py-2 border-b align-middle">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-2 border-b align-middle">Rp {{ number_format($product->price) }}</td>
                    <td class="px-4 py-2 border-b align-middle">{{ $product->stock }}</td>
                    <td class="px-4 py-2 border-b align-middle">
                        @if($product->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border-b align-middle text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition text-xs font-semibold">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-8">Tidak ada produk ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection 