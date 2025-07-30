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

                    <!-- JNT -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/J%26T_Express_logo.png" alt="JNT" class="h-8 w-auto mr-3">
                            <span class="font-medium text-gray-900">J&T Express</span>
                        </div>
                        <div class="p-4 space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jnt_regular" class="text-blue-600 focus:ring-blue-500" data-cost="9000" data-estimation="2-4 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">J&T Regular</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-4 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 9.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jnt_express" class="text-blue-600 focus:ring-blue-500" data-cost="14000" data-estimation="1-2 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">J&T Express</div>
                                            <div class="text-sm text-gray-600">Estimasi: 1-2 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 14.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SiCepat -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/SiCepat_Ekspres_logo.png" alt="SiCepat" class="h-8 w-auto mr-3">
                            <span class="font-medium text-gray-900">SiCepat Ekspres</span>
                        </div>
                        <div class="p-4 space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="sicepat_regular" class="text-blue-600 focus:ring-blue-500" data-cost="8000" data-estimation="2-3 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">SiCepat REG</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-3 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 8.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="sicepat_halu" class="text-blue-600 focus:ring-blue-500" data-cost="12000" data-estimation="1 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">SiCepat HALU (Hari Itu Sampai)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 1 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 12.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Pos Indonesia -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/0e/Pos_Indonesia_2013_logo.svg" alt="Pos Indonesia" class="h-8 w-auto mr-3">
                            <span class="font-medium text-gray-900">Pos Indonesia</span>
                        </div>
                        <div class="p-4 space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="pos_regular" class="text-blue-600 focus:ring-blue-500" data-cost="7000" data-estimation="3-5 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">Pos Reguler</div>
                                            <div class="text-sm text-gray-600">Estimasi: 3-5 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 7.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="pos_express" class="text-blue-600 focus:ring-blue-500" data-cost="13000" data-estimation="1-2 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">Pos Kilat Khusus</div>
                                            <div class="text-sm text-gray-600">Estimasi: 1-2 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 13.000</div>
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

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6 animate-slide-up animation-delay-4000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Metode Pembayaran</h2>
                
                <!-- Midtrans Payment Gateway -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Pembayaran Aman dengan Midtrans</h3>
                            <p class="text-sm text-gray-600">Payment gateway terpercaya di Indonesia</p>
                        </div>
                    </div>
                    
                    <!-- Available Payment Methods -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">💳</span>
                            <span class="text-xs font-medium text-gray-700">Kartu Kredit</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">🔄</span>
                            <span class="text-xs font-medium text-gray-700">QRIS</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">📱</span>
                            <span class="text-xs font-medium text-gray-700">GoPay</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">💰</span>
                            <span class="text-xs font-medium text-gray-700">ShopeePay</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">🏦</span>
                            <span class="text-xs font-medium text-gray-700">Transfer Bank</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">🏪</span>
                            <span class="text-xs font-medium text-gray-700">Indomaret</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">💎</span>
                            <span class="text-xs font-medium text-gray-700">Akulaku</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                            <span class="text-2xl mb-1 block">⚡</span>
                            <span class="text-xs font-medium text-gray-700">Dan lainnya</span>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-2 text-sm text-blue-700">
                        <svg class="w-4 h-4 mt-0.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div>
                            <p class="font-medium">Keamanan Terjamin</p>
                            <p class="text-xs">Transaksi dilindungi SSL 256-bit dan standar PCI DSS</p>
                        </div>
                    </div>
                </div>

                <!-- Cash on Delivery Option -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                        </svg>
                        Atau Bayar di Tempat
                    </h3>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="payment_method" value="cod" class="text-blue-600 focus:ring-blue-500">
                        <div class="ml-3 flex-1">
                            <div>
                            <div class="font-medium text-gray-900">Cash on Delivery (COD)</div>
                                <div class="text-sm text-gray-600">Bayar tunai saat barang diterima</div>
                                <div class="text-xs text-orange-600 mt-1">⚠️ Tambahan biaya COD Rp 5.000</div>
                            </div>
                        </div>
                    </label>
                </div>
                
                <!-- Hidden input for Midtrans payment -->
                <input type="hidden" name="payment_method" value="midtrans" id="hidden-payment-method">
                
                @error('payment_method')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Catatan tambahan untuk pesanan">{{ old('notes') }}</textarea>
                </div>
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
                    
                    <div class="flex justify-between" id="cod-fee-row" style="display: none;">
                        <span class="text-gray-600">Biaya COD</span>
                        <span class="font-medium text-orange-600">Rp 5.000</span>
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
                
                <button type="submit" form="checkout-form" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        id="checkout-btn">
                    Proses Checkout
                </button>
                
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
    
    // Shipping and payment tracking
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');
    const codFeeRow = document.getElementById('cod-fee-row');
    const selectedInfo = document.getElementById('selected-info');
    const selectedPayment = document.getElementById('selected-payment');
    const selectedShipping = document.getElementById('selected-shipping');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    let currentShippingCost = 0;
    let currentCodFee = 0;
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
    
    // Handle payment method selection (COD vs Midtrans)
    const codRadio = document.querySelector('input[name="payment_method"][value="cod"]');
    const hiddenPaymentInput = document.getElementById('hidden-payment-method');
    
    if (codRadio) {
        codRadio.addEventListener('change', function() {
            if (this.checked) {
                hiddenPaymentInput.value = 'cod';
                currentCodFee = 5000;
                codFeeRow.style.display = 'flex';
                selectedPayment.textContent = 'Cash on Delivery (COD)';
            } else {
                hiddenPaymentInput.value = 'midtrans';
                currentCodFee = 0;
                codFeeRow.style.display = 'none';
                selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
            }
            updateTotal();
            updateSelectedInfo();
        });
    }
    
    // Initialize payment method (default to Midtrans)
    function initializePaymentMethod() {
        if (!codRadio || !codRadio.checked) {
            hiddenPaymentInput.value = 'midtrans';
            currentCodFee = 0;
            codFeeRow.style.display = 'none';
            selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
        }
    }
    
    function updateTotal() {
        const newTotal = baseTotal + currentShippingCost + currentCodFee;
        totalAmountElement.textContent = 'Rp ' + newTotal.toLocaleString('id-ID');
    }
    
    function updateSelectedInfo() {
        const hiddenPaymentInput = document.getElementById('hidden-payment-method');
        const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
        
        if ((hiddenPaymentInput && hiddenPaymentInput.value) || hasShipping) {
            selectedInfo.style.display = 'block';
        } else {
            selectedInfo.style.display = 'none';
        }
    }
    
    // Form validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        try {
            console.log('Form submit triggered');
            
            const hiddenPaymentInput = document.getElementById('hidden-payment-method');
            const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
            
            console.log('Hidden payment method:', hiddenPaymentInput ? hiddenPaymentInput.value : 'none');
            console.log('Has shipping:', hasShipping);
            
            // Ensure payment method is set
            initializePaymentMethod();
            
            if (!hiddenPaymentInput || !hiddenPaymentInput.value) {
                e.preventDefault();
                alert('Metode pembayaran tidak valid');
                return;
            }
            
            if (!hasShipping) {
                e.preventDefault();
                alert('Silakan pilih ekspedisi pengiriman');
                return;
            }
            
            // Ensure shipping cost is set
            if (currentShippingCost <= 0) {
                e.preventDefault();
                alert('Ongkos kirim tidak valid. Silakan pilih ekspedisi pengiriman.');
                console.error('Invalid shipping cost:', currentShippingCost);
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('checkout-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Memproses...';
            }
            
            console.log('Current shipping cost:', currentShippingCost);
            console.log('Current COD fee:', currentCodFee);
            
            // Add shipping cost and COD fee to form
            // Remove existing hidden inputs first to avoid duplicates
            const existingShippingInput = this.querySelector('input[name="shipping_cost"]');
            const existingCodInput = this.querySelector('input[name="cod_fee"]');
            if (existingShippingInput) existingShippingInput.remove();
            if (existingCodInput) existingCodInput.remove();
            
            const shippingCostInput = document.createElement('input');
            shippingCostInput.type = 'hidden';
            shippingCostInput.name = 'shipping_cost';
            shippingCostInput.value = currentShippingCost;
            this.appendChild(shippingCostInput);
            
            const codFeeInput = document.createElement('input');
            codFeeInput.type = 'hidden';
            codFeeInput.name = 'cod_fee';
            codFeeInput.value = currentCodFee;
            this.appendChild(codFeeInput);
            
            // Log form data before submission
            const formData = new FormData(this);
            console.log('Form data to be submitted:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            console.log('Form submission proceeding...');
        } catch (error) {
            console.error('Error in form submission handler:', error);
            // Let the form submit naturally if there's an error in our JavaScript
        }
    });
    
    // Additional debugging for button click
    const checkoutButton = document.getElementById('checkout-btn');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function(e) {
            console.log('Checkout button clicked');
            console.log('Button form attribute:', this.getAttribute('form'));
            console.log('Button type:', this.type);
            
            // Manual form submission as fallback
            if (this.type !== 'submit') {
                console.log('Button is not submit type, manually triggering form submit');
                e.preventDefault();
                const targetForm = document.getElementById(this.getAttribute('form'));
                if (targetForm) {
                    targetForm.requestSubmit();
                }
            }
        });
    } else {
        console.error('Checkout button not found!');
    }
    
    // Log form existence
    const form = document.getElementById('checkout-form');
    if (form) {
        console.log('Checkout form found');
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
    } else {
        console.error('Checkout form not found!');
    }
    
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
        
        // Check for pre-selected payment
        const selectedPaymentRadio = document.querySelector('input[name="payment_method"]:checked');
        if (selectedPaymentRadio) {
            if (selectedPaymentRadio.value === 'cod') {
                currentCodFee = 5000;
                codFeeRow.style.display = 'flex';
            } else {
                currentCodFee = 0;
                codFeeRow.style.display = 'none';
            }
            const paymentText = selectedPaymentRadio.parentElement.querySelector('.font-medium').textContent;
            selectedPayment.textContent = paymentText;
            console.log('Pre-selected payment found:', selectedPaymentRadio.value, 'cod fee:', currentCodFee);
        }
        
        updateTotal();
        updateSelectedInfo();
    }
    
    // Call initialization
    initializeSelectedValues();
    initializePaymentMethod();
});
</script>
@endsection 