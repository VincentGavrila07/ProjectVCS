<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="{{ route('loginsubmit') }}" method="POST">
    @csrf  <!-- Tambahkan ini -->
    
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
    <a href="{{Route('register')}}">Belum punya akun?</a>
</form>

</body>
</html>