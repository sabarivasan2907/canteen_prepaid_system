<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>
</head>
<body>

    <h2>Scan QR Code</h2>
    <video id="preview" width="100%"></video>

    <form id="qrForm" method="POST" action="process_transaction.php">
        <input type="hidden" name="scanned_data" id="scanned_data">
        <button type="submit">Process Payment</button>
    </form>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]); // Use first available camera
            } else {
                alert("No cameras found.");
            }
        }).catch(function(e) {
            console.error(e);
            alert("Error accessing camera: " + e);
        });

        scanner.addListener("scan", function(content) {
            console.log("Scanned Data:", content);
            document.getElementById("scanned_data").value = content;
            document.getElementById("qrForm").submit();
        });
    </script>

</body>
</html>