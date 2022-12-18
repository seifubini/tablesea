<!Doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@200&display=swap" rel="stylesheet">
    <title>TableSea</title>

</head>

<body alink = "#000" link = "#000" vlink = "#000" style="font-family: 'Lato', sans-serif;">


<table bgcolor = "#0065A3" width = "100%" height="200px">
    <tr>
        <td>
            <center>
                <h1 style="color: #fff;">Reservation Update</h1>
            </center>
        </td>
    </tr>
</table>

<p><b><font size="5px"> TableSea. </font> </b></p>
<hr>
<h1><font color="#0065A3"> {!! $Restaurant_name !!} </font> </h1>

<table border = "0" cellpadding = "5" cellspacing = "3">

    <tr>
        <td width="30%">Name:</td>
        <td >{!! $guest_name !!}</td>
    </tr>

    <tr>
        <td>Seats:</td>
        <td width="55%">{!! $no_of_people !!}</td>
    </tr>

    <tr>
        <td>Date:</td>
        <td>{!! $date !!}</td>
    </tr>

    <tr>
        <td>Time:</td>
        <td>{!! $time !!}</td>
    </tr>

    <tr>
        <td>Status:</td>
        <td>{!! $status !!}</td>
    </tr>

    <tr>
        <td>Code:</td>
        <td>{!! $reservation_code !!}</td>
    </tr>


</table>
<br><br>

<br><br>
<p>Don't have an account?</p>
<p><a href="https://www.tablesea.com/register"> Click here </a> to create an account with tablesea and making your resturant boooking easy!</p>
<br><br>

<table border = "0">

    <tr>
        <td width="98%">2021 TableSea, All rights reserved </td>
    </tr>
    <tr>
        <td width="35%"><a href="https://www.tablesea.com">Tablesea.com </a></td>
    </tr>

</table>
<br>

</body>

</html>
