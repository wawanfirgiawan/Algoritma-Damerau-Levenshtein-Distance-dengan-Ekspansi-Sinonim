## 📄 `README.md` – *Algoritma Damerau-Levenshtein Distance dengan Ekspansi Sinonim*

```markdown
# 🔠 Damerau-Levenshtein Distance with Synonym Expansion for String Matching

This project implements a web-based application to match input queries against a dataset using the Damerau-Levenshtein Distance algorithm, enhanced with synonym expansion. Built using **Laravel**, this system is useful for handling typo-tolerant search and intelligent string matching.

---

## 📌 Features

- 🧠 Damerau-Levenshtein Distance algorithm (accounts for insertions, deletions, substitutions, and transpositions)
- 🔁 Synonym expansion to broaden search relevance
- 📦 Dataset stored in SQL and processed via Laravel controllers/models
- 💡 Web interface to input and test string comparisons

---

## ⚙️ Technology Stack

- **Backend**: PHP (Laravel)
- **Frontend**: Blade + Bootstrap (default Laravel setup)
- **Database**: MySQL / MariaDB
- **Tools**: Composer, Artisan, PHPUnit

---

## 📁 Project Structure

```

damerau-apps/
├── app/                 # Core application logic (controllers, models)
├── config/              # Application configuration
├── database/            # Migrations and seeders
├── public/              # Public assets and entry point (index.php)
├── resources/           # Views (Blade templates)
├── routes/              # Route definitions (web.php)
├── storage/             # Log and cache files
├── tests/               # Unit and feature tests
├── vendor/              # Composer dependencies
├── .env                 # Environment settings
├── composer.json        # Dependency management
├── dataset.sql          # Main dataset for string comparison
└── README.md            # Project documentation

````

---

## 🚀 How to Run the Project

### 1. Install Dependencies
```bash
composer install
````

### 2. Create Environment File

```bash
cp .env.example .env
```

### 3. Generate App Key

```bash
php artisan key:generate
```

### 4. Setup Database

* Create a database (e.g., `damerau_app`)
* Import `dataset.sql` into your database
* Edit `.env` to match your DB credentials

### 5. Run the Application

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Example Use Case

1. User inputs a string (e.g., "informasii")
2. System calculates Damerau-Levenshtein distance between the input and dataset entries
3. If a synonym exists (e.g., "data" → "informasi"), the synonym is also matched
4. Results are displayed with ranked similarity scores

---

## 📄 Academic Purpose

This project is part of a research work titled:

> **“Analisis Penerapan Algoritma Damerau-Levenshtein Distance dengan Ekspansi Sinonim untuk Pencarian String Toleran Typo”**

Also includes:

* 📄 `Jurnal.pdf`
* 📄 `Draft Penyetaraan Skripsi.pdf`

---

## 👨‍💻 Author

**Wawan Firgiawan**
Lecturer | AI Researcher | IT Consultant
📧 [Email](mailto:wawan@example.com)
🔗 [GitHub](https://github.com/wawanfirgiawan) | [LinkedIn](https://www.linkedin.com/in/wawan-firgiawan-60a492140)

---

## 📜 License

This project is open source and available under the [MIT License](LICENSE).

````

---

### ✅ Langkah Selanjutnya:

1. Simpan ke file:  
   `/d/Github-Project/Algoritma-Damerau-Levenshtein-Distance-dengan-Ekspansi-Sinonim/damerau-apps/README.md`

2. Jalankan:

```bash
git add damerau-apps/README.md
git commit -m "Add documentation for damerau-apps"
git push
````
