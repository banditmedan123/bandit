# Sistem Informasi Optimalisasi Pengelolaan Data Mahasiswa (SIOM)

## Deskripsi
SIOM adalah sistem informasi berbasis web yang dirancang untuk mengoptimalkan pengelolaan data mahasiswa secara efisien dan terintegrasi. Sistem ini menyediakan dashboard yang modern dan user-friendly untuk mengelola berbagai aspek data mahasiswa dengan sistem login yang terpisah untuk admin dan mahasiswa.

## Fitur Utama

### 🔐 Sistem Login Terpadu
- **Login Admin**: Akses ke dashboard admin untuk manajemen sistem
- **Login Mahasiswa**: Akses ke dashboard mahasiswa untuk informasi pribadi
- **Keamanan**: Validasi kredensial yang aman
- **Remember Me**: Opsi untuk menyimpan sesi login

### 📊 Dashboard Admin
- **Statistik Real-time**: Menampilkan data total mahasiswa, mahasiswa aktif, mahasiswa cuti, dan lulusan
- **Grafik Interaktif**: Visualisasi data mahasiswa per fakultas dan tren pendaftaran
- **Aktivitas Terbaru**: Monitoring aktivitas sistem secara real-time
- **Aksi Cepat**: Akses cepat ke fungsi-fungsi utama

### 👨‍🎓 Dashboard Mahasiswa
- **Informasi Pribadi**: Data mahasiswa, NIM, fakultas, dan status akademik
- **Statistik Akademik**: IPK, total SKS, jumlah mata kuliah
- **Jadwal Kuliah**: Jadwal harian dan mingguan
- **Keuangan**: Ringkasan pembayaran SPP dan tagihan
- **Aktivitas Terbaru**: Timeline aktivitas akademik

### 👥 Manajemen Data Mahasiswa
- Pendaftaran mahasiswa baru
- Update data mahasiswa
- Pencarian dan filter data
- Export data dalam berbagai format

### 📚 Manajemen Akademik
- Pengelolaan nilai dan transkrip
- Monitoring progress studi
- Pengaturan jadwal kuliah
- Tracking kelulusan

### 💰 Manajemen Keuangan
- Monitoring pembayaran SPP
- Laporan keuangan mahasiswa
- Tracking beasiswa
- Notifikasi pembayaran

### 📈 Laporan dan Analytics
- Laporan statistik mahasiswa
- Analisis tren pendaftaran
- Dashboard performa fakultas
- Export laporan dalam berbagai format

## Teknologi yang Digunakan

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Charts**: Chart.js untuk visualisasi data
- **Icons**: Font Awesome
- **Fonts**: Inter (Google Fonts)
- **Design**: Modern UI/UX dengan gradient dan shadow effects

## Struktur File

```
SIOM/
├── login.html                    # Halaman login utama
├── redirect.html                 # Halaman redirect ke login
├── index.html                    # Dashboard admin
├── styles.css                    # Styling dan layout
├── script.js                     # Interaktivitas dan charts
├── README.md                     # Dokumentasi proyek
└── pages/
    ├── mahasiswa.html            # Halaman data mahasiswa (admin)
    └── mahasiswa-dashboard.html  # Dashboard mahasiswa
```

## Cara Menjalankan

1. **Clone atau download** proyek ini ke direktori web server Anda
2. **Buka file `login.html`** di browser web untuk akses sistem
3. **Atau jalankan melalui web server** untuk pengalaman optimal

### Menggunakan XAMPP (Recommended)
1. Copy folder SIOM ke direktori `htdocs`
2. Buka XAMPP Control Panel
3. Start Apache server
4. Buka browser dan akses `http://localhost/SIOM/login.html`

### Menggunakan Live Server (VS Code)
1. Install extension Live Server di VS Code
2. Right-click pada `login.html`
3. Pilih "Open with Live Server"

## Sistem Login

### Kredensial Demo

#### Admin
- **Username**: `admin`
- **Password**: `admin123`
- **Akses**: Dashboard admin dengan semua fitur manajemen

#### Mahasiswa
- **NIM**: `2024001`
- **Password**: `mahasiswa123`
- **Akses**: Dashboard mahasiswa dengan informasi pribadi

### Fitur Login
- **Switch User Type**: Toggle antara login admin dan mahasiswa
- **Form Validation**: Validasi input yang real-time
- **Loading Animation**: Indikator proses login
- **Error Handling**: Pesan error yang informatif
- **Social Login**: Opsi login dengan Google dan Microsoft (coming soon)

## Fitur Responsif

Sistem ini dirancang responsif untuk berbagai ukuran layar:
- **Desktop**: Layout penuh dengan sidebar tetap
- **Tablet**: Sidebar collapsible dengan toggle
- **Mobile**: Layout mobile-first dengan navigasi yang dioptimalkan

## Komponen Dashboard

### Dashboard Admin
1. **Sidebar Navigation**: Menu navigasi utama dengan icon intuitif
2. **Header**: Toggle menu, search box, user profile, dan logout
3. **Stats Cards**: Total Mahasiswa (2,847), Mahasiswa Aktif (2,634), Mahasiswa Cuti (156), Lulusan (57)
4. **Charts**: Doughnut chart distribusi fakultas dan line chart tren pendaftaran
5. **Recent Activities**: Timeline aktivitas terbaru
6. **Quick Actions**: Tambah Mahasiswa, Export Data, Generate Laporan, Notifikasi

### Dashboard Mahasiswa
1. **Student Info**: Informasi pribadi dengan avatar dan status akademik
2. **Quick Stats**: Total SKS (24), IPK (3.85), Mata Kuliah (6), Total Tagihan (Rp 2.5M)
3. **Jadwal Kuliah**: Jadwal harian dengan detail mata kuliah dan dosen
4. **Keuangan**: Ringkasan pembayaran SPP dengan status (Lunas/Pending/Terlambat)
5. **Recent Activities**: Timeline aktivitas akademik terbaru

## Customization

### Mengubah Warna Tema
Edit file `styles.css` dan ubah variabel warna di bagian `:root`:

```css
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
}
```

### Menambah Data Chart
Edit file `script.js` dan ubah data di bagian chart configuration:

```javascript
data: [650, 520, 380, 420, 480, 397], // Data fakultas
labels: ['Teknik', 'Ekonomi', 'Hukum', 'Kedokteran', 'MIPA', 'Sastra']
```

## Browser Support

- ✅ Chrome (Recommended)
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ⚠️ Internet Explorer (Limited support)

## Performance

- **Loading Time**: < 2 detik
- **Responsive**: < 100ms untuk interaksi
- **Charts**: Smooth animations
- **Memory**: Optimized untuk penggunaan minimal

## Security Considerations

- Validasi input pada form login
- Sanitasi data sebelum render
- HTTPS recommended untuk production
- Regular security updates
- Session management (coming soon)

## Roadmap

### Versi 1.1 (Coming Soon)
- [ ] Database integration (MySQL/PostgreSQL)
- [ ] CRUD operations untuk data mahasiswa
- [ ] File upload untuk dokumen mahasiswa
- [ ] Session management dengan JWT

### Versi 1.2
- [ ] API endpoints
- [ ] Mobile app integration
- [ ] Advanced reporting
- [ ] Email notifications
- [ ] Password reset functionality

### Versi 2.0
- [ ] Multi-tenant architecture
- [ ] Advanced analytics
- [ ] Machine learning integration
- [ ] Real-time collaboration
- [ ] Multi-language support

## Contributing

1. Fork proyek ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Contact

- **Email**: admin@siom.edu
- **Website**: https://siom.edu
- **Phone**: +62-21-1234567

## Acknowledgments

- Chart.js untuk visualisasi data yang luar biasa
- Font Awesome untuk icon set yang komprehensif
- Google Fonts untuk tipografi yang modern
- Inter font family untuk readability yang optimal

---

**Dibuat dengan ❤️ untuk optimalisasi pengelolaan data mahasiswa** 