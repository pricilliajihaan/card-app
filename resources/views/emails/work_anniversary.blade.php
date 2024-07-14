<!DOCTYPE html>
<html>
<head>
    <title>Work Anniversary</title>
    <style>
        .email-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .ecard-image {
            max-width: 400px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
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
