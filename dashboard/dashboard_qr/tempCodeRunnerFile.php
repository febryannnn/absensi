<?php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code & Entry Code</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #1c2d04, #aee695);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 400px;
    text-align: center;
}

.card h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.card h3 {
    font-size: 3rem;
    margin-top:50px;
}


.qr-code {
    margin-bottom: 20px;
}

.qr-img {
    width: 150px;
    height: 150px;
    object-fit: cover;
}

form label {
    font-size: 1rem;
    margin-bottom: 10px;
    display: block;
}

form input {
    width: 80%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    width: 100%;
    padding: 10px;
    margin:50px 0;
    background-color:rgb(232, 241, 232);
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight:600;
    transition: background-color 0.3s;
}

form button:hover {
    background-color:rgb(13, 62, 16);
    color:white;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>QR Code</h2>
            <div class="qr-code">
                <!-- Ganti URL dengan gambar QR Code yang sesuai -->
                <img src="qrcode.png?v=<?php echo time(); ?>" alt="QR Code" class="qr-img">
            </div>
        </div>
        <div class="card">
            <h2>Display Code</h2>
            <h3><?php echo $kode?></h3>
        </div>
    </div>

    <form action="../dashboard.php">
            <button>Back to Dashboard</button>
    </form>
</body>
</html>