<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход менеджера</title>
</head>
<body>

<div style="max-width:400px; margin:80px auto; padding:20px; border:1px solid #ccc;">
    <h2>Вход менеджера</h2>

    @if ($errors->any())
        <div style="color:#b00; margin-bottom:10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('manager.login.post') }}">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Пароль</label><br>
            <input type="password" name="password" required style="width:100%;">
        </div>

        <button type="submit">Войти</button>
    </form>
</div>

</body>
</html>