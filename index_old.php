<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('images/bgimg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        .center-vertically {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .logo {
            width: 100px; /* Adjust the width as needed */
        }

        .button-container {
            margin-top: 20px;

        }

        .button {
            font-size: 40px;
            border-radius: 50px; /* Adjust the radius as needed */
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="center-vertically">
        <img src="images/logodbkltrans.png" alt="Logo" class="logo">
        <h1 class="text-center">TaskForce DBKL 2023</h1>
        <div class="button-container text-center">
            <a href="http://localhost/flutterapps/api/taxtrek/main.php?page=dashboard.php" class="btn button">Continue</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

