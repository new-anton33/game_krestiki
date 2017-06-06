<? header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" version="XHTML+RDFa 1.0" dir="ltr">
<head profile="http://www.w3.org/1999/xhtml/vocab">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

<!-- Получаем данные от бекенда. -->

<script>

    function LoadGame(){
        $.get( "/api/index.php?action=newGame", function( data ) {
            //Парсим Json
            data_game = $.parseJSON(data);
            console.log(data_game);

            CurPlayer(data_game.CurrentPlayer);

            //Грузим игровое поле
            pole = '';
            for(var y=0; y < data_game.height; y++) {
            pole += '<div class="poleRow">';
                for(var x=0; x < data_game.width; x++) {
                    pole += '<div id="'+ x+y+'" class="poleCell">';
                    pole += '<a href="#" onclick="Move('+x+','+y+')"></a>';
                    pole += '</div>';
                }
                pole += '</div>';
            }

            $('.GameBoard').html(pole);


        });
    }

    function CurPlayer(cp) {
        //Показываем кто ходит
        console.log(cp);
        if(cp == 1){
            $('.curplayer').html('Х');
            return '0';
        } else {
            $('.curplayer').html('0');
            return 'x';
        }
    }

    function WinPlayer(cp) {
        //Показываем кто ходит
        console.log(cp);
        if(cp == 1){
            return 'X';
        } else {
            return '0';
        }
    }


    function Move(x,y){
        $.get( "/api/index.php?action=move&x="+x+"&y="+y, function( data ) {
            data_game = $.parseJSON(data);
            //console.log(data_game.winnerCells);
            var cplayer = CurPlayer(data_game.CurrentPlayer);
            $('#'+x+y).html(cplayer);

            //Проверяем победителя
            if(data_game.Winner != null){
                //Закрашиваем ячейки победителя
                $.each(data_game.winnerCells, function(key, value){
                    $.each(data_game.winnerCells[key], function(key2, value2) {
                        console.log(key + ": " + key2);
                        $('#'+key+key2).attr('class','poleCell winner');
                    });
                });
                alert('Победил '+WinPlayer(data_game.Winner)+'!');
            }

        });
    }

    LoadGame();

</script>

<!-- CSS-стили, задающие внешний вид элементов HTML. -->
<style type="text/css">
    body{ font-family: Arial;}
    .GameBoard {overflow:hidden;}
    .poleRow {clear:both;}
    .poleCell {float:left; border: 1px solid #ccc; width: 20px; height:20px;
        position:relative; text-align:center;}
    .poleCell a {position:absolute; left:0;top:0;right:0;bottom:0}
    .poleCell a:hover { background: #aaa; }
    .poleCell.winner { background:#f00;}

    .icon { display:inline-block; }
    .green_b {
        background-color: #5ebd5f;
        border-radius: 3px;
        color: #ffffff !important;
        padding: 5px;
    }

</style>

    <!-- Отображаем приглашение сделать ход. -->
    Ходит игрок
    <div class="icon curplayer"></div>...


    <!-- Отображаем сообщение о победителе -->
    <div class="icon winplayer" style="display: none;"></div>


<!-- Рисуем игровое поле, отображая сделанные ходы
и подсвечивая победившую комбинацию. -->
<div class="GameBoard"></div>


<br/><a class="green_b" href="#" onclick="LoadGame()">Начать новую игру</a>
</body>
</html>
