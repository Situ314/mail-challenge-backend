<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Template</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 10px !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }

        p {
            color: #666666;
            font-size: 16px;
            line-height: 1.5;
        }

        .sender {
            margin-top: 20px;
            border-top: 1px solid #dddddd;
            padding-top: 10px;
        }

        .sender p {
            margin: 0;
            color: #999999;
            font-size: 14px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .footer a {
            display: inline-block;
            margin: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous">
</head>
<body>
<div style="text-align: center">
    <img src="{{ asset('img/header-woowup-mailer.png') }}" alt="Woowup Mailer Logo" width="300">
</div>

<div class="container" style="background-color: #f9f9f9;">
    <h1 style="color: #333333; text-align: center">{{ $email->subject }}</h1>
    <p style="color: #666666; text-align: justify">
        {{ $email->body }}
    </p>
    <div class="sender" style="margin-top: 20px; border-top: 1px solid #dddddd; padding-top: 10px; text-align: center">
        <p style="margin: 0; color: #999999;text-align: center">Sent by {{ $email->user->name }}</p>
        <p style="margin: 0; color: #999999;text-align: center">{{ $email->user->email }}</p>
    </div>
    <div class="footer" style="margin-top: 20px; text-align: center;">
        <a href="https://www.linkedin.com/in/cdanielarteaga/" target="_blank">
            <i class="fa fa-linkedin" style="height: 40px; color: #2ca5dd"></i>
        <a href="https://github.com/Situ314" target="_blank">
            <i class="fa fa-github" style="height: 40px; color: #25bca7"></i>
    </div>
</div>
</body>
</html>
