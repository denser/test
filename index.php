<?php
define("____GUESS_GAME____", true);
require_once 'auth.php';
$_SESSION['guess_game'] = [
    'try' => 0,
    'history' => [],
    'oracles' => [
        'oracle1' => [
            'name' => 'Мудрый',
            'history' => [],
            'pki' => 0,
        ],
        'oracle2' => [
            'name' => 'Древний',
            'history' => [],
            'pki' => 0,
        ],
        'oracle3' => [
            'name' => 'Всевидящий',
            'history' => [],
            'pki' => 0,
        ],
    ],
];
$userHistory = $_SESSION['guess_game']['history'];
$oracles = $_SESSION['guess_game']['oracles'];
$resultTd = '<td>Игрок</td>';
foreach ($oracles as $k => $oracle) $resultTd .= "<td>[<span id='$k-pki'>$oracle[pki]</span>] $oracle[name]</td>";
?>
<html lang="ru">
<head>
    <title>Загадай число. Игра.</title>
    <script src="https://code.jquery.com/jquery-2.0.2.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/cupertino/jquery-ui.css"/>
    <style>
        td {
            border: inset;
        }
        table {
            width: 100%;
        }
        #text {
            line-height: 30px;
        }
    </style>
</head>
<script type="text/javascript">
    $(document).ready(function() {
        $("#dialog").dialog({
            width: 600,
            height: 600,
            modal: true,
            open: function () {
                $("#text").html("Загадайте число от 1 до 10, пусть оракулы попытаются его отгадать!");
                $(".ui-dialog-titlebar-close").hide();
                $("#check-this-out").hide();
                $("#my-number").hide();
                $("#catch-me").show();
            },
            buttons: [
                {
                    text: "Я загадал!",
                    id: "catch-me",
                    click: function () {
                        $("#text").html("Введите число, которое вы загадали:");
                        $("#check-this-out").show();
                        $("#my-number").show();
                        $("#catch-me").hide();
                        $.ajax({
                            url: "./oracle.php",
                            dataType: "json",
                            success: function (result) {
                                let oraGuess = '<td>?</td>';
                                $.map( result, function( item ) {
                                    oraGuess = oraGuess + '<td>' + item + '</td>';
                                });
                                $('#results tr:last').after('<tr>'+ oraGuess +'</tr>');
                                
                                //$("#results").append(oraGuess);
                                console.log(result);
                            }
                        });
                    },
                },
                {
                    text: "Отправить",
                    id: "check-this-out",
                    click: function () {
                        let myNum = $("#my-number");
                        $("#text").html("Еще разок!");
                        $("#check-this-out").hide();
                        myNum.hide();
                        $("#catch-me").show();
                        $("td:contains('?')").html(myNum.val());
                        $.ajax({
                            url: "./oracle.php",
                            type: "POST",
                            data: {
                                num: myNum.val()
                            },
                            dataType: "json",
                            success: function (result) {
                                $.map( result, function( item, ora ) {
                                    let oraPki = $("#"+ora+"-pki");
                                    oraPki.html(item);
                                });
                                console.log(result);
                            }
                        });
                    }
                },
            ],
            show: {
                effect: "blind",
                duration: 600
            },
        });
    });
</script>
<?php
$out = <<<EOT
<div title='Оракулы приветствуют тебя!' id='dialog' style='text-align:center;'>
    <span id="text"></span>
    <input id="my-number" type="number" min="1" max="10" value="1">
    <table id="results">
        <tr>$resultTd</tr>
    </table>
    
</div>
EOT;
echo $out;
echo "Обнови страничку, чтобы начать заново :)";
?>
</html>