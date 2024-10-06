<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/src/css/main.css">
    <style type="text/css">

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        #notfound {
            position: relative;
            height: 100vh;
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .notfound {
            max-width: 520px;
            width: 100%;
            line-height: 1.4;
            text-align: center;
        }

        .notfound .notfound-error {
            position: relative;
            height: 200px;
            margin: 0px auto 20px;
            z-index: -1;
        }

        .notfound .notfound-error h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 200px;
            font-weight: 300;
            margin: 0px;
            color: #211b19;
            position: absolute;
            left: 50%;
            top: 50%;
                -webkit-transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
        }

        @media only screen and (max-width: 767px) {
            .notfound .notfound-error h1 {
                font-size: 148px;
            }
        }

        @media only screen and (max-width: 480px) {
            .notfound .notfound-error {
            height: 148px;
            margin: 0px auto 10px;
        }
        .notfound .notfound-error h1 {
            font-size: 120px;
            font-weight: 200px;
        }
        .notfound .notfound-error h2 {
            font-size: 30px;
        }
        .notfound a {
            padding: 7px 15px;
            font-size: 24px;
        }
        .h2 {
            font-size: 148px;
        }
        }
    </style>
</head>

<body>
    <div id="notfound">
        <div class="notfound">
            <h1><a>К сожалению, страница не может быть загружена!</a></h1>
            <div class="notfound-error">
                <p>Обратитесь за помощью к администратору сайта или в службу поддержки.</p>
            </div>
        </div>
    </div>
</body>

</html>