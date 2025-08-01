# Product Requirements Document (PRD)
## Sistem Manajemen Apotek - Laravel

### 1. Technical Stack

**Backend:** Laravel 10.x (PHP 8.1+)  
**Database:** MySQL 8.0+  
**Frontend:** Bootstrap 5.x + Blade Templates  
**Authentication:** Laravel Breeze

### 2. User Roles Berdasarkan Gambar

## 2.1 PELANGGAN
**Akses PUBLIC (tanpa login):**
- Melihat catalog obat di halaman home/landing page
- Mencari dan filter obat
- Melihat detail obat (termasuk stok tersedia dan jumlah terjual)
- Langsung klik "Beli" untuk transaksi

**Akses SETELAH LOGIN:**
- Proses transaksi pembelian langsung
- Melihat histori pembelian sendiri pada home public
- Update profil sendiri pada home public

**Flow Transaksi Pelanggan (Tanpa Keranjang):**
1. Browse obat di home page (tanpa login)
2. Pilih obat yang ingin dibeli
3. Klik "Beli Sekarang" → diminta login/register
4. Setelah login → langsung ke form transaksi (qty, alamat, pembayaran)
5. Konfirmasi pembelian → pesanan masuk ke apoteker untuk diproses

**Batasan Pelanggan:**
- Tidak bisa melihat harga beli/cost obat
- Tidak bisa melihat data supplier
- Tidak bisa mengubah data obat
- Tidak bisa melihat transaksi pelanggan lain
- Tidak bisa akses backend sistem (dashboard apoteker/admin)

## 2.2 APOTEKER
**Akses yang bisa dilakukan:**
- Mengakses dashboard apoteker
- Login dengan email dan password
- Melihat daftar obat (full access - termasuk cost, supplier, stok detail)
- Menambahkan data obat dan detail obat
- Mencari obat (dengan filter advanced)
- Menambah obat baru
- Mengedit/mengubah data obat
- Menghapus obat yang lama
- Mengubah status obat
- Menghapus data obat yang kadaluarsa
- **Melayani transaksi penjualan** (input transaksi atas nama pelanggan)
- Melihat histori penjualan semua customer
- Melihat daftar supplier (read only)
- Melihat daftar pembelian obat

**Batasan Apoteker:**
- Tidak bisa manage user (admin/apoteker lain)
- Tidak bisa CRUD supplier
- Tidak bisa CRUD pelanggan (hanya bisa lihat)

## 2.3 ADMIN
**Akses yang bisa dilakukan (FULL ACCESS):**
- Semua akses Apoteker dan Pelanggan
- Mendaftarkan Apoteker baru
- Mendaftarkan Obat baru
- Mendaftarkan Supplier baru
- Mendaftarkan Pelanggan baru
- Mengedit/hapus semua data master
- Mencari obat yang akan kadaluarsa
- Manage semua user (apoteker, pelanggan)
- Melihat laporan lengkap
- Setting sistem

### 3. Database Design

