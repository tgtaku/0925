<?php
session_start();
$project_id = $_SESSION['count'];
//mysqlとの接続
$link = mysqli_connect('localhost', 'root', '');
if (!$link) {
    die('Failed connecting'.mysqli_error());
}
//print('<p>Successed connecting</p>');

//DBの選択
$db_selected = mysqli_select_db($link , 'test_db');
if (!$db_selected){
    die('Failed Selecting table'.mysql_error());
}
//文字列をutf8に設定
mysqli_set_charset($link , 'utf8');

//pdfテーブルの取得
$result_file  = mysqli_query($link ,"SELECT pdf_name FROM pdf_information_1 where project_id = '$project_id';");
if (!$result_file) {
    die('Failed query'.mysql_error());
}
//データ格納用配列の取得
$row_array_file = array();
$i = 0;
$selectedPD = "";
while ($row = mysqli_fetch_assoc ($result_file)) {
    $row_array_file[$i] = $row['pdf_name'];
    //ディレクトリ作成
    $moji = substr($row_array_file[$i], 0, strlen($row_array_file[$i]) - 4);
    $d = '.\up\d';
    $di = substr($d, 0, strlen($d) - 1);
    $dir = $di.$moji;
    //mkdir($dir, 0777);
    //参照するPDFのパス
    $pa = 'D:\Bitnami\htdocs\web\up\d';
    $pat = substr($pa, 0, strlen($pa) - 1);
    $path = $pat.$row_array_file[$i];
    
    //pngのパス
    $png = substr($path, 0, strlen($path) - 4)."\d";
    $pg = substr($png, 0, strlen($png) - 1);
    $out = $pg.$moji.".png";
    //print_r($out);
    $cmd = 'D:\ImageMagick-7.0.10-Q16\convert.exe'.' '.$path.' '.$out;
    //echo $cmd;
    //exec($cmd);
    //print_r($row_array_file[$i]);
    if($i == 0){
        //$m = $moji."\d";
        //$mm = substr($m, 0, strlen($m) - 1);
        //$selectedPD = $mm.$moji."-0.png";
        $selectedPD = $moji;
        print_r($selectedPD);
    }
    $i++;
}
$selectedPDF = json_encode($selectedPD);
$array_length = count($row_array_file);
$json_array = json_encode($row_array_file);
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>報告箇所登録画面</title>
    </head>
    <body>
    <h2>報告箇所を登録してください。</h2>
    <ul id="pdfName">
    </ul>
    <h2 id ="selectedPDF">図面名</h2>
    <h4 id ="pagenum"></h4>
    <script>
        var pageNum = 0;
        var page = pageNum+1 + "ページ";
        var p = document.getElementById('pagenum');
        p.innerHTML = page;
    </script>
    <div id ="div">
    <img alt = "図面を選択してください" title = "test" id ="im" >
    <script>
        var img = document.getElementById('im');
        var first_img = <?php echo $selectedPDF; ?>;
        var first_img_g = "./up/" + first_img +"/" + first_img +"-0.png";
        //var first_img = "./up/sample/sample-0.png";
        img.src = first_img_g;
    </script>
    </div>
    <div>
    <img src="left.png" title = "left" id ="left" >
    <img src="right.png" title = "right" id ="right" >
