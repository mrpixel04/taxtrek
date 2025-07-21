<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaxTrek</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Poppins Font -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(90deg, #ff00cc, #8000ff); /* Purple to Red */
      animation: gradientMove 10s linear infinite;
    }
    
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }

    .login-container {
      width: 100%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
     background-color: #edf3f3;
    }

    @media (min-width: 576px) {
      .card {
        width: 400px;
      }
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .logo {
      width: 60px; /* Adjust as needed */
      height: auto;
      margin-right: 10px;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
    }

    /* Button Styles */
    .btn-primary {
      background: linear-gradient(90deg, #8000ff, #ff00cc); /* Purple to Pink */
      border: none;
      transition: all 0.3s ease;
      border-radius: 10px;
      padding: 10px 20px;
      cursor: pointer;
    }

    .btn-primary:hover {
      transform: scale(1.05); /* Zoom in effect */
    }

    
  </style>
</head>
<body>

  <div class="login-container">
    <div class="card">
      <div class="card-body p-5">
        <div class="logo-container">
          <img src="images/logo.png" alt="TaxTrek Logo" class="logo">
          <h2 class="title">TaxTrek - Auto Deploy Test ✅</h2>
        </div>
        <form>
          <div class="mb-3">
            <label for="email" class="form-label">Emel</label>
            <input type="email" class="form-control" id="email" placeholder="Masukan Emel">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Katanama</label>
            <input type="password" class="form-control" id="password" placeholder="Masukan Katanama">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Log Masuk</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>