```sql
-- Users table
users:
- id (primary key)
- name (varchar 255)
- email (varchar 255, unique)
- password (varchar 255)
- role (enum: 'pelanggan', 'apoteker', 'admin')
- phone (varchar 20, nullable)
- address (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

-- Categories table
categories:
- id (primary key)
- name (varchar 100, unique)
- slug (varchar 100, unique)
- description (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

-- Suppliers table
suppliers:
- id (primary key)
- name (varchar 255)
- contact_person (varchar 100)
- phone (varchar 20)
- address (text)
- email (varchar 255)
- created_at (timestamp)
- updated_at (timestamp)

-- Products table
products:
- id (primary key)
- name (varchar 255)
- supplier_id (foreign key to suppliers.id)
- category_id (foreign key to categories.id)
- stock (integer, default 0)
- price (decimal 10,2)
- expired_date (date)
- description (text, nullable)
- image (varchar 255, nullable)
- total_sold (integer, default 0) // total jumlah terjual
- status (enum: 'tersedia', 'habis', 'kadaluarsa')
- created_at (timestamp)
- updated_at (timestamp)

-- Sales table (PENJUALAN - Sales to Customers)
sales:
- id (primary key)
- sale_number (varchar 50, unique) // Sales number
- customer_id (foreign key to users.id, nullable) // untuk walk-in customer
- served_by_user_id (foreign key to users.id) // apoteker yang melayani
- total_amount (decimal 12,2)
- payment_method (enum: 'cash', 'transfer', 'credit_card', 'debit_card', 'e_wallet', 'qris')
- status (enum: 'completed', 'refunded')
- sale_date (timestamp)
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

-- Purchases table (PEMBELIAN - Buying from Suppliers)
purchases:
- id (primary key)
- purchase_number (varchar 50, unique) // PO number
- supplier_id (foreign key to suppliers.id)
- created_by_user_id (foreign key to users.id) // Admin/Apoteker who created
- purchase_date (date)
- total_amount (decimal 12,2)
- payment_method (enum: 'cash', 'transfer', 'credit', 'check')
- status (enum: 'draft', 'ordered', 'received', 'completed', 'cancelled')
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

-- Purchase Details table
purchase_details:
- id (primary key)
- purchase_id (foreign key to purchases.id)
- product_id (foreign key to products.id)
- quantity_ordered (integer)
- quantity_received (integer, default 0)
- unit_cost (decimal 10,2) // Cost price from supplier
- total_cost (decimal 12,2) // quantity_ordered * unit_cost
- product_name_snapshot (varchar 255) // Product name at time of purchase
- expiry_date (date, nullable) // Expiry date of received batch
- batch_number (varchar 50, nullable) // Batch number from supplier
- received_date (date, nullable)
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

-- Sale Details table
sale_details:
- id (primary key)
- sale_id (foreign key to sales.id)
- product_id (foreign key to products.id)
- quantity (integer)
- unit_price (decimal 10,2)
- total_price (decimal 10,2)
- created_at (timestamp)
- updated_at (timestamp)
```

### 4. Functional Requirements Berdasarkan Gambar

#### 4.1 Authentication
- Login menggunakan email dan password
- Role-based access (pelanggan/apoteker/admin)
- Registration untuk pelanggan (self-register)
- Guest checkout (browse catalog tanpa login, login hanya saat checkout)
- Logout functionality

#### 4.2 Public Frontend (Home/Landing Page)
- **Catalog Obat Publik**: Browse semua obat tersedia tanpa login
  - Tampilkan: nama obat, kategori, harga, stok tersedia, jumlah terjual, gambar
  - Button "Beli Sekarang" per obat
- **Search & Filter**: Cari obat by nama, kategori, harga
- **Filter by Category**: Filter obat berdasarkan kategori/jenis
  - Obat Bebas, Obat Bebas Terbatas, Obat Keras
  - Vitamin & Suplemen, Obat Herbal, dll
- **Detail Obat**: Info lengkap obat
  - Nama obat, kategori, harga jual, deskripsi, gambar
  - **Stok tersedia** (contoh: "Stok: 25 tersedia")
  - **Total terjual** (contoh: "127 terjual")
  - Button "Beli Sekarang"
- **Info Apotek**: Kontak, alamat, jam buka

#### 4.3 Area Pelanggan (Setelah Login)
- **Form Transaksi Langsung**: 
  - Input quantity yang ingin dibeli
  - Alamat pengiriman
  - Metode pembayaran
  - Konfirmasi pembelian
- **Dashboard Pelanggan**: Profil & histori pembelian (opsional)
- **Histori Pembelian**: Riwayat transaksi pribadi
- **Update Profil**: Edit data personal

#### 4.4 Dashboard Backend (Apoteker/Admin)
**A. Dashboard Apoteker**: Ringkasan penjualan, stok obat, transaksi hari ini
**B. Dashboard Admin**: Overview lengkap sistem, semua data

#### 4.3 Manajemen Obat
**A. View untuk PELANGGAN (Catalog Mode):**
- **Lihat daftar obat** - Hanya obat yang tersedia untuk dijual
- **Pencarian obat** - Search by nama obat
- **Detail obat** - Info dasar (nama, harga jual, deskripsi)

**B. View untuk APOTEKER/ADMIN (Management Mode):**
- **Daftar obat lengkap** - Semua obat dengan detail cost, supplier, kategori, stok
- **Tambah obat baru** - Form input obat baru dengan kategori (apoteker/admin)
- **Edit detail obat** - Update informasi obat termasuk kategori (apoteker/admin)
- **Hapus obat** - Remove obat dari sistem (apoteker/admin)
- **Pencarian advanced** - Filter by supplier, kategori, status, expired date
- **Status obat** - Update status (tersedia/habis/kadaluarsa)
- **Obat kadaluarsa** - Filter dan hapus obat expired
- **Management Kategori** - CRUD kategori obat (admin only)

