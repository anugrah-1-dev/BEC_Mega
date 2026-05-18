<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran</title>
</head>
<body>

<h2>Form Pendaftaran</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form action="/register" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Nama" required><br><br>

    <input type="email" name="email" placeholder="Email" required><br><br>

    <!-- 🔥 TAMBAHKAN INI -->
    <select name="course_id" required>
        <option value="">Pilih Course</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}">
                {{ $course->name }}
            </option>
        @endforeach
    </select><br><br>

    <button type="submit">Daftar</button>
</form>

</body>
</html>