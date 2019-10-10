<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    </head>
    <body>
        <a href="https://powerful-sea-28932.herokuapp.com/api/payment/create/?user_id=<?= $_GET['user_id']; ?>&booking_id=<?= $_GET['booking_id']; ?>
                &car_id=<?= $_GET['car_id']; ?>&rentDays=<?= $_GET['rentDays']?>">Proceed Payment</a>

    </body>


</html>
