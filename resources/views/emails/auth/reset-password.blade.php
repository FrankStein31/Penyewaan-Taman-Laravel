<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Reset Password</h2>
        
        <p>Halo,</p>
        
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        
        <div style="margin: 30px 0;">
            <a href="{{ $url }}" 
               style="background: #4299e1; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px;">
                Reset Password
            </a>
        </div>
        
        <p>Link reset password ini akan kedaluwarsa dalam {{ config('auth.passwords.users.expire') }} menit.</p>
        
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #edf2f7; margin: 30px 0;">
        
        <p style="color: #718096; font-size: 14px;">
            Terima kasih,<br>
            Sistem Penyewaan Taman
        </p>
    </div>
</body>
</html> 