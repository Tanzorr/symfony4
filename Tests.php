<?php

class Test1
{
    public static function last_world($sentance)
    {
        $arrSentance = explode(" ", $sentance);
        $lastWorld = $arrSentance[count($arrSentance) - 1];
        echo strlen($lastWorld);
    }

    public static function extract_string($str)
    {

        $param1 = "[";
        $param2 = "]";
        $pos1 = strripos($str, $param1);
        $pos2 = strripos($str, $param2);
        if ($pos1 !== false && $pos2 !== false) {
            $str = str_replace([$param1, $param2], '"', $str);
            echo $str;
        } else {
            echo '""';
        }
    }
}


class Test2
{
    public static function is_power($number, $base)
    {
        if ($base * $base == $number) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public static function format_number($str)
    {
        $res = preg_replace("/[^0-9.,]/", "", $str);
        echo $res;
    }

    public static function sum_digits($int)
    {
        $str = intval($int);
        $arr = str_split($str);
        echo array_sum($arr);
    }
}

$str1 = 'yabadabadoo';
$str2 = 'yaba';
if (strpos($str1, $str2) !== false) {
    echo "\"" . $str1 . "\" contains \"" . $str2 . "\"";
} else {
    echo "\"" . $str1 . "\" does not contain \"" . $str2 . "\"";
}


//4. Assume we have those two tables, Table 'a' Table 'b' Id Name Id Grade 1 Eli 1 98 2 Moshe 3 55 3 Yossi 4 100 What will be the output of this query? SELECT a.id, a.name, b.grade FROM a LEFT JOIN b on b.id = a.id

//• 3 rows, 1 NULL value

//5
//NOW () command is used to show current year,month,date with hours,minutes and seconds.
//SELECT NOW(), SLEEP(2), NOW();
//CURRENT_DATE() shows current year,month and date only.
//SELECT * FROM workers WHERE date > CURRENT_DATE()
//6
//
//The UNION operator is used to combine the result-set of two or more SELECT statements.
//
//• Each SELECT statement within UNION must have the same number of columns
//• The columns must also have similar data types
//• The columns in each SELECT statement must also be in the same order
//7
//SELECT costymers.name, costymers.city, sails.name FROM costymers LEFT JOIN sails ON costymers.sales_id = sails.id WHERE commission BETWEEN 0.12 AND 0.14
//SELECT * FROM sails LEFT JOIN costymers ON costymers.sales_id = sails.id WHERE costymers.sales_id IS NULL
?>
<script>
    var arr = [1, 2, 3, 5, 1, 5, 9, 1, 2, 8];

    function unique(arr) {
        let unique = [...new Set(arr)];
        console.log('your log', unique)
    };
    unique(arr);

    function duplicate(arr, times) {
        let res = [];
        for (let i = 0; i < times; i++) {
            for (let j = 0; j < arr.length; j++) {
                res.push(arr[j])
            }
        }
        console.log('res', res)
    };
    duplicate([1, 2, 3, 4, 5], 3); // [1,2,3,4,5,1,2,3,4,5,1,2,3,4,5]


    //10
    $(document).ready(function () {
// define the click handler for all buttons
        $("button").on("click", function () {
            console.log("Button Clicked:", this);
            setTimeout(() => {/* ... some time later ... */
// dynamically add another button to the page
                $("html").append("<button>Click Alert!</button>");
            }, 3000)
        });

    });
</script>

//11
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<a class="btn btn-primary">+1<i class="btn btn-light ml-4">1</i></a>
<script>
    $(document).ready(function () {
        let incremValue = 1;
        $("a").click(function () {
            console.log('incr', incremValue);
            incremValue++;
            $("i").text(incremValue);
        });
    });
</script>
</body>
