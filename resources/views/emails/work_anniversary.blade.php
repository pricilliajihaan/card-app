<!DOCTYPE html>
<html>
<head>
    <title>Work Anniversary</title>
    <style>
        .email-container {
            max-width: 800px; /* Ubah sesuai lebar maksimal yang diinginkan */
            margin: 0 auto; /* Memusatkan konten email */
            padding: 20px; /* Memberikan sedikit ruang di sekitar konten */
        }
        .ecard-image {
            max-width: 400px; /* Ubah sesuai lebar maksimal gambar yang diinginkan */
            width: 100%; /* Menyesuaikan dengan lebar kontainer */
            height: auto; /* Mempertahankan rasio aspek */
            display: block; /* Menampilkan gambar sebagai blok */
            margin: 0 auto; /* Memusatkan gambar */
        }
    </style>
</head>
<body>
    <div class="email-container">
        <p>Dear {{ $user->name }},</p>
        <p>Congratulations on your {{ $user->years_of_service }} year(s) of service! We appreciate your hard work and dedication.</p>
        <p>Here is your work anniversary card.</p>
        <img src="{{ $image }}" alt="Work Anniversary Card" class="ecard-image">
        <p>
            <a href="{{ route('send.reminder', ['user' => $user->id]) }}">
                Click here to send a reminder email to Finance
            </a>
        </p>
        <p>Best Regards,</p>
        <p>MUC Consulting Management</p>
    </div>
</body>
</html>
