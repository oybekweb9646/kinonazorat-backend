# MSPD integratsiyasi — API spetsifikatsiyasi

**Tizim:** Oʻzbekiston Respublikasi Madaniyat vazirligi huzuridagi Kinematografiya agentligi — Xavfni tahlil qilish tizimi
**Maqsad:** MSPD tizimiga risk-tahlil (baholash) natijalarini `GET` orqali faqat oʻqish uchun uzatish
**Versiya:** 1.0
**Sana:** 2026-10-29

---

## 1. Umumiy maʼlumot

Integratsiya **server-to-server** rejimida ishlaydi. MSPD tomonidan statik token va ruxsat etilgan IP manzil orqali himoyalangan ikkita `GET` endpoint taqdim etiladi. Hech qanday yozish (`POST/PUT/DELETE`) amali mavjud emas.

| Parametr | Qiymat |
|----------|--------|
| Protokol | HTTPS |
| Format | JSON (UTF-8) |
| Metod | faqat `GET` |
| Base URL (prod) | `https://api.uzbkinonazorat.uz/api` |
| Autentifikatsiya | Statik Bearer token + IP allowlist |
| Ruxsat etilgan IP | `10.190.0.102` (MSPD) |

---

## 2. Autentifikatsiya

Har bir soʻrovda **token** (majburiy) va — IP cheklovi yoqilgan boʻlsa — **ruxsat etilgan IP** shart.

1. **Token** — ikki usuldan biri bilan yuboriladi (ikkalasi ham ishlaydi):
   ```
   X-Api-Token: <TOKEN>
   ```
   yoki
   ```
   Authorization: Bearer <TOKEN>
   ```
   > **Tavsiya:** `X-Api-Token` — eng sodda va xatosiz. `Authorization` ishlatilsa, `Bearer` soʻzi + bitta probel + token boʻlishi shart.
   > Token xavfsiz kanal orqali alohida topshiriladi. Ushbu hujjatga yozilmaydi.

2. **IP manzil** — IP cheklovi yoqilgan boʻlsa, soʻrov faqat ruxsat etilgan IP (`10.190.0.102`) dan kelishi shart. IP tekshiruvi faqat ishonchli (teskari proksi oʻrnatgan) manbadan olinadi — `X-Forwarded-For` orqali soxtalashtirib boʻlmaydi. Cheklov admin panelidan oʻchirilgan boʻlsa (token-only rejimi) — faqat token tekshiriladi.

Shartlar bajarilmasa:

| Holat | HTTP | `message` |
|-------|------|-----------|
| Token yoʻq yoki notoʻgʻri | `401` | `Invalid or missing integration token` |
| IP ruxsat etilmagan | `403` | `IP address is not allowed` |
| Integratsiya sozlanmagan (server tomonida) | `503` | `Integration API is not configured` |

---

## 3. Javob konverti (envelope)

Barcha muvaffaqiyatli javoblar quyidagi koʻrinishda:

```json
{
  "code": 200,
  "success": true,
  "message": "...",
  "data": { }
}
```

Xatolik:

```json
{
  "success": false,
  "code": 404,
  "message": "Organization with given STIR not found",
  "data": []
}
```

> Amaliy maʼlumot faqat `data` ichida boʻladi. `message` xizmatchi maqsadda.

---

## 4. Endpointlar

### 4.1. Risk natijalari roʻyxati

Yakunlangan baholash natijalarini paginatsiya va filtrlar bilan qaytaradi.

```
GET /integration/risk-results
```

**Query parametrlar (barchasi ixtiyoriy):**

| Parametr | Tip | Izoh |
|----------|-----|------|
| `stir` | integer | Subyekt STIR raqami boʻyicha filtr |
| `indicator_type_id` | integer | Indikator turi ID boʻyicha filtr |
| `status` | integer / CSV | Holat boʻyicha filtr (masalan `3` yoki `3,4,10`). Standart: yakunlangan `[3,4,10]` |
| `year` | integer | Yil |
| `quarter` | integer | Chorak (1–4) |
| `month` | integer | Oy (1–12) |
| `date_from` | date `YYYY-MM-DD` | Yaratilgan sanadan (`>=`) |
| `date_to` | date `YYYY-MM-DD` | Yaratilgan sanagacha (`<=`) |
| `page` | integer | Sahifa raqami (standart `1`) |
| `per_page` | integer | Sahifadagi yozuvlar soni (standart `20`, maksimum `100`) |

**Soʻrov namunasi:**

```bash
curl -H "Authorization: Bearer <TOKEN>" \
  "https://api.uzbkinonazorat.uz/api/integration/risk-results?year=2026&per_page=20"
```

