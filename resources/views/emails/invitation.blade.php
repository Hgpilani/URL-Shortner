<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #1f2937;">
    <p>Hello,</p>

    <p>
        You have been invited to join <strong>{{ $companyName }}</strong>
        as <strong>{{ $roleName }}</strong>.
    </p>

    <p>
        Invited user email: <strong>{{ $invitedEmail }}</strong><br>
        Invited by: <strong>{{ $inviterName }}</strong> ({{ $inviterEmail }})
    </p>

    <p>
        Click this link to accept the invitation:
        <a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a>
    </p>

    <p>
        This invitation expires at: <strong>{{ \Illuminate\Support\Carbon::parse($expiresAt)->format('Y-m-d H:i') }}</strong>
    </p>

    <p>If you did not expect this invitation, you can ignore this email.</p>
</body>
</html>
