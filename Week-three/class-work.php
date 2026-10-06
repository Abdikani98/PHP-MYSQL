<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>class-work</title>
</head>
<body>

<?php

// Declare the 2D associative array
$students = [
    "CA221" => [
        "Name"    => "Mohamed Ahmed Ali", 
        "Phone"   => "0648440403",
        "Address" => "Laba Dhagax, Wardhiigley"
    ],
    "CA223" => [
        "Name"    => "Ahmed Abdi Jama",
        "Phone"   => "0647223201",
        "Address" => "Taleex, Hodan"
    ],
    "CA202" => [
        "Name"    => "Amina Nur Adan",
        "Phone"   => "0646990276",
        "Address" => "Macmacaanka, Dharkeynley"
    ]
];

// Display the HTML table
echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr bgcolor='#f2f2f2'>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
      </tr>";

foreach ($students as $id => $details) {
    echo "<tr>";
    echo "<td><strong>" . $id . "</strong></td>";
    echo "<td>" . $details["Name"] . "</td>";
    echo "<td>" . $details["Phone"] . "</td>";
    echo "<td>" . $details["Address"] . "</td>";
    echo "</tr>";
}

echo "</table>";

?>
    
</body>
</html>