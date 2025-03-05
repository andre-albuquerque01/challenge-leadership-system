<div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        h2 {
            color: #333333;
        }

        p {
            color: #555555;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        p.signature {
            margin-top: 40px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Olá,</h2>
        <p>Recebemos uma solicitação para recuperação de senha associada à sua conta. Para continuar o processo de
            recuperação, clique no botão abaixo:</p>

        <p>Seu token para recuperação de senha é:</p>

        <a href="{{ env('PATH_RECOVER_PASSWORD') }}/{{ $data['token'] }}">Recuperar Senha</a>

        <p>Este link de recuperação é válido por 15 minutos. Após este período, será
            necessário solicitar uma nova recuperação de senha.</p>

        <p>Se você não solicitou a recuperação de senha, pode ignorar este e-mail.</p>

        <p class="signature">Obrigado,<br>Equipe de Suporte</p>
    </div>
</body>

</html>