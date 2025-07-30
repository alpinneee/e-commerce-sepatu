# Setup Midtrans Payment Gateway

## 🚀 Integrasi Midtrans Berhasil!

Aplikasi e-commerce toko sepatu sekarang menggunakan **Midtrans** sebagai payment gateway utama. Berikut cara setup dan konfigurasinya:

## 📋 Yang Sudah Diimplementasi

✅ **Midtrans Snap Integration** - Payment popup yang aman dan modern  
✅ **Multiple Payment Methods** - Credit Card, QRIS, E-Wallets, Bank Transfer, Indomaret, dll  
✅ **Automatic Payment Notification** - Webhook untuk update status otomatis  
✅ **COD Option** - Tetap tersedia sebagai alternatif  
✅ **Security Features** - SSL encryption dan PCI DSS compliance  

## 🔧 Konfigurasi Midtrans

### 1. Daftar Akun Midtrans

1. Buka [https://midtrans.com](https://midtrans.com)
2. Klik **"Daftar Sekarang"** atau **"Register"**
3. Isi form pendaftaran dengan data bisnis Anda
4. Verifikasi email dan lengkapi proses KYC

### 2. Dapatkan API Keys

1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih environment:
   - **Sandbox** untuk testing
   - **Production** untuk live payments
3. Go to **Settings > Access Keys**
4. Copy:
   - **Server Key**
   - **Client Key**
   - **Merchant ID**

### 3. Update File .env

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# Midtrans Configuration
# Untuk Testing (Sandbox)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxx
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_IS_PRODUCTION=false

# Untuk Production (Live)
# MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxx
# MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxx
# MIDTRANS_MERCHANT_ID=G123456789
# MIDTRANS_IS_PRODUCTION=true
```

### 4. Setup Webhook URL (Penting!)

Di Midtrans Dashboard:
1. Go to **Settings > Configuration**
2. Set **Payment Notification URL** ke:
   ```
   https://yourdomain.com/midtrans/notification
   ```
3. Set **Finish Redirect URL** ke:
   ```
   https://yourdomain.com/midtrans/finish
   ```
4. Set **Unfinish Redirect URL** ke:
   ```
   https://yourdomain.com/checkout
   ```
5. Set **Error Redirect URL** ke:
   ```
   https://yourdomain.com/checkout
   ```

## 🎯 Flow Pembayaran Baru

### Customer Journey:
1. **Checkout** → Pilih shipping & isi alamat
2. **Payment** → Redirect ke halaman Midtrans Snap
3. **Pay** → Pilih metode pembayaran (QRIS/E-Wallet/Bank/dll)
4. **Notification** → System otomatis update status order
5. **Success** → Customer diarahkan ke halaman sukses

### Admin Dashboard:
- **Real-time Updates** → Status pembayaran update otomatis
- **Payment Verification** → Tidak perlu verifikasi manual (kecuali COD)
- **Comprehensive Reporting** → Lihat semua transaksi di Midtrans Dashboard

## 💳 Metode Pembayaran Tersedia

### ✨ Instant Payment
- **QRIS** - Scan QR code dari app banking/e-wallet manapun
- **GoPay** - Pembayaran langsung dari saldo GoPay
- **ShopeePay** - Pembayaran dari ShopeePay
- **DANA** - Transfer dari saldo DANA
- **OVO** - Transfer dari saldo OVO

### 🏦 Bank Transfer
- **BCA Virtual Account**
- **Mandiri Virtual Account**
- **BRI Virtual Account**
- **BNI Virtual Account**
- **Permata Virtual Account**

### 💳 Credit Card
- **Visa, Mastercard, JCB, Amex**
- **Installment** (cicilan) untuk kartu kredit tertentu

### 🏪 Retail Payment
- **Indomaret** - Bayar di counter Indomaret
- **Alfamart** - Bayar di counter Alfamart

### 💎 Credit/BNPL
- **Akulaku** - Buy Now Pay Later
- **Kredivo** - Cicilan tanpa kartu kredit

## 🔒 Keamanan

- **PCI DSS Compliant** - Standar keamanan internasional
- **SSL Encryption** - Data dilindungi end-to-end
- **3D Secure** - Verifikasi tambahan untuk kartu kredit
- **Fraud Detection** - AI-powered anti-fraud system
- **Secure Hosting** - Infrastructure tersertifikasi ISO 27001

## 🧪 Testing

### Sandbox Testing:
Gunakan **test cards** dari dokumentasi Midtrans:
- **Success**: 4811 1111 1111 1114
- **Failure**: 4911 1111 1111 1113
- **Challenge**: 4411 1111 1111 1118

### Test E-Wallets:
- **GoPay**: Gunakan nomor test 081234567890
- **ShopeePay**: Automatic success di Sandbox

## 📊 Monitoring

### Di Midtrans Dashboard:
- **Transaction List** - Semua transaksi real-time
- **Settlement Report** - Laporan settlement harian
- **Failed Transaction** - Analisis transaksi gagal
- **Fraud Analysis** - Laporan fraud detection

### Di Admin Dashboard:
- **Payment Status Updates** - Otomatis via webhook
- **Order Management** - Terintegrasi dengan status pembayaran
- **Customer Notifications** - Email update otomatis

## 🚀 Go Live Checklist

Sebelum production:

1. ✅ **Test semua payment methods** di Sandbox
2. ✅ **Verify webhook** bekerja dengan benar
3. ✅ **Update .env** dengan Production keys
4. ✅ **Set MIDTRANS_IS_PRODUCTION=true**
5. ✅ **Update webhook URLs** ke domain production
6. ✅ **Submit business documents** untuk approval Midtrans
7. ✅ **Test real payment** dengan amount kecil

## 🆘 Troubleshooting

### Payment Gagal?
- Cek **logs** di `storage/logs/laravel.log`
- Verifikasi **API keys** di .env
- Pastikan **webhook URL** accessible dari Midtrans

### Webhook Tidak Bekerja?
- Test webhook dengan tools seperti **ngrok** untuk local development
- Pastikan URL **tidak memerlukan authentication**
- Cek **firewall settings** server

### Status Tidak Update?
- Verify **notification URL** di Midtrans Dashboard
- Check **MidtransController@notification** endpoint
- Review **logs** untuk error messages

## 📞 Support

- **Midtrans Docs**: [https://docs.midtrans.com](https://docs.midtrans.com)
- **Midtrans Support**: [support@midtrans.com](mailto:support@midtrans.com)
- **Integration Guide**: [https://docs.midtrans.com/docs/snap-integration-guide](https://docs.midtrans.com/docs/snap-integration-guide)

---

🎉 **Selamat!** Aplikasi e-commerce Anda sekarang memiliki payment gateway professional yang mendukung 20+ metode pembayaran dengan keamanan tingkat enterprise! 