</div>
    <script>
        var right = document.getElementById('right');
        var left = document.getElementById('left');

        right.addEventListener('click', function(nextPage){
            console.log("next");
            pageNum += 1;
            var img = document.getElementById('im');
            img.src = "./up/lowcarbon05/hoge-" + pageNum + ".png";
            p.innerHTML = pageNum+1 + "ページ";;
        }, false);

        left.addEventListener('click', function(backPage){
            console.log("back");
            pageNum -= 1;
            var img = document.getElementById('im');
            //img.src = "./up/lowcarbon05/hoge-" + pageNum + ".png";
            img.src =;
            p.innerHTML = pageNum+1 + "ページ";;
        }, false);
    </script>
    <script>
        var img = document.getElementById('im');
        var cell1 = [];
        var cell2 = [];
        var cell3 = [];
        img.addEventListener('click', function(event){
            //要素の数を探す
            var div = document.getElementById('div');
            var table = document.getElementById('place_info');
            var tabLength = table.rows.length;
            var num = tabLength-1;
            console.log(num);
            console.log(tabLength);
            
            //画像表示位置の取得
            var elem = document.getElementById('im');
            var rect = elem.getBoundingClientRect();
            var elemtop = rect.top + window.pageYOffset;
            var elemleft = rect.left + window.pageXOffset;
            var eTop = parseInt(elemtop);
            var eLeft = parseInt(elemleft);
            //var elembotom = rect.bottom + window.pageYOffset;
            //var elemright = rect.right + window.pageXOffset;

            //画像サイズ
            var w = elem.width;
            var w1 = parseInt(w);
            var h = elem.height;
            var h1 = parseInt(h);
            
            //画像上のクリック地点
            var x = event.pageX;
            var x1 = parseInt(x);
            var x2 = x1 - eLeft;
            var y = event.pageY;
            var y1 = parseInt(y);
            var y2 = y1 - eTop;

            //座標上の比率を計算
            var pointX = x1/w1;
            var pointY = h1/y1;

            //var y = touch.pageY;
            alert(pointX);
            console.log(x1);
            console.log(w1);
            console.log(pointX);
            var pValue = window.prompt("施工箇所名を入力してください", "");
            //動的にdivを作成する
            var cBall = document.createElement('d'); 
		    cBall.style.position = "absolute";
            var RandLeft = x1 + "px";
		    var RandTop = y1 + "px";
            cBall.style.left = RandLeft;
		    cBall.style.top = RandTop;
            var mark = document.createElement('img');
            mark.id = tabLength;
            mark.value = pValue;
            mark.src = "http://10.20.170.52/web/local_pic.png";
            mark.addEventListener('click', function(event2){
                var placeValue = window.prompt("施工箇所名変更", this.value);
            });
            //Divにイメージを組み込む
		    cBall.appendChild(mark);
		    //ゲーム画面にボールレイヤ（Div)を組み込む
		    div.appendChild(cBall);
            var row = table.insertRow(-1);
            cell1.push(row.insertCell(-1));
            cell2.push(row.insertCell(-1));
            cell3.push(row.insertCell(-1));
            cell1[num].innerHTML = tabLength;
            cell2[num].innerHTML = pValue;
            cell3[num].innerHTML = pageNum;
        }, false);
    
        </script>
    <form id="place_form">
    <table id = "place_info">
                <tr>
                    <th style="WIDTH: 15px" id="no">No</th>
                    <th style="WIDTH: 300px" id="place">施工箇所</th>
                    <th style="WIDTH: 60px" id="page">ページ</th>
                </tr>
            </table>
            <input type = "submit" id = "place_button" name="gotPlace" value = "登録">
</form>
    <script type="text/javascript">
        //var names =[];
        var names = <?php echo $json_array; ?>;
        var length = <?php echo $array_length; ?>;
        var li = [];
        //var parent = document.getElementById('pdfName');
        for (var i = 0; i < length; i++){
            li[i] = document.createElement('button');
            li[i].value = names[i];
            li[i].textContent = names[i];
            li[i].onclick = function(){getPic(this)};
            //parent.appendChild(li[i]);
            document.getElementById('pdfName').appendChild(li[i]);
            document.getElementById('selectedPDF').innerHTML = names[0];
        }
        //document.getElementById("pdfName").children.onclick = function(){};
    </script>
    
    <script>
        function getPic(obj){
            //var ul = document.getElementById('pdfName');
            //var ul = document.button[0];
            //var lis = ul.childNodes;
            //var li = lis[0].name;
            //var li = ul.value;
            //alert(li);
            //var li = ul.getElementsByTagName("button");
            //li[0].style.backgroudColor = "lightblue";
            //bj.style.backgroundColor = "lightblue";
            var target_name = obj.value;
            //alert(target_id);
            document.getElementById('selectedPDF').innerHTML = target_name;

            //PDFから画像を取得する処理
        }

        //var touchStartX;

        //$(".view").on("touchstart", function (e) {
        //touchStartX = e.originalEvent.changedTouches[0].pageX;
        //});

        //function imgClick(img){
            //var touchObject = img.changedTouches[0];
            //var touchX = touchObject.pageX;
            //var touchX = img.pageX;
            //var touchY = img.pageY;
            //var o = img.offsetX;
            
            //var w = img.width;
            //var h = img.height;
            
            //var touchY = touchObject.pageY;

            //要素の位置の取得
            //var clientRect = img.getBoundingClientRect();
            //var positionX = clientRect.left + window.pageXOffset;
            //var positionY = clientRect.top + window.pageYOffset;

            //要素内におけるタッチ位置の計算
            //var x = touchX - positionX;
            //var y = touchY - positionY;
            //var x = img.originalEvent.changedTouches[0].screenX;
  //var y = img['pageY'] || img.clientY;
            

            //alert(touchStartX);


            //var n = img.alt;
            //alert(n);
        //}
    </script>
    </body>

</html>