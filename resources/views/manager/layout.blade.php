<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Админка менеджера')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f7;
            color: #222;
        }

        header {
            background: #ffffff;
            border-bottom: 1px solid #ddd;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .title {
            font-size: 18px;
            font-weight: bold;
        }

        header .user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        main {
            padding: 20px;
        }

        button {
            cursor: pointer;
        }

        a {
            color: #0a58ca;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <div class="title">
        Админка менеджера
    </div>

    <div class="user">
        <span>{{ auth()->user()->email }}</span>

        <form method="POST" action="{{ route('manager.logout') }}">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </div>
</header>

<main>
    @yield('content')
</main>

</body>
</html>