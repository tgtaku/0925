<?php
$err_msg = "";

if(isset($_POST['search_user'])){
    require "conn.php";
    $id = $_POST["id"];
    $user_name = $_POST["user_name"];
    if($id =="" && $user_name == ""){
        $mysql_qry = "select * from users_information_1 inner join companies_information_1 on users_information_1.companies_id = companies_information_1.companies_id;";
    $result = mysqli_query($conn, $mysql_qry);
    if(mysqli_num_rows($result) > 0){
        $row_array_file = array();
        $i = 0;
        while($row = mysqli_fetch_assoc($result)){
            $row_array_file[$i] = $row;
            $i++;
        }
        print_r($row_array_file);
}
    }elseif($id !="" && $user_name == ""){
        $mysql_qry = "select * from users_information_1 inner join companies_information_1 on users_information_1.companies_id = companies_information_1.companies_id where '$id' = companies_name;";
        $result = mysqli_query($conn, $mysql_qry);
        if(mysqli_num_rows($result) > 0){
            $row_array_file = array();
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $row_array_file[$i] = $row;
                $i++;
            }
            print_r($row_array_file);
    }
    }else{
        $mysql_qry = "select * from users_information_1 inner join companies_information_1 on users_information_1.companies_id = companies_information_1.companies_id where '$user_name' = users_name;";
        $result = mysqli_query($conn, $mysql_qry);
        if(mysqli_num_rows($result) > 0){
            $row_array_file = array();
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $row_array_file[$i] = $row;
                $i++;
            }
            print_r($row_array_file);
    }

    }
    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>報告箇所登録画面</title>
    </head>
    <body>
    <h2>ユーザを登録してください。</h2>
    <p>
        <form action="p_entry_user2.php" method = "post">
        会社名<input type = "text" name = "id" value = ""><br />
        ユーザー名<input type ="text" name="user_name" value = ""><br />
        <input type = "submit" id = "search_user" name="search_user" value = "検索">
        </form>
    </p>     
    
    <form id="user_form">
    <table id = "user_info">
                <tr>
                    <th style="WIDTH: 200px" id="user_company">会社名</th>
                    <th style="WIDTH: 300px" id="user_name">ユーザ名</th>
                    <th> <input type = "checkbox" style="WIDTH: 60px" id="user_check" onclick="selectall()"></th>
                </tr>
            </table>
            <input type = "submit" id = "user_button" name="gotUser" value = "次へ">
    </form>
    
    </body>

</html>