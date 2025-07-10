# Dokumentasi Fitur Profil User - SIOM

## Overview
Fitur profil user memungkinkan pengguna untuk melihat informasi detail tentang akun mereka, termasuk data umum dan data spesifik berdasarkan role (mahasiswa/dosen).

## Fitur yang Ditambahkan

### 1. API Profil User (`api/get_user_profile.php`)
- **Fungsi**: Mengambil data profil user yang sedang login
- **Method**: GET
- **Response**: JSON dengan data user dan data spesifik role
- **Logging**: Menyimpan log debug di `logs/profile_debug.log`

### 2. Halaman Profil User (`pages/user-profil.html`)
- **Fungsi**: Menampilkan profil user dengan UI yang menarik
- **Fitur**:
  - Header profil dengan avatar dan informasi dasar
  - Informasi umum (username, nama, email, role, tanggal registrasi)
  - Informasi spesifik role (mahasiswa: NIM, fakultas, prodi, angkatan, status)
  - Responsive design
  - Error handling

### 3. Logging System
- **API Debug Log**: `logs/api_debug.log` - untuk debugging API mahasiswa
- **Profile Debug Log**: `logs/profile_debug.log` - untuk debugging API profil

## Cara Penggunaan

### Untuk Mahasiswa:
1. Login dengan username NIM (contoh: 234234)
2. Klik menu "Profil Saya" di sidebar
3. Halaman akan menampilkan:
   - Informasi umum dari tabel `users`
   - Informasi akademik dari tabel `mahasiswa`

### Untuk Dosen:
1. Login dengan username NIDN
2. Klik menu "Profil Saya" di sidebar
3. Halaman akan menampilkan:
   - Informasi umum dari tabel `users`
   - Informasi dosen dari tabel `dosen`

## Struktur Data

### Response API Profil:
```json
{
  "status": "success",
  "data": {
    "user": {
      "id": 1,
      "username": "234234",
      "full_name": "riyan",
      "email": "riyan@example.com",
      "role": "mahasiswa",
      "created_at": "2024-01-01 00:00:00"
    },
    "role_specific_data": {
      "nim": "234234",
      "nama_lengkap": "riyan",
      "nama_fakultas": "Teknik",
      "nama_prodi": "Informatika",
      "angkatan": "2024",
      "status": "Aktif"
    }
  }
}
```

## Troubleshooting

### Jika terjadi error "terjadi kesalahan saat ambil data":

1. **Cek Log Files**:
   - Buka file `logs/api_debug.log` untuk melihat error API mahasiswa
   - Buka file `logs/profile_debug.log` untuk melihat error API profil

2. **Cek Session**:
   - Pastikan user sudah login
   - Pastikan session tidak expired
   - Pastikan role sesuai (mahasiswa/dosen)

3. **Cek Database**:
   - Pastikan data user ada di tabel `users`
   - Pastikan data mahasiswa/dosen ada di tabel yang sesuai
   - Pastikan relasi antara username dan NIM/NIDN benar

### Test Pages:
- `test_profile_api.php` - Test API dengan curl
- `test_profile_page.html` - Test API di browser

## Keamanan
- API memvalidasi session login
- API memvalidasi role user
- Data hanya ditampilkan untuk user yang login
- Password tidak ditampilkan di profil

## Pengembangan Selanjutnya
- Fitur edit profil
- Upload foto profil
- Riwayat login
- Notifikasi profil 