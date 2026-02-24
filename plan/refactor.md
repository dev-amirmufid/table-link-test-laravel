# Refactor Plan - Architecture Redesign (Interpretasi B)

## Problem

Requirement menyatakan:
- Line 129: "Data is **provided via REST API endpoints**"
- Line 154: "Data is **provided via REST API endpoints**"
- Line 164: "Clean separation between **API and UI logic**"

Dengan arsitektur lama (Web Controller memanggil API server-side):
- API ada tapi TIDAK digunakan oleh View
- Tidak ada "clean separation" sejati

## Solution Architecture (Interpretasi B)

```
┌─────────────────────────────────────────────────────────────┐
│                     BROWSER                                  │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  HTML/Blade (halaman kosong/minimal)               │   │
│  │                                                    │   │
│  │  JavaScript (Axios) ──────────────────────────┐ │   │
│  │                                                │ │   │
│  │  AJAX ke API ─────────────────────────────►    │ │   │
│  │                                                │ │   │
│  │  Render JSON ke DOM                           │ │   │
│  └────────────────────────────────────────────────┘ │   │
└──────────────────────────────────────────────────────┬─────┘
                                                   │ HTTP/AJAX
                                                   ▼
┌─────────────────────────────────────────────────────────────┐
│  API Layer (Laravel)                                       │
│  - /api/users    → JSON                                   │
│  - /api/flights → JSON                                   │
│  - /api/dashboard/charts → JSON                          │
│                                                              │
│  API Controllers → Services → Models                        │
└─────────────────────────────────────────────────────────────┘
```

## Mengapa Interpretasi B Lebih Sesuai?

| Alasan | Penjelasan |
|--------|------------|
| Requirement | Data WAJIB dari API endpoints |
| API menjadi functional | API benar-benar terpakai |
| Clean separation | View tidak tahu Services, hanya tahu API |
| Modern approach | SPA-like experience |

## Technology Stack

| Tool | Status | Kegunaan |
|------|--------|----------|
| **Vite** | ✅ Terinstall | Modern bundler |
| **Axios** | ✅ Terinstall | HTTP Client untuk AJAX |
| **ES Modules** | ✅ Aktif | JavaScript modules |
| **Tailwind CSS v4** | ✅ Terinstall | Styling |
| **Chart.js** | ✅ Terinstall | Charts |

## Implementation Plan

### Phase 1: Setup JavaScript API Client

Buat helper module untuk API calls:

```
resources/js/
├── api.js           # Fungsi helper untuk API calls
├── bootstrap.js    # (sudah ada) Konfigurasi Axios
└── app.js          # (sudah ada) Entry point
```

**resources/js/api.js:**
```javascript
import axios from 'axios';

// Set base URL
const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
    }
});

// Interceptor untuk attach token
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="api-token"]')?.content;
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
```

### Phase 2: Update Views dengan AJAX

Semua View mengambil data via JavaScript:

```
resources/views/
├── users/
│   ├── index.blade.php   # Load users via Axios
│   ├── create.blade.php  # Submit form via Axios
│   ├── edit.blade.php    # Load & update via Axios
│   └── show.blade.php    # Load detail via Axios
├── flights/
│   └── index.blade.php   # Load flights via Axios
└── dashboard/
    └── admin.blade.php   # Load charts via Axios
```

### Phase 3: Setup API Token di HTML

Tambahkan meta tag di layout untuk API token:

```html
<!-- resources/views/layouts/app.blade.php -->
<head>
    <meta name="api-token" content="{{ session('api_token') }}">
</head>
```

### Phase 4: Sederhanakan Web Controllers

Web Controllers hanya render view, tidak perlu Services:

```php
// WebUserController.php - Sangat sederhana
class WebUserController extends Controller
{
    public function index() { return view('users.index'); }
    public function create() { return view('users.create'); }
    public function edit($id) { return view('users.edit', ['id' => $id]); }
    public function show($id) { return view('users.show', ['id' => $id]); }
}
```

### Phase 5: Auth/Login Flow

1. User login via form → WebAuthController
2. WebAuthController call API → dapat token
3. Simpan token di session + meta tag
4. JavaScript baca token dari meta tag → attach ke Axios requests

## File Changes Summary

### Ditambah:
- `resources/js/api.js` - API helper functions

### Diubah:
- `resources/views/layouts/app.blade.php` - Tambah meta tag untuk token
- `resources/views/users/*.blade.php` - AJAX data loading
- `resources/views/flights/*.blade.php` - AJAX data loading
- `resources/views/dashboard/admin.blade.php` - AJAX charts
- `app/Http/Controllers/Web/*.php` - Sederhanakan (hanya render view)

### Dihapus:
- Tidak ada penghapusan, hanya refactor

## Arsitektur Final

```
┌─────────────────────────────────────────────────────────────┐
│  LAYER               │  RETURNS      │  AKSES               │
├──────────────────────┼───────────────┼──────────────────────┤
│  JavaScript/Axios   │  -           │  API Endpoints        │
│  (Browser)          │              │  (JSON)              │
├──────────────────────┼───────────────┼──────────────────────┤
│  API Controllers    │  JSON        │  Services            │
│  (Laravel)          │              │  Models              │
├──────────────────────┼───────────────┼──────────────────────┤
│  Services           │  -           │  Models              │
│  (Laravel)          │              │  Database            │
├──────────────────────┼───────────────┼──────────────────────┤
│  Models             │  -           │  Database            │
│  (Laravel)          │              │                      │
└──────────────────────┴───────────────┴──────────────────────┘
```

## Benefits

1. **API benar-benar functional** - Tidak hanya ada, tapi digunakan
2. **Clean separation** - UI tidak tahu business logic, hanya tahu API
3. **Modern SPA-like feel** - Tanpa page reload untuk data
4. **Reusable API** - API bisa digunakan untuk mobile app juga
5. **Requirement terpenuhi** - "Data provided via REST API endpoints"

## Prinsip

- **View** = HTML + JavaScript (minimal PHP)
- **JavaScript** = AJAX calls ke `/api/*` endpoints
- **API Controllers** = Return JSON
- **Services** = Business logic (tidak diakses View)
- **Models** = Data access
