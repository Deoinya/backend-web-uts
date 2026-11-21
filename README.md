Berikut adalah draf file **`README.md`** yang lengkap dan terstruktur untuk proyek backend Anda, dengan **Laragon** sebagai rekomendasi utama serta opsi alternatif lainnya (**XAMPP** dan **PHP Built-in Server**). Anda bisa menyalin teks di bawah ini dan menyimpannya sebagai `README.md` di folder utama proyek backend.

---

# Backend API - Sistem Peminjaman E-Book (Perpustakaan Digital)

Backend prototipe aplikasi web pengelolaan dan peminjaman e-book berbasis **PHP 8.x OOP Murni** (tanpa framework) dengan penyimpanan data berformat **JSON**.


## Prasyarat Sistem
* **PHP versi 8.0 atau lebih baru** (wajib untuk mendukung *typed properties*, *union types*, dan fitur PHP 8 OOP modern).
* Web server lokal (disarankan **Laragon**).
* *Catatan: Database MySQL tidak perlu dijalankan karena seluruh data disimpan pada file JSON di dalam direktori `data/`.*

---

## Struktur Direktori Backend

```struktur-folder
backend-web-uts/
├── api/                         # Endpoint REST API (JSON Response + CORS)
│   ├── checkout.php             # Memproses transaksi peminjaman e-book
│   ├── get_books.php            # Mengambil daftar katalog e-book
│   ├── get_transactions.php     # Mengambil riwayat transaksi peminjaman
│   ├── login.php                # Autentikasi masuk anggota
│   └── register.php             # Pendaftaran anggota baru
├── data/                        # Penyimpanan data berbasis file JSON
│   ├── books.json               # Data katalog e-book
│   ├── transactions.json        # Data riwayat peminjaman
│   └── users.json               # Data akun pengguna/member
├── src/                         # Kode inti PHP OOP (Namespace: App\*)
│   ├── Controllers/             # Logika penghubung API, Model, dan Storage
│   ├── Helpers/                 # Class utilitas (JsonStorage)
│   └── Models/                  # Entitas OOP (User, Member, EBook, Transaction, RentalRule)
└── README.md

```

---

## Cara Menjalankan Backend

### Opsi 1: Menggunakan Laragon (Sangat Disarankan)

Laragon direkomendasikan karena ringan, otomatis mengatur *virtual host*, dan langsung siap digunakan tanpa konfigurasi tambahan.

1. **Salin Folder Proyek:**
Letakkan folder `backend-web-uts` ke dalam direktori `www` milik Laragon:
```text
C:\laragon\www\backend-web-uts

```


2. **Jalankan Server:**
* Buka aplikasi **Laragon**.
* Klik tombol **Start All** (pastikan service **Apache** atau **Nginx** menyala).


3. **Verifikasi Endpoint:**
Buka browser dan akses URL berikut:
```text
http://localhost/backend-web-uts/api/get_books.php

```


*(Atau via Virtual Host Laragon: `http://backend-web-uts.test/api/get_books.php`)*
Jika berhasil, browser akan menampilkan data katalog buku dalam format JSON.

---

### Opsi 2: Menggunakan XAMPP

Jika perangkat Anda menggunakan XAMPP:

1. **Salin Folder Proyek:**
Letakkan folder `backend-web-uts` ke dalam direktori `htdocs`:
```text
C:\xampp\htdocs\backend-web-uts

```


2. **Jalankan Apache:**
* Buka **XAMPP Control Panel**.
* Klik **Start** pada baris **Apache** (MySQL boleh dibiarkan mati).


3. **Verifikasi Endpoint:**
Akses melalui browser:
```text
http://localhost/backend-web-uts/api/get_books.php

```



---

### Opsi 3: Menggunakan PHP Built-in Server (Tanpa Laragon / XAMPP)

Jika PHP 8.x sudah terpasang di sistem (*Environment Variables / PATH*), Anda dapat langsung menjalankannya lewat terminal VS Code:

1. Buka folder `backend-web-uts` di **VS Code**.
2. Buka terminal (`Ctrl + ``) dan jalankan perintah:
```bash
php -S localhost:8000

```


3. **Verifikasi Endpoint:**
Buka browser dan akses:
```text
http://localhost:8000/api/get_books.php

```


*(Catatan: Jika menggunakan port `8000`, sesuaikan tag `<meta name="api-base" content="http://localhost:8000/api">` pada file HTML frontend Anda).*

---

## Koneksi dengan Frontend

Secara *default*, frontend mengarah ke alamat:

```text
http://localhost/backend-web-uts/api

```

Pastikan folder frontend dijalankan melalui server lokal (seperti ekstensi **Live Server** di VS Code pada `http://127.0.0.1:5500` atau diletakkan di folder `www` Laragon) agar fitur SPA Router dan `fetch` API berjalan tanpa kendala CORS.

---

## Akun Uji Coba Bawaan

Untuk menguji fitur login dan perhitungan diskon otomatis (**Member Premium mendapat diskon 20%**), gunakan kredensial berikut yang sudah tersedia di `data/users.json`:

* **Email:** `budi@email.com`
* **Kata Sandi:** `password`
* **Tipe Anggota:** `Premium`
