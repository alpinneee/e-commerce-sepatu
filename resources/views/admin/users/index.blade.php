@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-gray-600">Kelola pengguna sistem</p>
        </div>
        <a href="#" class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition font-semibold opacity-50 cursor-not-allowed">+ Tambah User</a>
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
        <select name="role" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="border rounded px-3 py-2 focus:ring focus:ring-blue-200" autocomplete="off">
        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Filter</button>
    </form>

    <!-- Tabel Users -->
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border-b text-left">#</th>
                    <th class="px-4 py-3 border-b text-left">Nama</th>
                    <th class="px-4 py-3 border-b text-left">Email</th>
                    <th class="px-4 py-3 border-b text-left">Role</th>
                    <th class="px-4 py-3 border-b text-left">Tanggal Daftar</th>
                    <th class="px-4 py-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-4 py-2 border-b align-middle">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td class="px-4 py-2 border-b align-middle font-semibold text-gray-900">{{ $user->name }}</td>
                    <td class="px-4 py-2 border-b align-middle">{{ $user->email }}</td>
                    <td class="px-4 py-2 border-b align-middle">
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded text-xs font-semibold mr-1">{{ ucfirst($role) }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 border-b align-middle">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2 border-b align-middle text-center">
                        <div class="flex justify-center gap-2">
                            <a href="#" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition text-xs font-semibold opacity-50 cursor-not-allowed">Edit</a>
                            <form action="#" method="POST" onsubmit="return false;">
                                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-semibold opacity-50 cursor-not-allowed">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Tidak ada user ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection 