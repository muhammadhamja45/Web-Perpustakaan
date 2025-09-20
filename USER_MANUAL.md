# 📖 PANDUAN PENGGUNA SISTEM PERPUSTAKAAN DIGITAL

## 🎯 PENGANTAR

Selamat datang di **Sistem Perpustakaan Digital SMK**! Manual ini akan memandu Anda menggunakan semua fitur yang tersedia dalam sistem. Sistem ini dirancang untuk memudahkan pengelolaan perpustakaan sekolah dengan teknologi modern.

---

## 👤 JENIS PENGGUNA

### 🎓 **SISWA (Student)**
- Meminjam dan mengembalikan buku
- Melihat riwayat peminjaman
- Mengelola profil pribadi

### 👨‍💼 **ADMIN**
- Semua fitur siswa +
- Mengelola koleksi buku
- Menyetujui pendaftaran admin baru
- Membuat laporan dan statistik

---

## 🚀 MEMULAI

### **1. REGISTRASI AKUN BARU**

#### **Langkah-langkah:**
1. **Buka halaman registrasi**: Klik "Daftar" atau akses `/register`
2. **Isi form pendaftaran**:
   - **Nama Lengkap**: Masukkan nama sesuai identitas
   - **Email**: Gunakan email aktif (untuk verifikasi)
   - **Password**: Minimal 8 karakter
   - **Konfirmasi Password**: Ulangi password yang sama

3. **Pilih Status**:
   - **📚 Siswa SMK**: Langsung aktif setelah verifikasi
   - **👨‍💼 Admin Sekolah**: Perlu persetujuan admin lain

4. **Centang**: "Saya menyetujui Syarat dan Ketentuan"

5. **Klik**: "🎯 Daftar & Verifikasi Email"

#### **Verifikasi 2FA:**
1. **Popup muncul** dengan pesan "Kode verifikasi telah dikirim ke email Anda"
2. **Cek email** Anda untuk kode 6 digit
3. **Masukkan kode** di popup (auto-focus antar kotak)
4. Kode akan **auto-submit** atau klik "Verifikasi & Daftar"

#### **Status Setelah Registrasi:**
- **Siswa**: Langsung masuk ke dashboard
- **Admin**: Menunggu persetujuan dari admin lain

### **2. LOGIN KE SISTEM**

#### **Langkah-langkah:**
1. **Buka halaman login**: Akses `/login`
2. **Masukkan kredensial**:
   - Email yang sudah terdaftar
   - Password yang benar
3. **Klik**: "Masuk"

#### **Verifikasi 2FA Login:**
1. **Kode dikirim** ke email Anda
2. **Halaman verifikasi** muncul dengan timer 10 menit
3. **Masukkan 6 digit** kode verifikasi
4. **Auto-redirect** ke dashboard sesuai role

---

## 🎓 PANDUAN UNTUK SISWA

### **📊 DASHBOARD SISWA**

Dashboard siswa menampilkan:

#### **Statistik Pribadi:**
- **Buku Dipinjam**: Jumlah buku yang sedang dipinjam
- **Batas Peminjaman**: Maksimal 5 buku bersamaan
- **Jatuh Tempo Terdekat**: Alert tanggal pengembalian
- **Total Riwayat**: Jumlah buku yang pernah dipinjam

#### **Quick Actions:**
- **📚 Pinjam Buku Baru**: Shortcut ke form peminjaman
- **📖 Lihat Semua Pinjaman**: Daftar lengkap buku dipinjam
- **👤 Edit Profil**: Update informasi pribadi

#### **Buku Sedang Dipinjam:**
Tabel menampilkan:
- Judul buku dan penulis
- Tanggal peminjaman
- **Jatuh tempo** (dengan warning jika mendekati)
- Status: Normal/Hampir jatuh tempo/Terlambat
- Tombol "Kembalikan"

### **📚 MEMINJAM BUKU**

#### **Cara Meminjam:**
1. **Dashboard** → Klik "Pinjam Buku" ATAU
2. **Sidebar** → "Pinjam Buku" ATAU
3. **URL langsung**: `/loans/create`

#### **Form Peminjaman:**
1. **Pilih Buku**: Dropdown menampilkan buku yang tersedia
   - Filter otomatis: hanya buku dengan stok > 0
   - Tampil: Judul - Penulis (Stok: X)

2. **Tanggal Jatuh Tempo**:
   - **Minimal**: Besok
   - **Maksimal**: 14 hari dari sekarang
   - **Rekomendasi**: 7 hari untuk buku umum

3. **Klik**: "Pinjam Buku"

#### **Konfirmasi Peminjaman:**
1. **Email otomatis terkirim** dengan detail:
   - Info lengkap buku (judul, penulis, ISBN)
   - ID peminjaman unik
   - Tanggal pinjam dan jatuh tempo
   - Aturan perpustakaan
   - Informasi denda (Rp 1.000/hari jika terlambat)

2. **Dashboard update** menampilkan buku baru di daftar

### **📖 MENGEMBALIKAN BUKU**

#### **Cara Mengembalikan:**
1. **Dashboard** → Tabel "Buku Sedang Dipinjam"
2. **Klik tombol "Kembalikan"** pada buku yang sesuai
3. **Konfirmasi** pengembalian
4. **Sistem otomatis**:
   - Update stok buku (+1)
   - Hapus dari daftar pinjaman Anda
   - Hitung denda (jika ada)

#### **Perhitungan Denda:**
- **Tepat waktu**: Tidak ada denda
- **Terlambat**: Rp 1.000 per hari per buku
- **Contoh**: Terlambat 3 hari = Rp 3.000

### **👤 MENGELOLA PROFIL**

#### **Edit Profil:**
1. **Sidebar** → "Profile" ATAU
2. **Dashboard** → Icon user → "Edit Profil"

#### **Yang Bisa Diubah:**
- **Nama**: Update nama lengkap
- **Email**: Ganti email (perlu verifikasi ulang)

#### **Ganti Password:**
1. **Masukkan password lama** untuk verifikasi
2. **Password baru** (minimal 8 karakter)
3. **Konfirmasi password baru**
4. **Save Changes**

#### **Hapus Akun:**
- **Peringatan**: Aksi ini tidak bisa dibatalkan
- **Syarat**: Harus mengembalikan semua buku terlebih dahulu
- **Konfirmasi**: Masukkan password untuk konfirmasi

---

## 👨‍💼 PANDUAN UNTUK ADMIN

### **📊 DASHBOARD ADMIN**

Dashboard admin menampilkan analytics lengkap:

#### **Statistik Real-time:**
- **📚 Total Buku**: Jumlah koleksi perpustakaan
- **📋 Peminjaman Aktif**: Buku yang sedang dipinjam
- **👥 Total Anggota**: Jumlah siswa terdaftar
- **⚠️ Buku Terlambat**: Alert buku yang melewati jatuh tempo

#### **📈 Grafik Analytics:**
- **Tren Peminjaman 7 Hari**: Line chart aktivitas harian
- **Buku Terpopuler**: Top 5 buku paling diminati
- **Aktivitas Hari Ini**: Real-time activity log
- **Statistik Kategori**: Distribusi peminjaman per kategori

#### **🔔 Recent Activity:**
- Peminjaman hari ini
- Pengembalian terbaru
- Admin baru yang mendaftar
- Alert buku terlambat

### **📚 MANAJEMEN BUKU**

#### **Melihat Daftar Buku:**
1. **Sidebar** → "Kelola Buku"
2. **Tabel menampilkan**:
   - Judul, Penulis, ISBN
   - Kategori, Tahun Terbit
   - **Stok**: Total/Tersedia
   - **Actions**: Edit, Delete, View Details

#### **Tambah Buku Baru:**
1. **Klik**: "Tambah Buku Baru"
2. **Isi form lengkap**:
   ```
   Judul Buku*     : [Required]
   Penulis*        : [Required]
   ISBN            : [Optional, harus unik]
   Kategori        : [Dropdown: Fiksi, Non-Fiksi, Akademik, dll]
   Tahun Terbit    : [4 digit, max tahun ini]
   Deskripsi       : [Textarea untuk sinopsis]
   Jumlah Eksemplar*: [Min: 1, Default: 1]
   ```
3. **Validasi otomatis**:
   - ISBN harus unik (jika diisi)
   - Jumlah eksemplar minimal 1
   - Tahun terbit tidak boleh di masa depan

4. **Klik**: "Simpan Buku"

#### **Edit Buku:**
1. **Tabel Buku** → Klik "Edit" pada buku yang diinginkan
2. **Form pre-filled** dengan data existing
3. **Update informasi** yang diperlukan
4. **Kelola stok**:
   - **Tambah stok**: Increase total quantity
   - **Kurangi stok**: Hati-hati jika sedang dipinjam
5. **Save Changes**

#### **Hapus Buku:**
1. **Tabel Buku** → Klik "Delete"
2. **Sistem cek**: Apakah buku sedang dipinjam?
   - **Jika ya**: Error "Tidak bisa dihapus, masih dipinjam"
   - **Jika tidak**: Konfirmasi penghapusan
3. **Konfirmasi**: "Ya, Hapus Buku Ini"

#### **Detail Buku:**
1. **Klik judul buku** atau "View Details"
2. **Info lengkap**:
   - Semua metadata buku
   - **Riwayat peminjaman** lengkap
   - **Statistik popularitas**
   - **Current borrowers** (jika ada)

### **👥 MANAJEMEN PERSETUJUAN ADMIN**

#### **Mengakses Approval System:**
1. **Sidebar** → "Persetujuan Admin"
2. **Badge notification** menampilkan jumlah pending

#### **Review Pendaftar Admin:**
**Tabel Pending Admins menampilkan:**
- **Foto Profil & Nama**: Info dasar pendaftar
- **Email**: Kontak pendaftar
- **Tanggal Daftar**: Kapan mereka mendaftar
- **Actions**: Approve/Reject buttons

#### **Approve Admin:**
1. **Review informasi** pendaftar
2. **Klik "✅ Approve"**
3. **Konfirmasi**: "Setujui [Nama] sebagai admin?"
4. **Sistem otomatis**:
   - Update status `is_approved = true`
   - Kirim email notifikasi ke user
   - Log approval dengan timestamp
   - User bisa langsung akses admin panel

#### **Reject Admin:**
1. **Klik "❌ Reject"**
2. **Isi alasan penolakan** (optional)
3. **Konfirmasi penolakan**
4. **Sistem otomatis**:
   - Hapus aplikasi admin
   - User tetap sebagai student
   - Kirim email notifikasi penolakan

#### **Bulk Approval:**
1. **Centang checkbox** pada multiple pendaftar
2. **Klik "Approve Selected"**
3. **Konfirmasi bulk action**
4. **Proses batch approval**

### **📊 SISTEM LAPORAN**

#### **Mengakses Reports:**
1. **Sidebar** → "Reports"
2. **Dashboard analytics** dengan multiple tabs

#### **📈 Laporan Peminjaman:**
**Filter Options:**
- **Date Range**: From - To date picker
- **User Type**: All/Students/Admins
- **Book Category**: All/Specific category
- **Status**: All/Active/Returned/Overdue

**Data Yang Ditampilkan:**
- **Grafik tren** peminjaman per periode
- **Tabel detail** setiap transaksi
- **Summary statistics**: Total, average, peak days

**Export Options:**
- **📊 CSV**: Data mentah untuk Excel
- **📋 Excel**: Format dengan header dan styling
- **📄 PDF**: Report siap cetak (future)

#### **📚 Laporan Buku:**
**Inventory Report:**
- **Stok per kategori**
- **Buku paling/kurang diminati**
- **Turnover rate** (frequency of borrowing)
- **Buku yang belum pernah dipinjam**

**Popular Books:**
- **Top 10 buku** paling sering dipinjam
- **Trend naik/turun** popularitas
- **Seasonal patterns** (if data sufficient)

#### **👥 Laporan Anggota:**
**Member Activity:**
- **Most active borrowers**
- **Registration trends** bulanan
- **User engagement** metrics

**Defaulters Report:**
- **Daftar buku terlambat** dengan detail user
- **History keterlambatan** per user
- **Total denda** yang harus dibayar

#### **🔄 Export Data:**
1. **Pilih jenis laporan**
2. **Set filter** yang diinginkan
3. **Klik "Export"**
4. **Pilih format**: CSV/Excel
5. **Download otomatis** mulai

**Format CSV:**
```csv
Tanggal,Nama Siswa,Email,Judul Buku,Penulis,Status,Jatuh Tempo
2025-10-01,John Doe,john@student.com,Laravel Guide,Smith,Dipinjam,2025-10-15
```

---

## 🎨 FITUR TAMBAHAN

### **🌙 Dark Mode**

#### **Mengaktifkan Dark Mode:**
1. **Header kanan atas** → Icon bulan/matahari
2. **Toggle switch** untuk dark/light mode
3. **Preference tersimpan** di browser
4. **Auto-apply** saat login berikutnya

#### **Fitur Dark Mode:**
- **All pages** mendukung dark theme
- **Smooth transition** antar mode
- **Eye-friendly** untuk penggunaan malam
- **Consistent branding** tetap terjaga

### **📱 Responsive Design**

#### **Mobile Compatibility:**
- **📱 Phone**: Optimized untuk layar kecil
- **📲 Tablet**: Layout adapted untuk tablet
- **💻 Desktop**: Full feature experience
- **Auto-responsive**: Deteksi ukuran layar otomatis

#### **Mobile Features:**
- **Swipe gestures** untuk navigasi
- **Touch-friendly** button sizes
- **Simplified menus** untuk mobile
- **Fast loading** pada koneksi lambat

### **🔔 Notifikasi Email**

#### **Email Konfirmasi Peminjaman:**
**Dikirim otomatis saat siswa meminjam buku:**

```
Subject: Konfirmasi Peminjaman Buku - [Judul Buku]

Halo [Nama Siswa],

Selamat! Peminjaman buku Anda telah berhasil diproses.

📖 DETAIL BUKU:
- Judul: [Judul Lengkap]
- Penulis: [Nama Penulis]
- ISBN: [Kode ISBN]
- Kategori: [Kategori Buku]

📋 DETAIL PEMINJAMAN:
- ID Peminjaman: #[Unique ID]
- Tanggal Pinjam: [DD/MM/YYYY, HH:MM] WIB
- Tanggal Pengembalian: [DD/MM/YYYY]
- Status: ✅ DIPINJAM

⚠️ PENTING:
- Kembalikan tepat waktu untuk hindari denda
- Denda keterlambatan: Rp 1.000/hari/buku
- Jaga kondisi buku dengan baik
- Perpanjangan: Maksimal 1x jika tidak ada antrian

📞 KONTAK PERPUSTAKAAN:
Email: perpustakaan@sekolah.ac.id
Telepon: (021) 123-4567
Jam: Senin-Jumat, 08:00-16:00 WIB
```

#### **Email 2FA:**
**Template kode verifikasi:**
```
Subject: Kode Verifikasi [Login/Registrasi]

Kode verifikasi Anda: [123456]

Kode ini berlaku selama 10 menit.
Jangan bagikan kode ini kepada siapapun.

Jika Anda tidak melakukan request ini,
abaikan email ini.
```

---

## ⚡ TIPS & TRIK

### **🎯 Untuk Siswa:**

#### **Memaksimalkan Penggunaan:**
1. **Set reminder** di phone untuk tanggal jatuh tempo
2. **Bookmark** halaman "Pinjam Buku" untuk akses cepat
3. **Check dashboard** reguler untuk status peminjaman
4. **Manfaatkan dark mode** untuk baca malam hari

#### **Menghindari Denda:**
- **Return 1-2 hari** sebelum jatuh tempo
- **Set calendar reminder** saat meminjam
- **Check email** untuk notifikasi system
- **Perpanjang** jika masih butuh (max 1x)

### **🎯 Untuk Admin:**

#### **Mengelola Efisien:**
1. **Batch operations** untuk approval admin
2. **Regular reports** untuk monitoring trends
3. **Update stok** secara berkala
4. **Backup data** via export CSV

#### **Monitoring System:**
- **Daily check** dashboard untuk anomali
- **Weekly reports** untuk performance
- **Monthly analysis** untuk planning
- **Quarterly review** system improvement

---

## 🚨 TROUBLESHOOTING UMUM

### **❓ Login/Registrasi Issues**

#### **"2FA Modal Tidak Muncul"**
**Solusi:**
1. **Refresh browser** dan coba lagi
2. **Disable ad-blocker** sementara
3. **Clear browser cache** dan cookies
4. **Test di browser lain** (Chrome/Firefox)
5. **Check JavaScript** enabled di browser

#### **"Kode 2FA Tidak Diterima"**
**Solusi:**
1. **Check spam folder** di email
2. **Tunggu 2-3 menit** (delay server email)
3. **Klik "Kirim Ulang"** pada form
4. **Verify email address** benar
5. **Contact admin** jika masih bermasalah

