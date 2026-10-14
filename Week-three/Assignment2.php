```php
<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PHP & MySQL Assignment</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
            line-height: 1.6;
        }

        .container {
            width: 92%;
            max-width: 1100px;
            margin: 40px auto;
        }

        /* Header */

        .header {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
            padding: 35px;
            border-radius: 18px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .header p {
            color: #dbeafe;
            font-size: 15px;
        }

        /* Question Card */

        .question {
            background: white;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }

        .question-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .number {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #2563eb;
            color: white;
            border-radius: 10px;
            font-weight: bold;
            font-size: 18px;
        }

        .question-title h2 {
            font-size: 22px;
            color: #0f172a;
        }

        .subtitle {
            color: #64748b;
            font-size: 14px;
        }

        /* Array Items */

        .array-box {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0 25px;
        }

        .array-item {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 9px 15px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* Results */

        .results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .result-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }

        .result-card .label {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .result-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Tables */

        .table-wrapper {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
            overflow: hidden;
            border-radius: 10px;
        }

        table th {
            background: #0f172a;
            color: white;
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        table td {
            padding: 13px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        table tr:nth-child(even) {
            background: #f8fafc;
        }

        table tr:hover {
            background: #eff6ff;
        }

        /* Matrix */

        .matrix {
            width: auto;
            min-width: 300px;
            margin: 20px 0;
        }

        .matrix td {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 18px 30px;
        }

        /* Section headings */

        .section-heading {
            font-size: 17px;
            margin-top: 20px;
            margin-bottom: 12px;
            color: #1e3a8a;
        }

        /* Footer */

        .footer {
            text-align: center;
            color: #64748b;
            padding: 25px;
            font-size: 13px;
        }

        /* Responsive */

        @media (max-width: 600px) {

            .container {
                width: 95%;
                margin: 20px auto;
            }

            .header {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 25px;
            }

            .question {
                padding: 20px;
            }

            .question-title h2 {
                font-size: 18px;
            }

            .results {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="header">
        <h1>PHP & MySQL Assignment</h1>
        <p>Name: Abdikani Mohamud Nor</p>
        <p>ID: C1230893</p>
        <p>Class: CA232</p>
    </div>


    <!-- =====================================================
         QUESTION 1
         ===================================================== -->

    <div class="question">

        <div class="question-title">
            <div class="number">01</div>

            <div>
                <h2>One Dimensional Array</h2>
                <div class="subtitle">
                    Array elements, totals, minimum and maximum
                </div>
            </div>
        </div>


        <?php

        $array1 = array(
            5, -7, 12, 10, -7, 11,
            -6, 12, 1, -7, 2, 9
        );

        $total = 0;
        $evenTotal = 0;
        $oddTotal = 0;

        $minimum = $array1[0];
        $maximum = $array1[0];

        $minimumPositions = array();
        $maximumPositions = array();

        ?>


        <h3 class="section-heading">
            All Elements
        </h3>


        <div class="array-box">

            <?php

            foreach ($array1 as $index => $value) {

                echo "<div class='array-item'>";
                echo $value;
                echo "</div>";

                $total = $total + $value;

                if ($value % 2 == 0) {
                    $evenTotal = $evenTotal + $value;
                } else {
                    $oddTotal = $oddTotal + $value;
                }

                if ($value < $minimum) {
                    $minimum = $value;
                }

                if ($value > $maximum) {
                    $maximum = $value;
                }
            }

            ?>

        </div>


        <?php

        foreach ($array1 as $index => $value) {

            if ($value == $minimum) {
                $minimumPositions[] = $index;
            }

            if ($value == $maximum) {
                $maximumPositions[] = $index;
            }
        }

        ?>


        <h3 class="section-heading">
            Results
        </h3>


        <div class="results">

            <div class="result-card">
                <span class="label">Total Elements</span>
                <span class="value">
                    <?= $total ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Even Total</span>
                <span class="value">
                    <?= $evenTotal ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Odd Total</span>
                <span class="value">
                    <?= $oddTotal ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Minimum</span>
                <span class="value">
                    <?= $minimum ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Minimum Positions</span>
                <span class="value">
                    <?= implode(", ", $minimumPositions) ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Maximum</span>
                <span class="value">
                    <?= $maximum ?>
                </span>
            </div>

            <div class="result-card">
                <span class="label">Maximum Positions</span>
                <span class="value">
                    <?= implode(", ", $maximumPositions) ?>
                </span>
            </div>

        </div>

    </div>


    <!-- =====================================================
         QUESTION 2
         ===================================================== -->

    <div class="question">

        <div class="question-title">

            <div class="number">02</div>

            <div>
                <h2>Two Dimensional Associative Array</h2>

                <div class="subtitle">
                    Light, Normal and Dark colors
                </div>
            </div>

        </div>


        <?php

        $colors = array(

            "Light" => array(
                "Red" => "Light Red",
                "Green" => "Light Green",
                "Blue" => "Light Blue"
            ),

            "Normal" => array(
                "Red" => "Normal Red",
                "Green" => "Normal Green",
                "Blue" => "Normal Blue"
            ),

            "Dark" => array(
                "Red" => "Dark Red",
                "Green" => "Dark Green",
                "Blue" => "Dark Blue"
            )

        );

        ?>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>Type</th>
                    <th>Red</th>
                    <th>Green</th>
                    <th>Blue</th>

                </tr>


                <?php

                foreach ($colors as $rowName => $row) {

                    echo "<tr>";

                    echo "<td><strong>$rowName</strong></td>";

                    echo "<td>" . $row["Red"] . "</td>";

                    echo "<td>" . $row["Green"] . "</td>";

                    echo "<td>" . $row["Blue"] . "</td>";

                    echo "</tr>";
                }

                ?>

            </table>

        </div>

    </div>


    <!-- =====================================================
         QUESTION 3
         ===================================================== -->

    <div class="question">

        <div class="question-title">

            <div class="number">03</div>

            <div>

                <h2>Three × Three Square Array</h2>

                <div class="subtitle">
                    Rows, columns, diagonals, minimum and maximum
                </div>

            </div>

        </div>


        <?php

        $array3 = array(

            array(2, -6, 8),

            array(-6, 1, 6),

            array(7, 8, -6)

        );


        $oddTotal = 0;
        $evenTotal = 0;
        $allTotal = 0;

        $minimum = $array3[0][0];
        $maximum = $array3[0][0];

        $minimumPositions = array();
        $maximumPositions = array();

        ?>


        <h3 class="section-heading">
            Matrix
        </h3>


        <div class="table-wrapper">

            <table class="matrix">

                <?php

                for ($i = 0; $i < 3; $i++) {

                    echo "<tr>";

                    for ($j = 0; $j < 3; $j++) {

                        $value = $array3[$i][$j];

                        echo "<td>$value</td>";

                        $allTotal += $value;

                        if ($value % 2 == 0) {
                            $evenTotal += $value;
                        } else {
                            $oddTotal += $value;
                        }

                        if ($value < $minimum) {
                            $minimum = $value;
                        }

                        if ($value > $maximum) {
                            $maximum = $value;
                        }

                    }

                    echo "</tr>";
                }

                ?>

            </table>

        </div>


        <?php

        /* Minimum and Maximum positions */

        for ($i = 0; $i < 3; $i++) {

            for ($j = 0; $j < 3; $j++) {

                if ($array3[$i][$j] == $minimum) {

                    $minimumPositions[] =
                        "Row " . ($i + 1) .
                        ", Column " . ($j + 1);
                }


                if ($array3[$i][$j] == $maximum) {

                    $maximumPositions[] =
                        "Row " . ($i + 1) .
                        ", Column " . ($j + 1);
                }

            }

        }


        /* Row totals */

        $rowTotals = array();

        for ($i = 0; $i < 3; $i++) {

            $rowTotal = 0;

            for ($j = 0; $j < 3; $j++) {

                $rowTotal += $array3[$i][$j];

            }

            $rowTotals[] = $rowTotal;
        }


        /* Column totals */

        $columnTotals = array();

        for ($j = 0; $j < 3; $j++) {

            $columnTotal = 0;

            for ($i = 0; $i < 3; $i++) {

                $columnTotal += $array3[$i][$j];

            }

            $columnTotals[] = $columnTotal;
        }


        /* Diagonals */

        $firstDiagonal = 0;

        for ($i = 0; $i < 3; $i++) {

            $firstDiagonal += $array3[$i][$i];

        }


        $secondDiagonal = 0;

        for ($i = 0; $i < 3; $i++) {

            $secondDiagonal += $array3[$i][2 - $i];

        }

        ?>


        <h3 class="section-heading">
            Row Totals
        </h3>


        <div class="results">

            <?php

            foreach ($rowTotals as $index => $value) {

                echo "
                <div class='result-card'>

                    <span class='label'>
                        Row " . ($index + 1) . "
                    </span>

                    <span class='value'>
                        $value
                    </span>

                </div>
                ";
            }

            ?>

        </div>


        <h3 class="section-heading">
            Column Totals
        </h3>


        <div class="results">

            <?php

            foreach ($columnTotals as $index => $value) {

                echo "
                <div class='result-card'>

                    <span class='label'>
                        Column " . ($index + 1) . "
                    </span>

                    <span class='value'>
                        $value
                    </span>

                </div>
                ";
            }

            ?>

        </div>


        <h3 class="section-heading">
            Other Results
        </h3>


        <div class="results">

            <div class="result-card">

                <span class="label">
                    Odd Total
                </span>

                <span class="value">
                    <?= $oddTotal ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Even Total
                </span>

                <span class="value">
                    <?= $evenTotal ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    All Elements Total
                </span>

                <span class="value">
                    <?= $allTotal ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    First Diagonal
                </span>

                <span class="value">
                    <?= $firstDiagonal ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Second Diagonal
                </span>

                <span class="value">
                    <?= $secondDiagonal ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Minimum
                </span>

                <span class="value">
                    <?= $minimum ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Minimum Positions
                </span>

                <span class="value">
                    <?= implode(" | ", $minimumPositions) ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Maximum
                </span>

                <span class="value">
                    <?= $maximum ?>
                </span>

            </div>


            <div class="result-card">

                <span class="label">
                    Maximum Positions
                </span>

                <span class="value">
                    <?= implode(" | ", $maximumPositions) ?>
                </span>

            </div>

        </div>

    </div>


    <!-- =====================================================
         QUESTION 4
         ===================================================== -->

    <div class="question">

        <div class="question-title">

            <div class="number">04</div>

            <div>

                <h2>Student Information</h2>

                <div class="subtitle">
                    Student name, phone and address
                </div>

            </div>

        </div>


        <?php

        $students = array(

            "CA221" => array(
                "Name" => "Mohamed Ahmed Ali",
                "Phone" => "0648440403",
                "Address" => "Laba Dhagax, Wardhiigley"
            ),

            "CA223" => array(
                "Name" => "Ahmed Abdi Jama",
                "Phone" => "0647223201",
                "Address" => "Taleex, Hodan"
            ),

            "CA221-2" => array(
                "Name" => "Amina Nur Adan",
                "Phone" => "0646990276",
                "Address" => "Macmacaanka, Dharkeynley"
            )

        );

        ?>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>Class</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>

                </tr>


                <?php

                foreach ($students as $class => $student) {

                    echo "<tr>";

                    echo "<td><strong>$class</strong></td>";

                    echo "<td>" . $student["Name"] . "</td>";

                    echo "<td>" . $student["Phone"] . "</td>";

                    echo "<td>" . $student["Address"] . "</td>";

                    echo "</tr>";
                }

                ?>

            </table>

        </div>

    </div>


    <!-- =====================================================
         QUESTION 5
         ===================================================== -->

    <div class="question">

        <div class="question-title">

            <div class="number">05</div>

            <div>

                <h2>Student Transcript</h2>

                <div class="subtitle">
                    Semester 1 and Semester 2 results
                </div>

            </div>

        </div>


        <?php

        $transcript = array(

            "Semester 1" => array(

                "subject1" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 40,
                    "Total" => 85,
                    "Status" => "Pass"
                ),

                "subject2" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 40,
                    "Total" => 85,
                    "Status" => "Pass"
                ),

                "subject3" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 40,
                    "Total" => 85,
                    "Status" => "Pass"
                )

            ),


            "Semester 2" => array(

                "subject1" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 0,
                    "Total" => 45,
                    "Status" => "Fail"
                ),

                "subject2" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 40,
                    "Total" => 85,
                    "Status" => "Pass"
                ),

                "subject3" => array(
                    "CW1" => 9,
                    "MidTerm" => 26,
                    "CW2" => 10,
                    "Final" => 40,
                    "Total" => 85,
                    "Status" => "Pass"
                )

            )

        );

        ?>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>Semester</th>
                    <th>Course</th>
                    <th>CW1</th>
                    <th>MidTerm</th>
                    <th>CW2</th>
                    <th>Final</th>
                    <th>Total</th>
                    <th>Status</th>

                </tr>


                <?php

                foreach ($transcript as $semester => $courses) {

                    foreach ($courses as $course => $data) {

                        echo "<tr>";

                        echo "<td><strong>$semester</strong></td>";

                        echo "<td>$course</td>";

                        echo "<td>" . $data["CW1"] . "</td>";

                        echo "<td>" . $data["MidTerm"] . "</td>";

                        echo "<td>" . $data["CW2"] . "</td>";

                        echo "<td>" . $data["Final"] . "</td>";

                        echo "<td><strong>" . $data["Total"] . "</strong></td>";

                        echo "<td>";

                        if ($data["Status"] == "Pass") {

                            echo "<span style='color:#16a34a;font-weight:bold;'>
                                    Pass
                                  </span>";

                        } else {

                            echo "<span style='color:#dc2626;font-weight:bold;'>
                                    Fail
                                  </span>";
                        }

                        echo "</td>";

                        echo "</tr>";
                    }
                }

                ?>

            </table>

        </div>

    </div>


    <!-- FOOTER -->

    <div class="footer">

        PHP & MySQL Assignment
        <br>
        Jamhuriya University of Science & Technology

    </div>

</div>

</body>
</html>

