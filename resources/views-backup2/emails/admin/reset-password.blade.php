@component('mail::message')
# Hai {{ $adminName }},

Kami menerima permintaan reset password untuk akun admin Anda di **Unit Rating Feedback**.

Klik tombol di bawah untuk melanjutkan reset password:

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
Reset Password
@endcomponent

Link ini berlaku selama **60 menit**.  
Jika Anda tidak merasa meminta reset password, abaikan email ini.

Terima kasih,  
**Tim Unit Rating Feedback**
@endcomponent