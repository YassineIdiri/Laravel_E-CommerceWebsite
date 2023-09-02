<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Récapitulatif de votre commande</title>
    <style>
        body { font-size: 15px; color: rgba(0,0,0,.4); }
        h2 { font-size: 24px; font-weight: 400; }
        h3 { font-size: 18px; font-weight: 400; }
        a { color: #17bebb; text-decoration: none; }
        .btn { padding: 10px 15px; display: inline-block; border-radius: 5px; background: #17bebb; color: #ffffff; }
    </style>
</head>
<body style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">
    <div style="max-width: 600px; margin: 0 auto; background-color : white; padding : 5px;">
        <table cellspacing="0" cellpadding="0" width="100%" style="margin: auto;">
            <tr>
                <td style="text-align: left;">
                    <h1><a href="#">Online Shop</a></h1>
                </td>
            </tr>
            <tr>
                <td style="padding: 2em 0;">
                    <h2>{{$name}}, voivi le récapitulatif de votre commande</h2>
                    <hr>
                </td>
            </tr>
            <tr>
                <table width="100%">
                    @foreach($order as $article)
                    <tr>
                        <td width="80%" style="text-align:left;">
                            <div>
                                <div>
                                    <h3 style="color:black;">{{$article->article}}</h3>
                                    <span>{{$article->category}}</span>
                                    <p>Qt: {{$article->quantity}}</p>
                                </div>
                            </div>
                        </td>
                        <td width="20%" >
                            <span style="color: #000; font-size: 20px;">{{$article->price}}€</span>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </tr>
        </table>
    </div>
</body>
</html>
