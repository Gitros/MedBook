<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie wizyty #{{ $appointment->id }}</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #222; padding: 0; }
        h1 { color: #4f46e5; border-bottom: 3px solid #4f46e5; padding-bottom: 8px; }
        .header { display: table; width: 100%; margin-bottom: 24px; }
        .logo { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .meta { font-size: 11px; color: #666; }
        table.info { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.info td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        table.info td:first-child { font-weight: bold; width: 35%; background: #f9fafb; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 11px; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">MedBook</div>
        <div class="meta">Wygenerowano: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <h1>Potwierdzenie wizyty #{{ $appointment->id }}</h1>

    <table class="info">
        <tr><td>Data i godzina</td><td><strong>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</strong></td></tr>
        <tr><td>Lekarz</td><td>{{ $appointment->doctor->user->name }}</td></tr>
        <tr><td>Specjalizacje lekarza</td><td>{{ $appointment->doctor->specializations->pluck('name')->join(', ') }}</td></tr>
        <tr><td>Gabinet</td><td>{{ $appointment->doctor->room ?? '—' }}</td></tr>
        <tr><td>Cena konsultacji</td><td>{{ number_format($appointment->doctor->consultation_fee, 2) }} zł</td></tr>
        <tr><td>Pacjent</td><td>{{ $appointment->patient->user->name }}</td></tr>
        <tr><td>PESEL</td><td>{{ $appointment->patient->pesel }}</td></tr>
        <tr><td>Telefon</td><td>{{ $appointment->patient->phone }}</td></tr>
        <tr><td>Status</td><td>
            @php $labels = ['pending'=>'Oczekująca','confirmed'=>'Potwierdzona','completed'=>'Zakończona','cancelled'=>'Odwołana']; @endphp
            <span class="status status-{{ $appointment->status }}">{{ $labels[$appointment->status] ?? $appointment->status }}</span>
        </td></tr>
        <tr><td>Powód wizyty</td><td>{{ $appointment->reason }}</td></tr>
        @if($appointment->notes)
            <tr><td>Notatki lekarza</td><td>{{ $appointment->notes }}</td></tr>
        @endif
    </table>

    <div class="footer">
        MedBook — System rejestracji wizyt | Dokument wygenerowany automatycznie
    </div>
</body>
</html>