#### **"Email Already Registered"**
**Solusi:**
1. **Gunakan email berbeda** atau
2. **Reset password** jika lupa
3. **Contact admin** untuk check account status

### **📚 Peminjaman Issues**

#### **"Buku Tidak Muncul di Dropdown"**
**Kemungkinan Penyebab:**
- **Stok habis** (available_quantity = 0)
- **Buku sedang maintenance**
- **Filter kategori** aktif

**Solusi:**
1. **Refresh halaman** pinjam buku
2. **Check dengan admin** untuk konfirmasi stok
3. **Pilih buku alternatif**

#### **"Tidak Bisa Pinjam (Limit Reached)"**
**Penyebab:** Sudah pinjam 5 buku (maksimal)
**Solusi:**
1. **Kembalikan** satu atau lebih buku terlebih dahulu
2. **Check dashboard** untuk konfirmasi jumlah aktif

#### **"Error Saat Submit Peminjaman"**
**Solusi:**
1. **Check tanggal** jatuh tempo (tidak boleh hari ini/masa lalu)
2. **Refresh dan coba lagi**
3. **Logout/login** ulang
4. **Contact admin** jika persist

### **👨‍💼 Admin Issues**

#### **"Tidak Bisa Access Admin Panel"**
**Penyebab:** Status approval belum aktif
**Solusi:**
1. **Check status** di profile
2. **Wait approval** dari admin lain
3. **Contact existing admin** untuk approval

#### **"Report Export Tidak Bekerja"**
**Solusi:**
1. **Reduce date range** jika data terlalu besar
2. **Try different format** (CSV vs Excel)
3. **Check browser** pop-up blocker
4. **Clear cache** dan coba lagi

---

## 📞 BANTUAN & DUKUNGAN

### **🔧 Technical Support**

#### **Level 1: Self-Help**
1. **Baca manual** ini terlebih dahulu
2. **Check FAQ** di bagian troubleshooting
3. **Try basic solutions**: refresh, clear cache, different browser

#### **Level 2: Admin Support**
- **Contact**: Admin perpustakaan di sekolah
- **Method**: Email atau langsung ke ruang perpustakaan
- **Info yang disiapkan**: Screenshot error, browser info, step yang dilakukan

#### **Level 3: Technical Support**
- **For system issues**: Contact IT department
- **Email**: it-support@sekolah.ac.id
- **Include**: Error logs, browser console, detailed steps

### **📧 Contact Information**

#### **Perpustakaan SMK:**
- **📧 Email**: perpustakaan@sekolah.ac.id
- **📞 Telepon**: (021) 123-4567
- **🏢 Lokasi**: Gedung Utama, Lantai 2
- **🕒 Jam Operasional**: Senin-Jumat, 08:00-16:00 WIB

#### **IT Support:**
- **📧 Email**: it-support@sekolah.ac.id
- **📞 Extension**: 234
- **🏢 Lokasi**: Ruang Server, Lantai 1

---

## 📝 CHANGELOG & UPDATES

### **Version 1.0.0 (Current)**
**Release Date:** September 2025

#### **🆕 New Features:**
- Complete authentication system with 2FA
- Book management (CRUD) for admins
- Loan system with email notifications
- Admin approval workflow
- Comprehensive reporting system
- Dark mode support
- Mobile responsive design

#### **✅ Improvements:**
- Modern UI/UX with Tailwind CSS
- Real-time dashboard analytics
- Automated email notifications
- Advanced search and filtering
- Performance optimizations

### **🔮 Coming Soon (v1.1.0)**
**Expected:** December 2025

#### **Planned Features:**
- **📱 Mobile App**: Native Android/iOS app
- **🔔 Push Notifications**: Real-time alerts
- **💰 Fine Management**: Automated fine calculations
- **📊 Advanced Analytics**: ML-powered insights
- **🤖 Auto Reminders**: Smart notification system
- **📚 Book Recommendations**: Personalized suggestions

---

**🎉 Selamat menggunakan Sistem Perpustakaan Digital SMK!**

Jika Anda memiliki pertanyaan, saran, atau menemukan bug, jangan ragu untuk menghubungi tim support. Kami berkomitmen untuk terus meningkatkan sistem ini demi kenyamanan pengguna.

**Happy Reading! 📚✨**