**Javob namunasi (`200`):**

```json
{
  "code": 200,
  "success": true,
  "message": "...",
  "data": {
    "items": [
      {
        "request_no": "5647cf95-65e8-439f-95a0-652cc1443004",
        "stir": 123456789,
        "organization": "MCHJ \"Namuna\"",
        "indicator_type": "Kino olish",
        "score": 100,
        "risk_level": "high",
        "status": 3,
        "status_label": "Yakunlangan",
        "period": { "year": 2026, "quarter": 2, "month": 6 },
        "registered_date": null,
        "closed_at": null,
        "order":  { "number": null, "date": null, "inspector": null },
        "act":    { "number": null, "date": null },
        "created_at": "2026-06-21 09:38:02"
      }
    ],
    "meta": {
      "page": 1,
      "per_page": 20,
      "total": 1,
      "last_page": 1
    }
  }
}
```

---

### 4.2. STIR boʻyicha natija

Berilgan STIR boʻyicha subyekt maʼlumotlari va uning barcha yakunlangan natijalarini (har biri indikatorlar kesimida) qaytaradi.

```
GET /integration/risk-results/by-stir/{stir}
```

**Yoʻl parametri:**

| Parametr | Tip | Izoh |
|----------|-----|------|
| `stir` | integer | Subyektning STIR raqami (faqat raqam) |

**Soʻrov namunasi:**

```bash
curl -H "Authorization: Bearer <TOKEN>" \
  "https://api.uzbkinonazorat.uz/api/integration/risk-results/by-stir/123456789"
```

**Javob namunasi (`200`):**

```json
{
  "code": 200,
  "success": true,
  "message": "...",
  "data": {
    "organization": {
      "stir": 123456789,
      "name": "MCHJ \"Namuna\"",
      "address": "Toshkent sh., ...",
      "director": "Aliyev Vali Salimovich",
      "indicator_type": "Kino olish"
    },
    "results": [
      {
        "request_no": "5647cf95-65e8-439f-95a0-652cc1443004",
        "stir": 123456789,
        "organization": "MCHJ \"Namuna\"",
        "indicator_type": "Kino olish",
        "score": 100,
        "risk_level": "high",
        "status": 3,
        "status_label": "Yakunlangan",
        "period": { "year": 2026, "quarter": 2, "month": 6 },
        "registered_date": null,
        "closed_at": null,
        "order":  { "number": null, "date": null, "inspector": null },
        "act":    { "number": null, "date": null },
        "created_at": "2026-06-21 09:38:02",
        "indicators": [
          { "name": "Kino olish 11", "score": 50, "max_score": 50 },
          { "name": "Kino olish 12", "score": 25, "max_score": 25 },
          { "name": "Kino olish 13", "score": 25, "max_score": 25 }
        ]
      }
    ]
  }
}
```

**STIR topilmasa (`404`):**

```json
{
  "success": false,
  "code": 404,
  "message": "Organization with given STIR not found",
  "data": []
}
```

---

## 5. Maydonlar lugʻati

### Natija obyekti (`items[]` / `results[]`)

| Maydon | Tip | Izoh |
|--------|-----|------|
| `request_no` | string (UUID) | Baholashning unikal raqami |
| `stir` | integer | Subyekt STIR raqami |
| `organization` | string | Subyekt nomi (til boʻyicha) |
| `indicator_type` | string | Indikator turi nomi |
| `score` | integer / null | Umumiy ball (0–100) |
| `risk_level` | string / null | Xavf darajasi: `low` / `medium` / `high` |
| `status` | integer | Holat kodi (§6) |
| `status_label` | string | Holat nomi (uz) |
| `period.year` | integer | Yil |
| `period.quarter` | integer | Chorak |
| `period.month` | integer | Oy |
| `registered_date` | date / null | Roʻyxatga olingan sana |
| `closed_at` | date / null | Yopilgan sana |
| `order.number` | string / null | Buyruq raqami |
| `order.date` | date / null | Buyruq sanasi |
| `order.inspector` | string / null | Tekshiruvchi (buyruq boʻyicha) |
| `act.number` | string / null | Akt raqami |
| `act.date` | date / null | Akt sanasi |
| `created_at` | datetime | Yaratilgan vaqt (`YYYY-MM-DD HH:MM:SS`) |
| `indicators[]` | array | Faqat `by-stir`da — indikatorlar kesimi |
| `indicators[].name` | string | Indikator nomi |
| `indicators[].score` | integer / null | Qoʻyilgan ball |
| `indicators[].max_score` | integer | Maksimal ball |

