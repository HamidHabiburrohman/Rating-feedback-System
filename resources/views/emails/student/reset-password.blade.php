@component('mail::message')
    # Hai {{ $studentName }},

    Kami menerima permintaan reset password untuk akun Anda di **Itenas Portal**.

    Klik tombol di bawah untuk melanjutkan reset password:

    @component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
        Reset Password
    @endcomponent

    Link ini berlaku selama **60 menit**.
    Jika Anda tidak merasa meminta reset password, abaikan email ini.

    Terima kasih,
    **Tim Itenas Portal**
@endcomponent
