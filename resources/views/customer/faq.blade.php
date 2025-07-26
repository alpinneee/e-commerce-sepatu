@extends('layouts.customer')

@section('title', 'FAQ')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Pertanyaan yang Sering Diajukan</h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-gray-600">
                Temukan jawaban atas pertanyaan yang sering diajukan tentang produk dan layanan kami.
            </p>
        </div>

        <div class="mt-16 max-w-3xl mx-auto">
            <div class="space-y-8">
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana cara melakukan pembelian di Toko Sepatu?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Untuk melakukan pembelian di Toko Sepatu, ikuti langkah-langkah berikut:</p>
                        <ol class="list-decimal pl-6 mt-2 space-y-1">
                            <li>Pilih produk yang ingin Anda beli</li>
                            <li>Klik tombol "Tambahkan ke Keranjang"</li>
                            <li>Klik ikon keranjang di pojok kanan atas</li>
                            <li>Periksa pesanan Anda dan klik "Checkout"</li>
                            <li>Isi informasi pengiriman dan pembayaran</li>
                            <li>Klik "Pesan Sekarang" untuk menyelesaikan pembelian</li>
                        </ol>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Metode pembayaran apa saja yang tersedia?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Kami menerima berbagai metode pembayaran, termasuk:</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Transfer bank (BCA, Mandiri, BNI, BRI)</li>
                            <li>Kartu kredit (Visa, Mastercard)</li>
                            <li>E-wallet (GoPay, OVO, Dana, LinkAja)</li>
                            <li>Virtual Account</li>
                            <li>Cash on Delivery (COD)</li>
                        </ul>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Berapa lama waktu pengiriman?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Waktu pengiriman tergantung pada lokasi Anda:</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Jakarta: 1-2 hari kerja</li>
                            <li>Jawa: 2-3 hari kerja</li>
                            <li>Sumatera, Kalimantan, Sulawesi: 3-5 hari kerja</li>
                            <li>Papua, Maluku, dan daerah terpencil lainnya: 5-7 hari kerja</li>
                        </ul>
                        <p class="mt-2">Perlu diingat bahwa waktu pengiriman dapat bervariasi tergantung pada ketersediaan stok dan kondisi cuaca.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana kebijakan pengembalian dan penukaran?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Kami menerima pengembalian dan penukaran dalam waktu 7 hari setelah barang diterima dengan ketentuan:</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Produk dalam kondisi asli dan belum digunakan</li>
                            <li>Produk masih dalam kemasan asli dan lengkap</li>
                            <li>Disertai bukti pembelian (invoice)</li>
                            <li>Alasan pengembalian yang valid (cacat produk, ukuran tidak sesuai, atau produk tidak sesuai dengan deskripsi)</li>
                        </ul>
                        <p class="mt-2">Biaya pengiriman untuk pengembalian ditanggung oleh pembeli, kecuali jika pengembalian disebabkan oleh kesalahan kami.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Apakah produk yang dijual dijamin keasliannya?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Ya, kami menjamin keaslian semua produk yang kami jual. Kami hanya bekerja sama dengan distributor resmi dan brand terpercaya. Jika Anda menemukan produk palsu dari toko kami, kami akan mengembalikan uang Anda 100% dan memberikan voucher belanja sebagai kompensasi.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana cara melacak pesanan saya?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Anda dapat melacak pesanan dengan cara berikut:</p>
                        <ol class="list-decimal pl-6 mt-2 space-y-1">
                            <li>Login ke akun Anda</li>
                            <li>Klik "Pesanan Saya" di halaman profil</li>
                            <li>Pilih pesanan yang ingin dilacak</li>
                            <li>Klik tombol "Lacak Pesanan"</li>
                        </ol>
                        <p class="mt-2">Anda juga akan menerima email dengan nomor resi dan link pelacakan setelah pesanan Anda dikirim.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-gray-200 pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Apakah ada biaya pengiriman?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Ya, biaya pengiriman dihitung berdasarkan berat produk, lokasi pengiriman, dan jasa pengiriman yang dipilih. Biaya pengiriman akan ditampilkan pada saat checkout sebelum Anda menyelesaikan pembelian.</p>
                        <p class="mt-2">Kami menawarkan pengiriman gratis untuk pembelian dengan nilai tertentu (biasanya di atas Rp 500.000) ke sebagian besar wilayah di Indonesia.</p>
                    </div>
                </div>

                <div x-data="{ open: false }" class="pb-6">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana cara menghubungi layanan pelanggan?</h3>
                        <span class="ml-6 flex-shrink-0">
                            <svg x-show="!open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" class="mt-4 text-base text-gray-600">
                        <p>Anda dapat menghubungi layanan pelanggan kami melalui:</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Email: cs@tokosepatu.com</li>
                            <li>Telepon: +62 123 4567 890 (Senin-Jumat, 08.00-17.00)</li>
                            <li>Live Chat: Tersedia di website kami (24/7)</li>
                            <li>WhatsApp: +62 987 6543 210</li>
                            <li>Media Sosial: Instagram, Facebook, Twitter (@tokosepatu)</li>
                        </ul>
                        <p class="mt-2">Tim layanan pelanggan kami akan merespons pertanyaan Anda dalam waktu 1x24 jam pada hari kerja.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Masih punya pertanyaan?</h2>
            <p class="mt-4 text-gray-600">
                Jika Anda tidak menemukan jawaban atas pertanyaan Anda, jangan ragu untuk menghubungi kami.
            </p>
            <div class="mt-6">
                <a href="{{ route('contact') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush 