<?php
session_start();
//ログインしてない時ログインページへ
if(!isset($_SESSION['employee'])) {
	header("Location: login.php");
}
//DB接続ファイル読み込み
include_once 'dbconnect.php';

$year=date('Y'); //年を取得
$month=date('n'); //月を取得
$last_day=date('j',mktime(0,0,0,$month,0,$year)); //取得した月の最終日を取得
$d=1;

/* if(isset($_POST['add'])){ //モーダルの追加を押したときに実行
    $start =$_POST['start'];
    $end =$_POST['end'];
    $rest =$_POST['rest'];
    $emp_id =$_POST['emp_id'];
    $emp_name =$_POST['emp_name'];
    $day=$_POST['day'];

    //テーブルに格納
    $sql = "INSERT INTO shift(emp_id,emp_name,shift_month,shift_day,start_time,end_time,rest_time) VALUES('$emp_id','$emp_name','$month','$day','$start','$end','$rest')";
    $result = $mysqli->query($sql);

    if(!$result){
        print('保存が失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
    }
} */
//時間区分(Aタイプ,Bタイプ,Cタイプ)を取得
$time_repertory = "SELECT * FROM time_repertory";
$repertory = $mysqli->query($time_repertory);
while($rou = $repertory->fetch_assoc()){
    $start_timeA = $rou['start_timeA'];
    $end_timeA = $rou['end_timeA'];
    $rest_timeA = $rou['rest_timeA'];
    $start_timeB = $rou['start_timeB'];
    $end_timeB = $rou['end_timeB'];
    $rest_timeB = $rou['rest_timeB'];
    $start_timeC = $rou['start_timeC'];
    $end_timeC = $rou['end_timeC'];
    $rest_timeC = $rou['rest_timeC'];
}
//従業員のデータを取得・実行
$sql = "SELECT * FROM employee WHERE Creatuser=".$_SESSION['employee']." OR Emp_id=".$_SESSION['employee']."";
$result = $mysqli->query($sql);
//失敗
if(!$result){
    print('取得が失敗しました'.$mysqli->error);
    $mysqli->close();
    exit();
}

/* //従業員のシフトデータを取得
$query = "SELECT * FROM shift";
$data = $mysqli->query($query);
 */
?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>シフト作成ページ</title>
<link rel="stylesheet" href="style.css">
<!-- Bootstrap読み込み（スタイリングのため） -->
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="jquery/jquery-leanModal.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<!-- <button type="button" class="btn btn-default" id="before">先月</button>
<button type="button" class="btn btn-default" id="after" >来月</button> -->
<a href="repertory.php"><button type="button" class="btn btn-default">勤務時間レパートリー</button></a>
<div class="panel panel-primary">
    <div class="panel-heading">シフト表</div>
<div class="table-responsive">
<table id="calender" class="table table-striped table-bordered">
    <tbody>
        <td align="center" rowspan="2">ID</td>
        <td align="center" rowspan="2">氏名</td>
        <?php
            while(checkdate($month,$d,$year)){ //日にちを表示
                echo "<th class=\"day\">$d</th>";
                $d++;
            }
            $d=1;
            echo "<tr>";

            while(checkdate($month,$d,$year)){ //曜日を表示
                switch(date("w",mktime(0,0,0,$month,$d,$year))){
                    case 0:
                    $week = '日';
                    break;

                    case 1:
                    $week = '月';
                    break;

                    case 2:
                    $week = '火';
                    break;

                    case 3:
                    $week = '水';
                    break;

                    case 4:
                    $week = '木';
                    break;

                    case 5:
                    $week = '金';
                    break;

                    case 6:
                    $week = '土';
                    break;
            }
            echo"<td>$week</td>";
            $d++;
            }
            echo "</tr>";
            /* $date = $data->fetch_all(); */
            
            while($row = $result->fetch_assoc()){//カレンダーに従業員表示
                echo "<tr data-toggle=\"modal\" data-target=\"#sampleModal\">
                        <td class=\"id\">{$row["Emp_id"]}</td>
                        <td class=\"name\" bgcolor=\"#f0fff0\" align=\"center\" nowrap>{$row["Emp_name"]}</td>";
                        $i = 1;
                        while($i < $d){
                            echo "<td class=\"cell\">";
                            /*for($j=0;!empty($date[$j]);$j++){
                                if($date[$j][5] == $i && $date[$j][1] == $row["Emp_id"]){
                                    echo "<input type=\"hidden\" name=\"start_time\"
                                    class=\"start_time\" value=\"{$date[$j][6]}\">";
                                }*/
                            echo "</td>";
                            $i++;
                            }
                echo  "</tr>";
            }
        ?>
    </tbody>
</table>
    <div id="XMLHttpRequest"></div><!-- ステータスコード -->
            <div id="textStatus"></div><!-- エラー情報 -->
            <div id="errorThrown"></div><!-- 例外情報 -->

            <div id="result"></div><!-- 返してきたデータを表示 -->
</div>
</div>
<!-- モーダル・ダイアログ -->
<div class="modal fade" id="sampleModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
				<h4 class="modal-title">勤務時間入力</h4>
			</div>
			<div class="modal-body">
				<form action="work_main.php" method="POST">
                    <p><input type="radio" id="A" name="time_repertory" value="<?php echo $start_timeA,$end_timeA,$rest_timeA;?>">
                    <?php echo "<label for=\"A\">A:開始:$start_timeA:終了:$end_timeA:休憩:$rest_timeA "?></p>
                    <br>
                    <p><input type="radio" id="B" name="time_repertory" value="<?php echo $start_timeB,$end_timeB,$rest_timeB;?>">
                    <?php echo "<label for=\"B\">B:開始:$start_timeB:終了:$end_timeB:休憩:$rest_timeB "?></p>
                    <br>
                    <p><input type="radio" id="C" name="time_repertory" value="<?php echo $start_timeC,$end_timeC,$rest_timeC;?>">
                    <?php echo "<label for=\"C\">C:開始:$start_timeC:終了:$end_timeC:休憩:$rest_timeC "?></p>
                    <br>
                    <input type="hidden" name="month" id="month" value="<?php echo $month ;?>">
                    <input type="hidden" name="day" id="day">
                    <input type="hidden" name="emp_id" id="emp_id">
                    <input type="hidden" name="emp_name" id="emp_name">
			</div>
			         <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				        <button type="submit" class="btn btn-primary" id="add" name="add">追加</button>
                </form>
                    </div>
		</div>
	</div>
</div>
<br>
<a href="adm_main.php"><button type="button" class="btn btn-default">メイン画面へ戻る</button></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    $('.cell').on('click',function(){
        //今月を代入
        var month = $('#month').val();
        console.log(month);
        //セル番地＝日にちとして代入
        var cell = $(this)[0].cellIndex - 1;
        $('#day').val(cell);
        console.log(cell);
        
        /* var start_time = $(this).find('input.start_time').val();
        $('#start').val(start_time);
        console.log(start_time); */
        //名前
        var name = $(this).parent().children('td.name').text();
        $('#emp_name').val(name);
        console.log(name);
        //セルをクリックされた人のID
        var id = $(this).parent().children('td.id').text();
        $('#emp_id').val(id);
        console.log(id);
        
       /*  var x = $('#day').val();
        console.log(x); */
    });
    $(document).ready(function(){
        //追加ボタンをクリック
        $('#add').click(function(){
            //POSTメソッドで送るデータを定義する
            var arg = {emp_id : $('#emp_id').val(),emp_name : $('#emp_name').val(),month : $('#month').val(),day : $('#day').val(),work_time:$("input[name='time_repertory']:checked").val()};
            
            $.ajax({
                type: "POST",
                url: "add.php",
                data: arg,
                //Ajax通信が成功した場合に呼び出されるメソッド
                success:function(data,dataType){
                    //デバッグ用アラートとコンソール
                    alert("OK");
                    alert(arg);
                    console.log(arg)
                },
                //Ajax通信が失敗した場合に呼び出されるメソッド
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert("error");
                }
            });
            return false;
        });
    });
    /*     $('#calender').on('click','td',function(){
    });
    $('#before').on('click',function(){
    }); */
</script>
</body>
</html>