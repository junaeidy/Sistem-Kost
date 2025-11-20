# Sistem Informasi Manajemen Kost

Sistem Informasi Manajemen Kost dengan Fitur Pencarian & Pemesanan di Medan Sunggal.

## 🌟 Fitur Utama

### 👥 Multi-Role System
- **Admin**: Verifikasi owner, monitoring sistem, manajemen global
- **Owner**: Kelola kost & kamar, terima booking, lihat transaksi
- **Penyewa**: Cari kost, booking kamar, bayar digital

### 💳 Payment Gateway
- Integrasi Midtrans (QRIS, Virtual Account, E-wallet, Credit Card)
- Webhook otomatis untuk update status pembayaran
- Riwayat transaksi lengkap

### 🏠 Manajemen Kost
- Multiple photos per kost
- Rich text editor (CKEditor) untuk deskripsi
- Filter pencarian (harga, lokasi, fasilitas)
- Status ketersediaan real-time

### 🔐 Keamanan
- Password hashing (bcrypt)
- PDO prepared statements (SQL injection prevention)
- CSRF protection
- Session management
- Role-based access control

## 📋 Prasyarat

- PHP >= 7.4
- MySQL >= 5.7
- Apache (XAMPP/WAMP)
- Composer
- Akun Midtrans (untuk payment gateway)

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd kost
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
```
Edit file `.env` dan sesuaikan:
- Database credentials
- Midtrans keys (dapatkan dari dashboard Midtrans)
- App URL

### 4. Setup Database
```bash
# Buat database baru
mysql -u root -p
CREATE DATABASE kost_db;
exit;

# Import schema
mysql -u root -p kost_db < database/schema.sql

# (Opsional) Import data sample
mysql -u root -p kost_db < database/seeders.sql
```

### 5. Setup Permissions
```bash
# Windows (PowerShell as Administrator)
New-Item -ItemType Directory -Force -Path public/uploads/kost
New-Item -ItemType Directory -Force -Path public/uploads/ktp
New-Item -ItemType Directory -Force -Path public/uploads/profile
New-Item -ItemType Directory -Force -Path storage/logs
New-Item -ItemType Directory -Force -Path storage/cache
New-Item -ItemType Directory -Force -Path storage/sessions
```

### 6. Start Development Server
```bash
# Pastikan XAMPP Apache & MySQL sudah running
# Akses aplikasi di browser:
http://localhost/kost/public
```

## 📁 Struktur Folder

```
kost/
├── app/
│   ├── Controllers/        # Controller classes
│   ├── Models/             # Model classes
│   └── Middleware/         # Middleware classes
├── core/                   # Core framework files
│   ├── Database.php
│   ├── Router.php
│   ├── Controller.php
│   └── Model.php
├── config/                 # Configuration files
│   ├── database.php
│   └── app.php
├── database/
│   ├── schema.sql          # Database schema
│   ├── seeders.sql         # Sample data
│   └── migrations/         # Migration files
├── helpers/                # Helper functions
│   └── functions.php
├── public/                 # Public accessible files
│   ├── index.php          # Entry point
│   ├── css/
│   ├── js/
│   └── uploads/           # User uploaded files
├── resources/
│   └── views/             # View templates
│       ├── layouts/
│       ├── admin/
│       ├── owner/
│       ├── tenant/
│       └── auth/
├── routes/
│   └── web.php            # Route definitions
├── storage/               # Storage files
│   ├── logs/
│   ├── cache/
│   └── sessions/
├── .env.example           # Environment template
├── .gitignore
├── composer.json
└── README.md
```

## 🔑 Default Login

### Admin
- Email: `admin@kost.com`
- Password: `admin123`

### Owner (Setelah verifikasi)
- Daftar melalui `/register-owner`
- Tunggu approval dari admin

### Penyewa
- Daftar melalui `/register`
- Langsung bisa login

## 🛠️ Development

### Menjalankan Development Server
```bash
# Gunakan XAMPP atau built-in PHP server
php -S localhost:8000 -t public
```

### Database Migration
```bash
# Jalankan migration (custom script)
php database/migrate.php
```

### Clear Cache
```bash
# Hapus cache files
rm -rf storage/cache/*
```

## 📖 API Documentation

### Payment Webhook (Midtrans)
```
POST /payment/notification
Content-Type: application/json

# Webhook ini di-handle otomatis oleh Midtrans
# URL webhook set di Midtrans dashboard:
# http://yourdomain.com/public/payment/notification
```

## 🧪 Testing

### Midtrans Sandbox
Gunakan test credentials dari Midtrans untuk testing:
- Credit Card: `4811 1111 1111 1114`
- CVV: `123`
- Exp: Any future date

### Test Accounts
Lihat file `database/seeders.sql` untuk test accounts

## 📝 Development Guide

Lihat [DEVELOPMENT_PLAN.md](DEVELOPMENT_PLAN.md) untuk:
- Step-by-step development phases
- Task breakdown
- Technical specifications
- Implementation checklist

## 🔄 Workflow

1. **Owner Registration Flow**
   - Owner daftar → status `pending`
   - Admin verifikasi → status `active`
   - Owner bisa kelola kost

2. **Booking Flow**
   - Penyewa pilih kamar → booking `waiting_payment`
   - Bayar via Midtrans → status `paid`
   - Owner approve → status `active_rent`

3. **Payment Flow**
   - Generate Midtrans transaction
   - Redirect ke payment page
   - Webhook update status
   - Notifikasi ke owner

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👨‍💻 Author

Developed with ❤️ for Sistem Informasi Manajemen Kost

## 📞 Support

Untuk pertanyaan dan dukungan:
- Create an issue di GitHub
- Email: support@sistemkost.com

## 🔮 Roadmap

- [ ] Email notifications
- [ ] Review & rating system
- [ ] Advanced search filters
- [ ] Multi-language support
- [ ] Analytics dashboard
