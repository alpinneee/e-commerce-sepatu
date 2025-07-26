@extends('layouts.customer')

@section('title', 'Tentang Kami')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Tentang Kami</h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-gray-600">
                Toko Sepatu adalah toko online terpercaya yang menyediakan berbagai jenis sepatu berkualitas dengan harga terjangkau.
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-y-16 lg:grid-cols-2 lg:gap-x-12">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Visi & Misi</h2>
                    <div class="mt-6 space-y-6 text-base text-gray-600">
                        <p>
                            <strong>Visi:</strong> Menjadi toko sepatu online terkemuka di Indonesia yang menyediakan produk berkualitas dengan pelayanan terbaik.
                        </p>
                        <p>
                            <strong>Misi:</strong>
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Menyediakan produk sepatu berkualitas tinggi dengan harga terjangkau</li>
                            <li>Memberikan pengalaman berbelanja online yang mudah, aman, dan nyaman</li>
                            <li>Mengutamakan kepuasan pelanggan melalui layanan pelanggan yang responsif</li>
                            <li>Terus berinovasi dalam produk dan layanan untuk memenuhi kebutuhan pelanggan</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Keunggulan Kami</h2>
                    <div class="mt-6 space-y-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-blue-600 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Produk Berkualitas</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Kami hanya menjual produk sepatu berkualitas tinggi dari brand terpercaya.
                                </p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-blue-600 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Harga Terjangkau</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Kami menawarkan harga yang kompetitif dan sering mengadakan promo menarik.
                                </p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-blue-600 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Pengiriman Cepat</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Kami bekerja sama dengan jasa pengiriman terpercaya untuk memastikan produk sampai dengan cepat dan aman.
                                </p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-blue-600 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Layanan Pelanggan 24/7</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Tim layanan pelanggan kami siap membantu Anda 24 jam sehari, 7 hari seminggu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 text-center">Tim Kami</h2>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="text-center">
                    <img class="mx-auto h-40 w-40 rounded-full" src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="Team Member">
                    <h3 class="mt-6 text-lg font-semibold leading-7 tracking-tight text-gray-900">John Doe</h3>
                    <p class="text-sm leading-6 text-gray-600">CEO & Founder</p>
                </div>
                <div class="text-center">
                    <img class="mx-auto h-40 w-40 rounded-full" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="Team Member">
                    <h3 class="mt-6 text-lg font-semibold leading-7 tracking-tight text-gray-900">Michael Smith</h3>
                    <p class="text-sm leading-6 text-gray-600">Head of Operations</p>
                </div>
                <div class="text-center">
                    <img class="mx-auto h-40 w-40 rounded-full" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="Team Member">
                    <h3 class="mt-6 text-lg font-semibold leading-7 tracking-tight text-gray-900">Sarah Johnson</h3>
                    <p class="text-sm leading-6 text-gray-600">Customer Service Manager</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 