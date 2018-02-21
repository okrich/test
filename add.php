<?php
//データベースに接続するための宣言
$con = mysqli_connect('localhost','root','amamijoho') or die(mysqli_error());
mysqli_set_charset($con,"utf-8");
mysqli_select_db($con,'employee');
mysqli_query($con,'SET NAMES UTF8');

function hsc($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

//このページでechoしたものがhtmlに返されて出力される
header("Content-type: text/plain; charset=UTF-8");

//Ajaxによるリクエストかどうかの識別を行う
//strtolower()をつけるのはXMLHttpRequestやxmlHttpRequestで返ってくる場合があるため
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    if(isset($_POST['add'])){
        $id = $_POST['emp_id'];
        $name = $_POST['emp_name'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        //$work_time = $_POST['work_time'];

        $sql = "INSERT INTO shift(emp_id,emp_name,shift_month,shift_day) VALUES('$id','$ame','$month','$day')";
        $res = mysqli_query($con,$sql);
        
        if(!$res){
            print('保存が失敗しました。' . $con->error);
            $mysqli->close();
            exit();
        }

        if(mysqli_num_rows($res) != 0){
            while($row = mysqli_fetch_assoc($res)){
                $emp_id = $row['emp_id'];
                $emp_name = $row['emp_name'];
                $shift_month = $row['shift_month'];
                $shift_day = $row['shift_day'];
                $String .= "<p>".$emp_id."</p>";
                $String .= "<h3>".$emp_name."</h3>";
                $String .= "<p>".$shift_month."</p>";
                $String .= "<p>".$shift_day."</p>";
            }
        }   
        else{
            echo $sql;//デバッグ用 データがなかった時にSQLを出力する
        }
    }
}
?>