### Subyekt obyekti (`organization`, faqat `by-stir`)

| Maydon | Tip | Izoh |
|--------|-----|------|
| `stir` | integer | STIR raqami |
| `name` | string | Nomi (til boʻyicha) |
| `address` | string / null | Manzil |
| `director` | string / null | Rahbar F.I.Sh. |
| `indicator_type` | string / null | Indikator turi |

---

## 6. Maʼlumotnomalar

### Xavf darajasi (`risk_level`) — ball boʻyicha

| Ball oraligʻi | `risk_level` |
|---------------|--------------|
| 81 – 100 | `high` (yuqori) |
| 61 – 80 | `medium` (oʻrta) |
| 0 – 60 | `low` (past) |

### Holat kodlari (`status`)

| Kod | `status_label` | Izoh |
|-----|----------------|------|
| 1 | Yaratilgan | Draft (standart filtrga kirmaydi) |
| 2 | Qisman baholangan | Draft (standart filtrga kirmaydi) |
| 3 | Yakunlangan | Yakunlangan |
| 4 | Tekshiruvga yuborilgan | Yakunlangan |
| 10 | Arxivlangan | Yakunlangan |

> Standart holatda (`status` berilmasa) faqat **yakunlangan** natijalar — `3`, `4`, `10` — qaytariladi.

---

## 7. Tillar

`Accept-Language` sarlavhasi orqali matnli maydonlar (`organization`, `indicator_type`, `indicators[].name`) tili boshqariladi:

| Qiymat | Til |
|--------|-----|
| `uz` | Oʻzbekcha (lotin) — standart |
| `uzc` | Oʻzbekcha (kirill) |
| `ru` | Ruscha |

Misol: `Accept-Language: ru`

---

## 8. HTTP status kodlari

| Kod | Maʼno |
|-----|-------|
| `200` | Muvaffaqiyatli |
| `401` | Token yoʻq yoki notoʻgʻri |
| `403` | IP ruxsat etilmagan |
| `404` | STIR boʻyicha subyekt topilmadi |
| `503` | Integratsiya server tomonida sozlanmagan |

---

## 9. Qisqa chek-list (MSPD uchun)

- [ ] Token xavfsiz kanal orqali qabul qilindi va saqlandi
- [ ] Soʻrovlar `10.190.0.102` IP manzilidan yuboriladi
- [ ] Har soʻrovda `X-Api-Token: <TOKEN>` (yoki `Authorization: Bearer <TOKEN>`) sarlavhasi bor
- [ ] `GET /integration/risk-results` — roʻyxat olinadi
- [ ] `GET /integration/risk-results/by-stir/{stir}` — STIR boʻyicha olinadi
- [ ] Zarur til uchun `Accept-Language` sarlavhasi qoʻyiladi

---

## 10. Boshqaruv (ichki — admin panel)

Integratsiya tizim ichidan **SUPER_ADMIN** tomonidan boshqariladi: *Sozlamalar → MSPD integratsiyasi* (`/settings/integration`). Sozlamalar DB'da saqlanadi (`.env` faqat dastlabki qiymat uchun).

**Imkoniyatlar:**
- **Token** — joriy tokenni koʻrish, nusxalash va **yangilash** (regenerate). Yangilangach eski token ishlamaydi — MSPD'ga yangisini berish kerak.
- **IP cheklovi** — yoqish/oʻchirish. Oʻchirilsa **token-only** rejimi (istalgan IP'dan, faqat token bilan).
- **Ruxsat etilgan IP'lar** — roʻyxatni tahrirlash (masalan `10.190.0.102`).
- **Loglar** — har bir soʻrov (ruxsat berilgan/rad etilgan, IP, natija, sana) filtr va sahifalash bilan.

**Admin API** (JWT + SUPER_ADMIN, frontend shulardan foydalanadi):

| Metod | Endpoint | Tavsif |
|-------|----------|--------|
| GET | `/api/integration-admin/settings` | Joriy sozlama (token, ip_check_enabled, allowed_ips) |
| POST | `/api/integration-admin/settings` | `ip_check_enabled` va `allowed_ips` ni yangilash |
| POST | `/api/integration-admin/regenerate-token` | Yangi token generatsiya |
| POST | `/api/integration-admin/logs` | Loglar (filtr: reason, client_ip, allowed, date_from/to, page, size) |
| GET | `/api/integration-admin/logs/stats` | Statistika (total/allowed/denied/today) |

**Log natijalari (`reason`):** `ok` (ruxsat), `invalid_token` (xato/yoʻq token), `ip_denied` (IP rad), `not_configured` (token sozlanmagan).
