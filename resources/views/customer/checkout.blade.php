@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Checkout</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-fade-in">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Ringkasan Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">
                                Rp {{ number_format($item->product->discount_price ?? $item->product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Total: Rp {{ number_format(($item->product->discount_price ?? $item->product->price) * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-slide-up animation-delay-2000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Alamat Pengiriman</h2>
                
                @if(\Illuminate\Support\Facades\Auth::check() && $shippingAddresses->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alamat Tersimpan</label>
                    <select id="saved-address" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih alamat tersimpan</option>
                        @foreach($shippingAddresses as $address)
                        <option value="{{ $address->id }}" 
                                data-name="{{ $address->name }}"
                                data-phone="{{ $address->phone }}"
                                data-address="{{ $address->address }}"
                                data-city="{{ $address->city }}"
                                data-province="{{ $address->province }}"
                                data-postal-code="{{ $address->postal_code }}">
                            {{ $address->name }} - {{ $address->full_address }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', \Illuminate\Support\Facades\Auth::user()?->email) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota *</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('province')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos *</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('postal_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if(\Illuminate\Support\Facades\Auth::check())
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="save_address" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Simpan alamat ini untuk pesanan selanjutnya</span>
                        </label>
                    </div>
                    @endif
            </div>

            <!-- Shipping Expedition -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-slide-up animation-delay-3000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Pilih Ekspedisi Pengiriman</h2>
                
                <div class="space-y-4">
                    <!-- JNE -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/92/New_Logo_JNE.png" alt="JNE" class="h-8 w-auto mr-3">
                            <span class="font-medium text-gray-900">JNE (Jalur Nugraha Ekakurir)</span>
                        </div>
                        <div class="p-4 space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jne_reg" class="text-blue-600 focus:ring-blue-500" data-cost="10000" data-estimation="2-3 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">JNE REG (Regular)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-3 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 10.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jne_yes" class="text-blue-600 focus:ring-blue-500" data-cost="15000" data-estimation="1-2 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">JNE YES (Yakin Esok Sampai)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 1-2 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 15.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                @error('shipping_expedition')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-md p-6 animate-slide-up animation-delay-4000">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan Pesanan</h3>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Catatan tambahan untuk pesanan">{{ old('notes') }}</textarea>
                </div>
                
                <!-- Payment Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <div>
                            <div class="font-medium text-blue-900">Pembayaran Online</div>
                            <div class="text-sm text-blue-700">Kartu Kredit, QRIS, E-Wallet, Bank Transfer, dan lainnya</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden payment method field -->
                <input type="hidden" name="payment_method" value="midtrans">
            </div>
            </form>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4 animate-slide-up animation-delay-4000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Total Pembayaran</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium" id="shipping-cost">Rp 0</span>
                    </div>
                    

                    
                    <hr class="my-3">
                    
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span id="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                @if($coupon)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <div class="font-medium text-green-800">Kupon Terpakai</div>
                            <div class="text-sm text-green-600">{{ $coupon->code }} - {{ $coupon->description }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Selected Payment & Shipping Info -->
                <div id="selected-info" class="mb-4" style="display: none;">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="text-sm text-blue-800">
                            <div id="selected-payment" class="font-medium"></div>
                            <div id="selected-shipping" class="mt-1"></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="processCheckout()"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        id="checkout-btn">
                    Proses Checkout
                </button>
                
                <!-- Midtrans Snap JS -->
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('cart.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const savedAddressSelect = document.getElementById('saved-address');
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const provinceInput = document.getElementById('province');
    const postalCodeInput = document.getElementById('postal_code');
    
    // Shipping tracking
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');
    const selectedInfo = document.getElementById('selected-info');
    const selectedPayment = document.getElementById('selected-payment');
    const selectedShipping = document.getElementById('selected-shipping');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    let currentShippingCost = 0;
    const baseTotal = {{ $total }};
    
    // Handle saved address selection
    if (savedAddressSelect) {
        savedAddressSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                nameInput.value = selectedOption.dataset.name;
                phoneInput.value = selectedOption.dataset.phone;
                addressInput.value = selectedOption.dataset.address;
                cityInput.value = selectedOption.dataset.city;
                provinceInput.value = selectedOption.dataset.province;
                postalCodeInput.value = selectedOption.dataset.postalCode;
            }
        });
    }
    
    // Handle shipping expedition selection
    const shippingRadios = document.querySelectorAll('input[name="shipping_expedition"]');
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                currentShippingCost = parseInt(this.dataset.cost);
                shippingCostElement.textContent = 'Rp ' + currentShippingCost.toLocaleString('id-ID');
                selectedShipping.textContent = this.parentElement.querySelector('.font-medium').textContent + ' - ' + this.dataset.estimation;
                updateTotal();
                updateSelectedInfo();
            }
        });
    });
    
    // Initialize payment method (Midtrans only)
    selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
    
    function updateTotal() {
        const newTotal = baseTotal + currentShippingCost;
        totalAmountElement.textContent = 'Rp ' + newTotal.toLocaleString('id-ID');
    }
    
    function updateSelectedInfo() {
        const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
        
        if (hasShipping) {
            selectedInfo.style.display = 'block';
        } else {
            selectedInfo.style.display = 'none';
        }
    }
    
    // Checkout button click handler
    window.processCheckout = function() {
        try {
            console.log('Checkout button clicked');
            
            const form = document.getElementById('checkout-form');
            if (!form) {
                console.error('Form not found!');
                alert('Form tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Validate required fields
            const requiredFields = ['name', 'email', 'phone', 'address', 'city', 'province', 'postal_code'];
            let missingFields = [];
            
            requiredFields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input || !input.value.trim()) {
                    missingFields.push(field);
                }
            });
            
            if (missingFields.length > 0) {
                alert('Silakan lengkapi field: ' + missingFields.join(', '));
                return;
            }
            
            const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
            
            if (!hasShipping) {
                alert('Silakan pilih ekspedisi pengiriman');
                return;
            }
            
            if (currentShippingCost <= 0) {
                alert('Ongkos kirim tidak valid. Silakan pilih ekspedisi pengiriman.');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('checkout-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Memproses...';
            }
            
            // Prepare form data
            const formData = new FormData(form);
            formData.append('shipping_cost', currentShippingCost);
            formData.append('cod_fee', 0);
            
            // Send AJAX request to create order and get snap token
            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success && data.snap_token) {
                    // Show Midtrans popup
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            alert('Pembayaran berhasil!');
                            window.location.href = '{{ route("checkout.success") }}';
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
                            window.location.href = '{{ route("checkout.success") }}';
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                        }
                    });
                } else {
                    console.error('Server error:', data);
                    alert(data.message || 'Terjadi kesalahan saat memproses pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            })
            .finally(() => {
                // Re-enable button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Proses Checkout';
                }
            });
            
        } catch (error) {
            console.error('Error in checkout process:', error);
            alert('Terjadi kesalahan: ' + error.message);
            
            // Re-enable button
            const submitBtn = document.getElementById('checkout-btn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Proses Checkout';
            }
        }
    };
    

    
    // Initialize values for pre-selected options on page load
    function initializeSelectedValues() {
        // Check for pre-selected shipping
        const selectedShippingRadio = document.querySelector('input[name="shipping_expedition"]:checked');
        if (selectedShippingRadio) {
            currentShippingCost = parseInt(selectedShippingRadio.dataset.cost);
            shippingCostElement.textContent = 'Rp ' + currentShippingCost.toLocaleString('id-ID');
            const shippingText = selectedShippingRadio.parentElement.querySelector('.font-medium').textContent + ' - ' + selectedShippingRadio.dataset.estimation;
            selectedShipping.textContent = shippingText;
            console.log('Pre-selected shipping found:', selectedShippingRadio.value, 'cost:', currentShippingCost);
        }
        
        // Set payment method to Midtrans
        selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
        
        updateTotal();
        updateSelectedInfo();
    }
    
    // Call initialization
    initializeSelectedValues();
});
</script>
@endsection 