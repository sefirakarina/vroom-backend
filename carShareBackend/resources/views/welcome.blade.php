<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    </head>
    <body>
        <a href="https://powerful-sea-28932.herokuapp.com/api/payment/create/?booking_id=<?= $_GET['booking_id']; ?>">Proceed Payment</a>

    </body>


</html>
