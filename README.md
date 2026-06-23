# MedBook — System rejestracji wizyt w przychodni

Projekt zaliczeniowy z przedmiotu **Programowanie Zaawansowanych Serwisów Internetowych**.
Aplikacja webowa zbudowana w Laravel 12 umożliwiająca rezerwację wizyt lekarskich, zarządzanie kalendarzem lekarza oraz pełną administrację przychodnią.

## Stack technologiczny

- **Laravel 12** (PHP 8.4) — framework backendowy
- **MySQL** (XAMPP) — baza danych
- **Blade** — silnik szablonów
- **Tailwind CSS 3** + **Alpine.js** — frontend (przez Laravel Breeze)
- **Vite** — bundler assetów
- **DomPDF** (`barryvdh/laravel-dompdf`) — generowanie PDF
- **Laravel Breeze** — scaffolding uwierzytelniania

## Funkcjonalności

### Role użytkowników
- **Pacjent** — rejestracja online, przeglądanie lekarzy, rezerwacja wizyt, edycja własnych danych, pobieranie PDF
- **Lekarz** — kalendarz wizyt (dzisiejsze + nadchodzące), wystawianie notatek, zmiana statusu
- **Administrator** — pełny CRUD na wszystkich modelach, eksport CSV wizyt, statystyki

### Główne moduły
- Specjalizacje (CRUD)
- Lekarze (CRUD + przypisanie wielu specjalizacji)
- Pacjenci (CRUD + rejestracja publiczna jako pacjent)
- Wizyty (rezerwacja, statusy: oczekująca/potwierdzona/zakończona/odwołana)
- Wyszukiwanie i filtrowanie w każdej liście
- Eksport CSV listy wizyt (z BOM dla polskich znaków w Excelu)
- PDF potwierdzenie wizyty

## Struktura bazy danych

5 powiązanych tabel + tabela pivot dla relacji M:N:

```
users (id, name, email, password, role, is_active, deleted_at)
  └─ doctors (user_id FK) ─┐
  └─ patients (user_id FK) │
                           │
specializations            ├─ doctor_specialization (pivot M:N)
  │                        │
  └────────────────────────┘
appointments (doctor_id FK, patient_id FK, specialization_id FK, date, status, ...)
```

**Relacje:**
- `User 1:1 Doctor` / `User 1:1 Patient`
- `Doctor N:M Specialization` (pivot `doctor_specialization`)
- `Doctor 1:N Appointment`
- `Patient 1:N Appointment`
- `Specialization 1:N Appointment` (nullable)

## Wykorzystane mechanizmy Laravel

| Mechanizm | Zastosowanie |
|-----------|--------------|
| **Eloquent ORM** | Wszystkie modele + relacje (`hasOne`, `belongsTo`, `belongsToMany`, `hasMany`) |
| **Migracje** | Tworzenie schematu DB krok po kroku z FK i indeksami |
| **Seedery + Factories** | 30 lekarzy, 80 pacjentów, 200 wizyt z Fakerem |
| **Form Requests** | Walidacja w osobnych klasach (Store/Update × 4 modele) |
| **Custom Validation Rule** | `ValidPesel` — walidacja sumy kontrolnej PESEL |
| **Policies + Gate** | Autoryzacja per model + per akcja (`$this->authorize()`) |
| **Middleware** | Custom `CheckRole` dla ról + wbudowane `auth` |
| **Soft Deletes** | Trait `SoftDeletes` na 4 modelach (kolumna `deleted_at`) |
| **Blade Components** | Custom `<x-avatar>` z inicjałami i deterministyczną kolorystyką |
| **Resource Routes** | `Route::resource('appointments', ...)` |
| **Route Model Binding** | Auto-load modelu z URL: `Doctor $doctor` |
| **Query Builder** | Wyszukiwanie z `whereHas`, dynamicznymi filtrami |
| **Paginacja** | `paginate()` z customowym widokiem `vendor/pagination/tailwind` |
| **Transakcje DB** | `DB::transaction()` przy tworzeniu User+Doctor/Patient (atomowość) |
| **Hashing haseł** | `Hash::make()` (bcrypt — automatyczne przez Breeze) |
| **CSRF** | `@csrf` we wszystkich formularzach |
| **Service Provider** | `AppServiceProvider::boot()` — globalna konfiguracja paginacji |
| **Streamed Response** | Eksport CSV bez zużywania pamięci (`streamDownload` + `chunk`) |

## Instalacja i uruchomienie

