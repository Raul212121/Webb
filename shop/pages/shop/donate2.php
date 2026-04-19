<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>ItemShop - Incarca Contul</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #050608 url("background.jpg") center top no-repeat;
            background-size: cover;
            color: #e3d7c0;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at top, rgba(255, 200, 120, 0.18), transparent 55%),
                        radial-gradient(circle at bottom, rgba(0, 0, 0, 0.9), #020308 70%);
        }

        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 60px auto;
            padding: 30px 40px;
            background: radial-gradient(circle at top, rgba(90, 60, 30, 0.75), rgba(5, 5, 8, 0.98));
            border: 1px solid rgba(255, 200, 120, 0.2);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.9);
            border-radius: 6px;
        }

        .title {
            text-align: center;
            font-size: 28px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #f3e0b5;
            text-shadow: 0 0 8px #000, 0 0 12px rgba(255, 180, 80, 0.7);
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #c3b49a;
            margin-bottom: 30px;
        }

        .line-sep {
            height: 2px;
            margin: 0 auto 30px auto;
            max-width: 360px;
            background: linear-gradient(to right,
                transparent,
                rgba(255, 210, 140, 0.8),
                rgba(255, 240, 200, 1),
                rgba(255, 210, 140, 0.8),
                transparent
            );
            box-shadow: 0 0 12px rgba(255, 210, 140, 0.7);
        }

        .package-box {
            max-width: 480px;
            margin: 0 auto;
            padding: 20px 25px;
            border-radius: 6px;
            background: linear-gradient(145deg, rgba(15, 10, 8, 0.95), rgba(45, 30, 20, 0.96));
            border: 1px solid rgba(255, 200, 120, 0.35);
            box-shadow:
                0 0 15px rgba(0, 0, 0, 0.9),
                0 0 10px rgba(255, 180, 80, 0.2);
        }

        .package-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .package-name {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #f9e2b8;
        }

        .package-price {
            font-size: 16px;
            color: #ffd88c;
        }

        .package-details {
            font-size: 13px;
            color: #c9b79a;
            margin-bottom: 20px;
        }

        .coins-highlight {
            color: #ffe9a0;
            font-weight: bold;
        }

        .pay-button-wrapper {
            text-align: center;
            margin-top: 10px;
        }

        .pay-button {
            display: inline-block;
            padding: 12px 40px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #f9d66f;
            background: radial-gradient(circle at top, #ffec9a, #d48a29);
            box-shadow:
                0 0 10px rgba(255, 210, 120, 0.7),
                0 0 20px rgba(180, 90, 20, 0.6);
            color: #3c1b06;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .pay-button span {
            position: relative;
            z-index: 1;
        }

        .pay-button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -80%;
            width: 60%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.7), transparent);
            transform: skewX(-20deg);
            opacity: 0;
        }

        .pay-button:hover::before {
            left: 120%;
            opacity: 1;
            transition: all 0.7s ease;
        }

        .pay-button:hover {
            box-shadow:
                0 0 12px rgba(255, 220, 150, 0.9),
                0 0 24px rgba(200, 110, 30, 0.9);
            transform: translateY(-1px);
        }

        .pay-button:active {
            transform: translateY(1px);
            box-shadow:
                0 0 6px rgba(255, 220, 150, 0.6),
                0 0 12px rgba(140, 70, 20, 0.7);
        }

        .small-note {
            margin-top: 18px;
            font-size: 11px;
            text-align: center;
            color: #9e8f7a;
        }
    </style>
</head>
<body>
<div class="overlay"></div>

<div class="wrapper">
    <div class="title">ItemShop - Incarca contul</div>
    <div class="subtitle">Plata securizata prin Stripe · Card · Apple Pay (unde este disponibil)</div>
    <div class="line-sep"></div>

    <!-- PACHET EXEMPLU: 100 COINS -->
    <div class="package-box">
        <div class="package-header">
            <div class="package-name">Pachet Bronze</div>
            <div class="package-price">5 &euro;</div>
        </div>
        <div class="package-details">
            Primesti <span class="coins-highlight">100 Coins</span> in contul tau dupa confirmarea platii.<br>
            Monedele sunt adaugate automat pe contul conectat in joc.
        </div>

        <div class="pay-button-wrapper">
            <!-- IMPORTANT: modifica domeniul si amount daca vrei alt pachet -->
            <form action="/shop/stripe/create_checkout_session.php" method="GET">
                <input type="hidden" name="amount" value="100">
                <button type="submit" class="pay-button">
                    <span>Cumpara 100 Coins</span>
                </button>
            </form>
        </div>

        <div class="small-note">
            Dupa finalizarea tranzactiei, nu este nevoie sa repornesti jocul. Monedele se vor incarca automat.
        </div>
    </div>
</div>

</body>
</html>
