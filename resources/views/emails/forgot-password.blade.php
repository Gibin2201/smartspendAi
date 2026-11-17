<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f5f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px;">
        <h3 style="color: #696cff; margin-top: 0;">SmartSpendAI Security</h3>
        
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" style="background-color: #696cff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Reset Password</a>
        </div>

        <p style="color: #555;">If you did not request a password reset, no further action is required.</p>
        
        <p style="margin-bottom: 0; color: #888;">Regards,<br><strong>SmartSpendAI Team</strong></p>
    </div>
</body>
</html>