### Wymagania
- PHP ≥ 8.2
- Composer
- Node.js + npm
- MySQL (XAMPP/WAMP/Laragon)

### Setup

```bash
# 1. Klonowanie / pobranie projektu
cd C:\xampp\htdocs\Projekt\MedBook

# 2. Instalacja zależności
composer install
npm install

# 3. Konfiguracja środowiska
copy .env.example .env
php artisan key:generate
```

W pliku `.env` ustaw dostęp do MySQL:

```
DB_DATABASE=medbook
DB_USERNAME=root
DB_PASSWORD=
```

### Stworzenie bazy

W phpMyAdmin:
```sql
CREATE DATABASE medbook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Migracja i seed

```bash
php artisan migrate:fresh --seed
```

### Uruchomienie

```bash
# Terminal 1
php artisan serve

# Terminal 2 (asset bundler)
npm run dev
```

Aplikacja: `http://127.0.0.1:8000`

## Konta testowe (po seedzie)

| Rola | E-mail | Hasło |
|------|--------|-------|
| Administrator | `admin@medbook.pl` | `password` |
| Lekarz | `doctor@medbook.pl` | `password` |
| Pacjent | `patient@medbook.pl` | `password` |

Plus 30 losowych lekarzy i 80 pacjentów (dostęp przez panel admina).

## Struktura katalogów (najważniejsze)

```
app/
├── Http/
│   ├── Controllers/         # 6 kontrolerów (CRUD-y + Dashboard + Auth)
│   ├── Middleware/          # CheckRole
│   └── Requests/            # 8 Form Request — walidacja
├── Models/                  # User, Doctor, Patient, Specialization, Appointment
├── Policies/                # 4 policies — autoryzacja per akcja
├── Providers/               # AppServiceProvider — pagination
└── Rules/                   # ValidPesel — checksum PESEL

database/
├── factories/               # Faker dla wszystkich modeli
├── migrations/              # 9 migracji
└── seeders/                 # DatabaseSeeder

resources/
├── views/
│   ├── auth/                # login.blade, register.blade (custom MedBook style)
│   ├── components/          # avatar.blade.php
│   ├── dashboards/          # admin / doctor / patient
│   ├── doctors/, patients/, specializations/, appointments/
│   ├── layouts/             # app, guest, navigation
│   ├── vendor/pagination/   # custom paginacja indigo
│   └── welcome.blade.php    # landing page

routes/
├── web.php                  # główne routy + grupy middleware role
└── auth.php                 # routy Breeze (login/register/logout)
```

## Walidacja — przykłady reguł

- **PESEL** — 11 cyfr + suma kontrolna (algorytm wagowy 1,3,7,9,...)
- **Email** — `unique:users,email`, lowercase
- **Nr licencji** — regex `^[A-Z]{3}-\d{7}$` (np. `LEK-1234567`)
- **Cena konsultacji** — `min:0`, `max:10000`, `numeric`
- **Telefon** — regex `^[0-9 +\-]+$`
- **Data urodzenia** — `before:today`, `after:1900-01-01`
- **Termin wizyty** — `after:now` + sprawdzanie kolizji w bazie
- **Powód wizyty** — `min:5`, `max:500`
- **Specjalizacje lekarza** — wymagana min. 1

Custom message po polsku dla każdej reguły.

## Realizacja wymagań projektu

| Wymaganie | Status |
|-----------|--------|
| Min. 5 tabel z FK | ✓ (users, doctors, patients, specializations, appointments + pivot) |
| Relacja M:N | ✓ (doctor_specialization) |
| CRUD każdego modelu | ✓ |
| Dezaktywacja zamiast DELETE | ✓ (SoftDeletes + flaga `is_active`) |
| Wyszukiwanie w każdej tabeli | ✓ |
| Walidacja (min 5 niestandardowych) | ✓ (PESEL checksum, format licencji, cena ≥0, kolizja terminu, regex telefonu, ...) |
| Wzorzec MVC | ✓ (Modele Eloquent, Controllery zwracające widoki Blade) |
| Query params / URL params / POST | ✓ (`?search=&specialization=`, `/doctors/{id}`, formularze) |
| Rejestracja + logowanie z hashowaniem | ✓ (Breeze + bcrypt) |
| Dodatkowe mechanizmy Laravel | ✓ (Policies, SoftDeletes, Custom Rule, Components, PDF, CSV) |

## Licencja

Projekt edukacyjny — Politechnika.
