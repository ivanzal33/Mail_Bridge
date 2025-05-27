<!-- Шапка -->
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td>
            <h2 style="margin-bottom: 0;">Ваш код двухфакторной аутентификации</h2>
            <p style="margin-top: 0; color: #555;">От кого: mailbridje.ru</p>
        </td>
    </tr>
</table>

<!-- Тело -->
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td>
            <p>Здравствуйте!</p>
            <p>
                Вы получили это письмо, потому что для вашей учётной записи MailBridge был запрошен код двухфакторной аутентификации.
            </p>
            <p style="font-size: 18px; font-weight: bold; background: #f1f1f1; display: inline-block; padding: 10px 20px; border-radius: 5px;">
                Ваш код: {{ $user->two_factor_code }}
            </p>
            <p>
                Если вы не запрашивали этот код, просто проигнорируйте это письмо.<br>
                В целях безопасности рекомендуем сменить пароль, если вы подозреваете несанкционированный доступ к вашей учётной записи.
            </p>
            <p>С уважением,<br>Команда MailBridge</p>
        </td>
    </tr>
</table>
