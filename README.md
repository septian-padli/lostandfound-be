# LostAndFound API

RESTful API backend untuk aplikasi LostAndFound, yang berfungsi untuk mempermudah masyarakat dalam melaporkan atau mencari barang hilang.

## ✨ Fitur Utama

- 🔑 Autentikasi Google OAuth
- 🧑‍💻 Manajemen User (Admin & User Biasa)
- 🏷️ CRUD Kategori Barang
- 📦 CRUD Barang Hilang
- 🛠️ Update Status "Ditemukan"
- 💬 Komentar & Balasan di setiap barang
- 🔍 Search & Filter berdasarkan kota dan kategori
- 📤 Share ke sosial media (Telegram, Instagram, X)
- 🌆 Data master Provinsi dan Kota
- 🔒 Token-based Authentication (JWT)

---

## 📚 API Documentation

API Specification tersedia dalam format **OpenAPI 3.0.3**:
- File: [`api.json`](./api.json)
- Base URL (development): `http://localhost:8889`

---

## 🚀 Tech Stack

- **Framework:** Laravel 12
- **Database:** MySQL / PostgreSQL
- **Authentication:** Google OAuth 2.0 (Socialite / Passport / Sanctum)
- **Storage:** Local / S3 (untuk gambar barang)
- **API Format:** REST API (JSON)

---

## 🔧 Instalasi & Setup

### 1. Clone Repository
```bash
git clone https://github.com/your-username/lostandfound-api.git
cd lostandfound-api