#### 4.4 Transaksi & Payment System

**A. PENJUALAN (Sales to Customers):**

**Sales PELANGGAN (Online):**
- **Beli obat** - Add to cart, checkout mandiri
- **Payment Options** - Cash, Transfer, Credit/Debit Card, E-Wallet, QRIS
- **Status pesanan** - Completed, Refunded
- **Histori pembelian sendiri** - Riwayat transaksi pribadi

**Sales APOTEKER (POS/Offline):**
- **Input penjualan** - Melayani customer (walk-in atau registered)
- **Payment Processing** - Handle multiple payment methods (Cash, Transfer, Card, E-Wallet, QRIS)
- **Histori penjualan semua** - Riwayat semua transaksi
- **Detail penjualan** - Info lengkap per transaksi dengan payment info
- **Proses pesanan online** - Handle pesanan dari customer online

**B. PEMBELIAN (Purchase from Suppliers):**

**Purchase Order Management:**
- **Create PO** - Buat purchase order ke supplier
- **PO Status Tracking** - Draft → Ordered → Received → Completed
- **Receiving Management** - Terima barang dari supplier
- **Payment Methods** - Cash, Transfer, Credit, Check

**Inventory Receiving:**
- **Goods Receipt** - Terima barang dari supplier
- **Batch & Expiry Tracking** - Catat batch number dan expiry date
- **Quality Check** - Validasi kualitas barang yang diterima
- **Stock Update** - Auto update stock setelah receiving

**Status Management:**
- **Order Processing** - Track order dari draft sampai completed
- **Receiving Process** - Handle goods receipt dari supplier
- **Inventory Update** - Auto update stock setelah receiving
- **Order Completion** - Mark purchase as completed

#### 4.5 Manajemen Master Data (Admin Only)
- **Daftar supplier** - CRUD supplier
- **Daftar kategori** - CRUD kategori obat
- **Daftar pelanggan** - CRUD customer & registered users
- **Daftar apoteker** - CRUD user apoteker

#### 4.6 Purchase Management (Admin/Apoteker)
- **Purchase Orders** - Create, track, dan manage PO
- **Receiving Management** - Handle barang masuk dari supplier
- **Status Management** - Track order status dari draft sampai completed
- **Purchase Reports** - Laporan pembelian dan analisis cost
- **Supplier Performance** - Monitor performa supplier

#### 4.6 User Management (Admin Only)
- **Daftarkan apoteker baru** - Create user apoteker
- **Daftarkan pelanggan** - Create customer account
- **Edit data user** - Update user info

### 5. Page Structure

```
PUBLIC PAGES (Tidak perlu login)
├── Home/Landing Page
│   ├── Catalog Obat (dengan info stok & jumlah terjual)
│   ├── Search & Filter Obat
│   ├── Detail Obat (stok tersedia, total terjual, deskripsi)
│   └── Info Apotek
│
├── Login/Register Page
│
└── Transaksi Langsung (perlu login untuk proses)

SETELAH LOGIN:
│
├── AREA PELANGGAN (setelah login)
│   ├── Form Transaksi (qty, alamat, pembayaran)
│   ├── Konfirmasi Pembelian
│   ├── Dashboard Pelanggan (opsional - profil & histori)
│   ├── Histori Pembelian
│   └── Update Profil
│
├── DASHBOARD APOTEKER (backend system)
│   ├── Ringkasan Penjualan
│   ├── Manajemen Obat
│   │   ├── Daftar Obat (full access)
│   │   ├── Tambah Obat Baru
│   │   ├── Edit Obat
│   │   └── Obat Kadaluarsa
│   ├── Transaksi POS
│   │   ├── Input Penjualan Baru
│   │   ├── Proses Pesanan Online
│   │   └── Histori Semua Transaksi
│   └── Lihat Data
│       ├── Daftar Supplier (read only)
│       └── Daftar Pelanggan (read only)
│
└── DASHBOARD ADMIN (backend system)
    ├── Overview Sistem
    ├── Manajemen Obat (full access)
    ├── Transaksi & Penjualan (full access)
    │   ├── Sales Transactions
    │   ├── Payment Management
    │   └── Sales Reports
    ├── Purchase Management
    │   ├── Purchase Orders
    │   ├── Receiving Management
    │   ├── Status Management
    │   └── Purchase Reports
    ├── Master Data
    │   ├── Daftar Supplier
    │   ├── Daftar Kategori
    │   └── Daftar Pelanggan
    └── User Management
        ├── Daftar Apoteker
        └── Daftar Pelanggan Terdaftar
```

