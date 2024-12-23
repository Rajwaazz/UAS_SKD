<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmation</h1>
    <p>Dear Customer,</p>
    <p>Thank you for booking your tickets with us!</p>
    <p>Here are the details of your booking:</p>
    <ul>
        <li>Film: {{ $film }}</li>
        <li>Show Time: {{ $show_time }}</li>
        <li>Price: {{ $price }}</li>
    </ul>
    <p>We look forward to seeing you!</p>
    <p>Best Regards,<br>Your Movie Booking Team</p>
</body>
</html>
