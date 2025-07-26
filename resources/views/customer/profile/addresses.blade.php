@extends('layouts.customer')

@section('title', 'Shipping Addresses - Toko Sepatu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Shipping Addresses</h1>
                    <p class="text-gray-600 mt-2">Manage your delivery addresses</p>
                </div>
                <button onclick="openAddAddressModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Add New Address
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white rounded-lg shadow-md p-6 border-2 {{ $address->is_default ? 'border-blue-500' : 'border-gray-200' }}">
                        @if($address->is_default)
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Default Address
                                </span>
                            </div>
                        @endif
                        
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $address->name }}</h3>
                            <p class="text-gray-600">{{ $address->phone }}</p>
                            <p class="text-gray-600">{{ $address->address }}</p>
                            <p class="text-gray-600">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                            <button onclick="openEditAddressModal({{ $address->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </button>
                            @if(!$address->is_default)
                                <form action="{{ route('profile.addresses.update', $address) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_default" value="1">
                                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Set as Default
                                    </button>
                                </form>
                                <form action="{{ route('profile.addresses.delete', $address) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No addresses yet</h3>
                <p class="mt-2 text-gray-600">Add your first shipping address to make checkout faster.</p>
                <div class="mt-6">
                    <button onclick="openAddAddressModal()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Add Address
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Address</h3>
            <form action="{{ route('profile.addresses.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" id="phone" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea id="address" name="address" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="city" name="city" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" id="province" name="province" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_default" name="is_default" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as default address</label>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddAddressModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Add Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Address</h3>
            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="edit_name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" id="edit_phone" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea id="edit_address" name="address" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="edit_city" name="city" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_province" class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" id="edit_province" name="province" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" id="edit_postal_code" name="postal_code" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="edit_is_default" name="is_default" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="edit_is_default" class="ml-2 block text-sm text-gray-900">Set as default address</label>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditAddressModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddAddressModal() {
    document.getElementById('addAddressModal').classList.remove('hidden');
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
}

function openEditAddressModal(addressId) {
    // Here you would typically fetch the address data via AJAX
    // For now, we'll just show the modal with a placeholder form
    document.getElementById('editAddressForm').action = `/profile/addresses/${addressId}`;
    document.getElementById('editAddressModal').classList.remove('hidden');
}

function closeEditAddressModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addAddressModal');
    const editModal = document.getElementById('editAddressModal');
    
    if (event.target === addModal) {
        closeAddAddressModal();
    }
    if (event.target === editModal) {
        closeEditAddressModal();
    }
}
</script>
@endsection 