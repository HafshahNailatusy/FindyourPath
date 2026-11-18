<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FindYourPath</title>
</head>
<body style="font-family: sans-serif; padding: 40px;">
    <h1>Dashboard Admin</h1>
    <p>Halo, {{ auth()->user()->name }}. Halaman admin belum dikonversi ke Laravel.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Keluar</button>
    </form>
</body>
</html>
