<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <!-- resources/views/register.blade.php -->

<form action="{{ route('registersubmit') }}" method="POST">
    @csrf

    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <label>Kamu Sebagai:</label>
    <input type="radio" name="role" value="1" required> Tutor
    <input type="radio" name="role" value="2" required> Pelajar

    <button type="submit">Register</button>
</form>

</body>
</html>
