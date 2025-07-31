@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-900">Products</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="px-3 py-1.5 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition">+ Kategori</a>
            <a href="{{ route('admin.products.create') }}" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">+ Produk</a>
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
    <form method="GET" class="flex gap-2 items-center bg-white p-3 rounded shadow">
        <select name="category" class="border rounded px-2 py-1 text-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="border rounded px-2 py-1 text-sm" autocomplete="off">
        <select name="status" class="border rounded px-2 py-1 text-sm">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="px-3 py-1 bg-gray-700 text-white rounded text-sm hover:bg-gray-800 transition">Filter</button>
    </form>

    <!-- Tabel Produk -->
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">#</th>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Nama</th>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Kategori</th>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Harga</th>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Stok</th>
                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-3 py-2 border-b text-center text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 border-b text-sm">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td class="px-3 py-2 border-b text-sm font-medium">{{ $product->name }}</td>
                    <td class="px-3 py-2 border-b text-sm">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-3 py-2 border-b text-sm">Rp {{ number_format($product->price) }}</td>
                    <td class="px-3 py-2 border-b text-sm">{{ $product->stock }}</td>
                    <td class="px-3 py-2 border-b">
                        @if($product->is_active)
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 border-b text-center">
                        <div class="flex justify-center gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-6 text-sm">Tidak ada produk ditemukan.</td>
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