### 6. User Interface Requirements

#### 6.1 Layout
- Bootstrap 5 responsive design
- Sidebar navigation
- Header dengan user info dan logout
- DataTables untuk listing data
- Modal untuk form input/edit

#### 6.2 Forms
- Tambah/Edit Obat
- Tambah/Edit Supplier
- Tambah/Edit Customer
- Tambah/Edit Apoteker
- Input Penjualan
- Login Form

#### 6.3 Tables
- Daftar Obat (searchable, sortable)
- Histori Penjualan
- Daftar Supplier
- Daftar Customer
- Daftar Apoteker

### 7. Business Rules

#### 7.1 Akses Data Obat
**Pelanggan melihat daftar obat:**
- Hanya obat dengan status 'tersedia'
- Hanya melihat: nama, harga jual, deskripsi, gambar
- Tidak bisa lihat: cost price, supplier, stok detail

**Apoteker/Admin melihat daftar obat:**
- Semua obat (tersedia/habis/kadaluarsa)
- Semua detail: cost, supplier, stok, expired date
- Bisa CRUD obat

#### 7.2 Transaksi
**Transaksi Pelanggan (Online):**
- Customer bisa beli sendiri via sistem
- Otomatis kurangi stok
- Status: pending → apoteker konfirmasi → completed

**Transaksi Apoteker (POS):**
- Apoteker input transaksi atas nama customer
- Bisa untuk walk-in customer atau registered customer
- Langsung completed

#### 7.3 Stock Management
- Stock otomatis berkurang saat penjualan (online/offline)
- Alert ketika stock hampir habis
- Status otomatis berubah ke 'habis' jika stock = 0
- Status otomatis berubah ke 'kadaluarsa' jika melewati expired_date

#### 7.4 User Experience Flow

**Flow Pelanggan:**
1. **Tanpa Login**: Buka website → halaman home dengan catalog obat
2. **Browse**: Lihat obat, search, filter, detail obat
3. **Add to Cart**: Masukkan obat ke keranjang (session based)
4. **Checkout**: Klik checkout → diminta login/register
5. **Login/Register**: Setelah login → lanjut ke checkout
6. **Payment**: Pilih metode pembayaran → konfirmasi pesanan
7. **Dashboard (Optional)**: Akses dashboard untuk lihat histori

**Flow Apoteker/Admin:**
1. **Login**: Langsung ke backend dashboard
2. **Backend System**: Tidak akses public frontend
3. **Manage**: CRUD data, proses pesanan, POS system

### 8. Technical Requirements

#### 8.1 Laravel Features
- Eloquent ORM untuk database operations
- Blade templates untuk views
- Laravel Validation untuk form validation
- Laravel Authentication untuk login/logout
- Middleware untuk role checking

#### 8.2 Security
- Password hashing
- CSRF protection
- SQL injection prevention
- XSS protection

#### 8.3 Performance
- Database indexing
- Eager loading untuk relationships
- Pagination untuk large datasets

### 9. Development Milestones

**Phase 1:**
- Setup Laravel project
- Database migration
- Authentication system
- Basic CRUD obat

**Phase 2:**
- Penjualan system
- User management (admin)
- Master data management

**Phase 3:**
- UI/UX improvements
- Search and filter functionality
- Reports and dashboard

**Phase 4:**
- Testing
- Bug fixes
- Deployment preparation

