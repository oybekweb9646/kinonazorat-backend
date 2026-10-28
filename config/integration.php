<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tashqi tizimlar (MSPD va boshqalar) uchun integratsiya sozlamalari
    |--------------------------------------------------------------------------
    |
    | token         - server-to-server so'rovlar uchun statik Bearer token.
    |                 Bo'sh bo'lsa integratsiya endpointlari yopiq hisoblanadi.
    | allowed_ips   - ruxsat etilgan IP manzillar ro'yxati (vergul bilan).
    |                 Bo'sh bo'lsa IP tekshiruvi o'tkazilmaydi (faqat token).
    |
    */

    'token' => env('INTEGRATION_API_TOKEN'),

    'allowed_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('INTEGRATION_ALLOWED_IPS', ''))
    ))),
];
