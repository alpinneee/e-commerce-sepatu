# Activity Diagram - E-Commerce Toko Sepatu

## Tabel Activity Diagram Sistem E-Commerce

### 1. Alur Guest User

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 1 | Guest | Start | Memulai aplikasi | - | Masuk ke halaman utama |
| 2 | Guest | Browse Products | Melihat daftar produk | - | Tampil daftar produk |
| 3 | Guest | Search Products | Cari produk berdasarkan kategori | - | Hasil pencarian |
| 4 | Guest | View Product Details | Lihat detail produk | - | Detail produk tampil |
| 5 | Guest | Need Account? | Perlu akun untuk checkout | Ya/Tidak | Ke registrasi atau login |
| 6 | Guest | Register Account | Buat akun baru | Pilih registrasi | Form registrasi |
| 7 | Guest | Fill Registration Form | Isi data registrasi | - | Data tersimpan |
| 8 | Guest | Submit Registration | Kirim data registrasi | - | Email verifikasi |
| 9 | Guest | Email Verification | Verifikasi email | - | Akun aktif |
| 10 | Guest | Login | Masuk ke akun | - | Ke proses autentikasi |

### 2. Alur Customer

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 11 | Customer | Authentication Check | Validasi login | Valid/Tidak | Dashboard atau ulang login |
| 12 | Customer | Customer Dashboard | Halaman utama customer | - | Menu customer tersedia |
| 13 | Customer | Choose Action | Pilih aktivitas | Browse/Profile/Orders | Ke aktivitas terpilih |
| 14 | Customer | Browse Products | Lihat produk sebagai customer | - | Daftar produk |
| 15 | Customer | Add to Cart | Tambah produk ke keranjang | - | Produk masuk keranjang |
| 16 | Customer | View Cart | Lihat isi keranjang | - | Daftar item keranjang |
| 17 | Customer | Update Cart? | Perlu update keranjang | Ya/Tidak | Modify atau checkout |
| 18 | Customer | Modify Cart | Ubah jumlah/hapus item | Pilih update | Keranjang terupdate |
| 19 | Customer | Proceed to Checkout | Lanjut ke checkout | - | Halaman checkout |
| 20 | Customer | Select Address | Pilih/tambah alamat kirim | - | Alamat terpilih |
| 21 | Customer | Choose Payment | Pilih metode pembayaran | - | Metode terpilih |
| 22 | Customer | Review Order | Review detail pesanan | - | Konfirmasi pesanan |
| 23 | Customer | Confirm Order | Konfirmasi pesanan | - | Proses pembayaran |
| 24 | Customer | Process Payment | Proses pembayaran | Sukses/Gagal | Order dibuat atau ulang |

### 3. Alur Profile Management Customer

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 25 | Customer | Manage Profile | Kelola profil | - | Menu profil |
| 26 | Customer | Update Profile | Update info profil | - | Profil terupdate |
| 27 | Customer | Change Password | Ganti password | - | Password berubah |
| 28 | Customer | Manage Addresses | Kelola alamat | - | Daftar alamat |
| 29 | Customer | View Orders | Lihat riwayat pesanan | - | Daftar pesanan |
| 30 | Customer | Track Order | Lacak status pesanan | - | Status pesanan |

### 4. Alur Admin

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 31 | Admin | Admin Dashboard | Panel admin | - | Menu admin tersedia |
| 32 | Admin | Admin Actions | Pilih aktivitas admin | Products/Orders/Users/Categories | Ke menu terpilih |
| 33 | Admin | Manage Products | Kelola produk | - | CRUD produk |
| 34 | Admin | Add Product | Tambah produk baru | - | Produk baru tersimpan |
| 35 | Admin | Edit Product | Edit produk existing | - | Produk terupdate |
| 36 | Admin | Delete Product | Hapus produk | - | Produk terhapus |
| 37 | Admin | Manage Categories | Kelola kategori | - | CRUD kategori |
| 38 | Admin | Add Category | Tambah kategori baru | - | Kategori baru tersimpan |
| 39 | Admin | Edit Category | Edit kategori existing | - | Kategori terupdate |
| 40 | Admin | Delete Category | Hapus kategori | - | Kategori terhapus |

### 5. Alur Order Management Admin

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 41 | Admin | Manage Orders | Kelola pesanan | - | Daftar pesanan |
| 42 | Admin | View Order List | Lihat daftar pesanan | - | List semua pesanan |
| 43 | Admin | Select Order | Pilih pesanan | - | Detail pesanan |
| 44 | Admin | View Order Details | Lihat detail pesanan | - | Info lengkap pesanan |
| 45 | Admin | Update Status | Update status pesanan | Processing/Shipped/Delivered/Cancelled | Status berubah |
| 46 | Admin | Set Processing | Set status processing | - | Status: Processing |
| 47 | Admin | Set Shipped | Set status shipped | - | Status: Shipped |
| 48 | Admin | Set Delivered | Set status delivered | - | Status: Delivered |
| 49 | Admin | Set Cancelled | Set status cancelled | - | Status: Cancelled |
| 50 | System | Notify Customer | Kirim notifikasi ke customer | - | Customer ternotifikasi |
| 51 | Customer | Receive Notification | Terima notifikasi status | - | Customer tahu status |
| 52 | System | Order Complete? | Cek pesanan selesai | Ya/Tidak | End atau lanjut |

### 6. Alur User Management Admin

| No | Actor | Aktivitas | Deskripsi | Kondisi/Keputusan | Hasil |
|----|-------|-----------|-----------|-------------------|-------|
| 53 | Admin | Manage Users | Kelola user | - | Menu user management |
| 54 | Admin | View Users | Lihat daftar customer | - | List semua customer |
| 55 | Admin | View User Details | Lihat detail customer | - | Info lengkap customer |
| 56 | System | End | Selesai | - | Proses berakhir |

## Ringkasan Alur Sistem

### Flow Utama:
1. **Guest (1-10)**: Browse → Register → Login
2. **Customer Shopping (11-24)**: Login → Browse → Cart → Checkout → Payment
3. **Customer Profile (25-30)**: Manage Profile → View Orders → Track Status
4. **Admin Product (31-40)**: Manage Products → Manage Categories
5. **Admin Orders (41-52)**: Process Orders → Update Status → Notify Customer
6. **Admin Users (53-56)**: Manage Customer Accounts

### Status Pesanan:
- **Processing**: Pesanan sedang diproses
- **Shipped**: Pesanan sudah dikirim
- **Delivered**: Pesanan sudah sampai
- **Cancelled**: Pesanan dibatalkan

### Fitur Tercakup:
✅ Authentication & Registration | ✅ Product Management | ✅ Shopping Cart
✅ Checkout Process | ✅ Order Tracking | ✅ Profile Management
✅ Admin Dashboard | ✅ User Management | ✅ Category Management