---
### structure folder Laravel
sistem-apotek/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── PasswordResetLinkController.php
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   └── TransactionController.php
│   │   │   ├── Apoteker/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── TransactionController.php
│   │   │   │   ├── POSController.php
│   │   │   │   └── ReportController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── UserController.php
│   │   │       ├── ProductController.php
│   │   │       ├── SupplierController.php
│   │   │       ├── CustomerController.php
│   │   │       ├── TransactionController.php
│   │   │       └── ReportController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   ├── EnsureUserIsCustomer.php
│   │   │   ├── EnsureUserIsApoteker.php
│   │   │   └── EnsureUserIsAdmin.php
│   │   └── Requests/
│   │       ├── Auth/
│   │       │   ├── LoginRequest.php
│   │       │   └── RegisterRequest.php
│   │       ├── Product/
│   │       │   ├── StoreProductRequest.php
│   │       │   └── UpdateProductRequest.php
│   │       ├── Transaction/
│   │       │   ├── StoreTransactionRequest.php
│   │       │   └── UpdateTransactionRequest.php
│   │       └── User/
│   │           ├── StoreUserRequest.php
│   │           └── UpdateUserRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Supplier.php
│   │   ├── Sale.php (Sales to Customers)
│   │   ├── SaleDetail.php
│   │   ├── Purchase.php (Purchase from Suppliers)
│   │   └── PurchaseDetail.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/
│       ├── ProductService.php
│       ├── TransactionService.php
│       ├── UserService.php
│       └── ReportService.php
│
├── database/
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_suppliers_table.php
│   │   ├── 2024_01_01_000002_create_categories_table.php
│   │   ├── 2024_01_01_000003_create_customers_table.php
│   │   ├── 2024_01_01_000004_create_products_table.php
│   │   ├── 2024_01_01_000005_create_sales_table.php
│   │   ├── 2024_01_01_000006_create_sale_details_table.php
│   │   ├── 2024_01_01_000007_create_purchases_table.php
│   │   └── 2024_01_01_000008_create_purchase_details_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── UserSeeder.php
│   │   ├── SupplierSeeder.php
│   │   ├── CategorySeeder.php
│   │   ├── ProductSeeder.php
│   │   └── PurchaseSeeder.php
│   └── factories/
│       ├── UserFactory.php
│       ├── ProductFactory.php
│       ├── SupplierFactory.php
│       ├── CategoryFactory.php
│       └── TransactionFactory.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── guest.blade.php
│   │   │   ├── admin.blade.php
│   │   │   └── apoteker.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   └── forgot-password.blade.php
│   │   ├── public/
│   │   │   ├── home.blade.php
│   │   │   ├── products/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   └── transaction/
│   │   │       ├── create.blade.php
│   │   │       └── confirm.blade.php
│   │   ├── apoteker/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── products/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── transactions/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── pos.blade.php
│   │   │   │   └── show.blade.php
│   │   │   └── reports/
│   │   │       ├── sales.blade.php
│   │   │       └── stock.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── users/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── products/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── expired.blade.php
│   │   │   ├── categories/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── suppliers/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── customers/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── transactions/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── show.blade.php
│   │   │   │   └── purchases.blade.php
│   │   │   └── reports/
│   │   │       ├── sales.blade.php
│   │   │       ├── financial.blade.php
│   │   │       └── inventory.blade.php
│   │   └── components/
│   │       ├── alert.blade.php
│   │       ├── modal.blade.php
│   │       ├── datatable.blade.php
│   │       └── form-input.blade.php
│   ├── css/
│   │   ├── app.css
│   │   ├── admin.css
│   │   └── public.css
│   └── js/
│       ├── app.js
│       ├── admin.js
│       ├── apoteker.js
│       └── public.js
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── public.php
│   ├── apoteker.php
│   └── admin.php
│
├── public/
│   ├── images/
│   │   ├── products/
│   │   ├── avatars/
│   │   └── logo/
│   ├── css/
│   ├── js/
│   └── storage/
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── products/
│   │   │   ├── avatars/
│   │   │   └── reports/
│   │   └── private/
│   │       └── backups/
│   └── logs/
│
├── config/
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── mail.php
│   └── filesystems.php
│
└── tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   └── RegistrationTest.php
│   ├── Public/
│   │   ├── HomeTest.php
│   │   └── ProductTest.php
│   ├── Customer/
│   │   ├── DashboardTest.php
│   │   └── TransactionTest.php
│   ├── Apoteker/
│   │   ├── ProductManagementTest.php
│   │   └── POSTest.php
│   └── Admin/
│       ├── UserManagementTest.php
│       ├── ProductManagementTest.php
│       └── ReportTest.php
└── Unit/
├── Models/
│   ├── UserTest.php
│   ├── ProductTest.php
│   └── TransactionTest.php
└── Services/
├── ProductServiceTest.php
├── TransactionServiceTest.php
└── UserServiceTest.php

**Catatan:** Sistem ini dirancang sesuai dengan requirements yang terlihat di gambar, fokus pada fungsi inti apotek tanpa fitur tambahan yang berlebihan.