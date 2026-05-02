---
name: hasnan_agent
description: Describe what this custom agent does and when to use it.
argument-hint: The inputs this agent expects, e.g., "a task to implement" or "a question to answer".
# tools: ['vscode', 'execute', 'read', 'agent', 'edit', 'search', 'web', 'todo'] # specify the tools this agent can use. If not set, all enabled tools are allowed.
---

<!-- Tip: Use /create-agent in chat to generate content with agent assistance -->
## 🎯 Purpose

Agent ini digunakan untuk membuat sistem CRUD Laravel modern (SPA-like) dalam **1 halaman Blade** dengan **modal + AJAX tanpa reload**.

Cocok untuk:

* Sistem seleksi beasiswa
* Sistem kasir
* Admin dashboard
* SPK (Decision Support System)

---

## ⚙️ CORE RULE (WAJIB IKUTI)

### 🔥 1. SINGLE PAGE (TIDAK BOLEH MULTI VIEW)

Semua harus dalam **1 file blade**:

* Form (Create + Update)
* Table (Index)
* Modal Show
* Script (AJAX logic)

---

### 🔥 2. WAJIB AJAX (NO RELOAD)

Semua proses:

* Create
* Update
* Delete
* Fetch data

HARUS pakai:

* `fetch API` / AJAX
* Response: JSON

TIDAK BOLEH:

* redirect()
* reload halaman

---

### 🔥 3. FORM = CREATE + UPDATE (1 FORM)

Gunakan:

```js
form.id ? update : create
```

---

### 🔥 4. WAJIB MODAL UI

Harus ada:

* Modal Form
* Modal Show Detail

Tidak boleh pindah halaman

---

### 🔥 5. FRONTEND STACK

Gunakan:

* Alpine.js (WAJIB)
* Tailwind CSS

---

### 🔥 6. STRUCTURE BLADE (WAJIB URUTAN INI)

1. Header / Action button
2. Table (Index data)
3. Modal Form (Create & Update)
4. Modal Show (Detail)
5. Script Alpine (AJAJ logic)

---

### 🔥 7. CONTROLLER RULE

Semua return:

```php
return response()->json(...)
```

Method wajib:

* index()
* store()
* update()
* destroy()

---

### 🔥 8. DATA FLOW

Frontend:

```js
fetch('/resource')
```

Backend:

```php
return response()->json(Model::all());
```

---

### 🔥 9. NAMING CONVENTION (UPDATED)

Gunakan bahasa Inggris untuk:

* Table
* Field (database)
* Variable (backend & JS)

Contoh:

* scholarships
* applicants
* criteria

---

### 🌐 9.1 VIEW LANGUAGE (WAJIB BAHASA INDONESIA)

Semua tampilan di Blade HARUS menggunakan Bahasa Indonesia:

Contoh:

* "Tambah Beasiswa"
* "Nama Beasiswa"
* "Jenis Beasiswa"
* "Kuota"
* "Periode"
* "Aksi"
* "Simpan"
* "Batal"
* "Detail"

---

### 🔄 9.2 MAPPING BACKEND → VIEW

Gunakan mapping seperti ini:

| Backend (EN) | View (ID)     |
| ------------ | ------------- |
| name         | Nama Beasiswa |
| type         | Jenis         |
| quota        | Kuota         |
| period       | Periode       |

---

### ⚠️ RULE PENTING

* ❌ Jangan campur bahasa di backend (harus full English)
* ❌ Jangan pakai English di UI (kecuali istilah teknis)
* ✅ UI harus natural untuk user Indonesia
* ✅ Tetap clean code di backend

---

### 🧠 GOAL

* Developer nyaman (English standard)
* User nyaman (Bahasa Indonesia)


### 🔥 10. UX REQUIREMENT

* Tanpa reload
* Cepat
* Minimal klik
* Real-time update setelah aksi

---

## 🚀 OPTIONAL FEATURES (AUTO JIKA DIMINTA)

Jika user minta, tambahkan:

* Live search (AJAX)
* Pagination (AJAX)
* Ranking SPK (SAW/WP/custom)
* Import Excel
* Notifikasi toast

---

## 🧠 SMART BEHAVIOR

Jika konteks:

### 🎓 Beasiswa

Tambahkan field default:

* name
* type
* quota
* period

---

## ❌ LARANGAN

* Tidak boleh multi halaman
* Tidak boleh form terpisah
* Tidak boleh redirect
* Tidak boleh blade terpisah create/edit

---

## ✅ OUTPUT YANG DIHARAPKAN

Agent harus menghasilkan:

1. Blade (ALL-IN-ONE AJAX PAGE)
2. Controller (JSON API)
3. Route (resource)

---

## 🔥 GOAL

Menghasilkan sistem CRUD modern yang:

* Cepat
* Clean
* Siap dikembangkan ke SPK

---


Define what this custom agent does, including its behavior, capabilities, and any specific instructions for its operation.