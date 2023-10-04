<?php
    // DB接続設定
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = "パスワード";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //CREATE文：データベース内にテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS comment"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "username varchar(32),"
    . "str TEXT,"
    . "newpass TEXT,"
    . "date TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Board</title>
</head>
<body>
    <div>
        <h3>投稿フォーム</h3>
        <form method="post">
            
            <label for="username">名前:</label>
            <input type="text" name="username" value="<?php
            if(!empty($_POST["presubmit"])){
            //変数定義
                $edit = $_POST["edit"];
                $prepass = $_POST["prepass"];
                
            //該当番号のデータの取り出し準備
            //SELECT文
                $sql = 'SELECT * FROM comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
                $stmt->execute();                             
                $results = $stmt->fetchAll();
                
                foreach ($results as $row){
                    if($row['newpass'] == $prepass){
                        echo $row['username'];
                    }
                }
            }?>"><br>
            
            
            <label for="str">コメント:</label>
            <input type="text" name="str" value="<?php
            if(!empty($_POST["presubmit"])){
            //変数定義
                $edit = $_POST["edit"];
                $prepass = $_POST["prepass"];
                
            //該当番号のデータの取り出し準備
            //SELECT文
                $sql = 'SELECT * FROM comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
                $stmt->execute();                             
                $results = $stmt->fetchAll();
                
                foreach ($results as $row){
                    if($row['newpass'] == $prepass){
                        echo $row['str'];
                    }
                }
            }?>"><br>
            
            <label for="newpass">パスワード:</label>
            <input type="text" name="newpass"><br>
            
            <input type="text" name="editmark" value="<?php
            if(!empty($_POST["presubmit"])){
            //変数定義
                $edit = $_POST["edit"];
                $prepass = $_POST["prepass"];
                
            //該当番号のデータの取り出し準備
            //SELECT文
                $sql = 'SELECT * FROM comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
                $stmt->execute();                             
                $results = $stmt->fetchAll();
                
                foreach ($results as $row){
                    if($row['newpass'] == $prepass){
                        echo $row['id'];
                    }
                }
            }?>"><br>
            
            <input type="submit" name="submit">
        </form>
    </div>
    <hr>
    <div>
        <h3>削除フォーム</h3>
        <form method="post">
            <label for="delete">削除番号:</label>    
            <input type="number" name="delete"><br>
            <label for="delpass">パスワード:</label>
            <input type="text" name="delpass"><br>
            <input type="submit" name="delsubmit" value="削除">
        </form>
    </div>
    <hr>
    <div>
        <h3>編集フォーム</h3>
        <form method="post" action="5-1.php">
            <label for="edit">編集番号:</label>    
            <input type="number" name="edit"><br>
            <label for="prepass">パスワード:</label>
            <input type="text" name="prepass"><br>
            <input type="submit" name="presubmit" value="編集">
        </form>
    </div>
    <hr>
    
<?php
//新規投稿

    //送信すると
    if(!empty($_POST["submit"])){
        //名前が空欄なら
        if(empty($_POST["username"])){
            echo "名前を書き込んでください"."<br>";
        }
        //コメントが空欄なら
        if(empty($_POST["str"])){
            echo "コメントを書き込んでください"."<br>";
        }
        //パスワードが空欄なら
        if(empty($_POST["newpass"])){
            echo "パスワードを入力してください"."<br>";
        }
   
        //マークなしですべて受信したとき、    
        if(empty($_POST["editmark"]) && !empty($_POST["username"]) && !empty($_POST["str"]) && !empty($_POST["newpass"])){
            
            //INSERT文：データを入力
            $username = $_POST["username"];
            $str = $_POST["str"];
            $newpass = $_POST["newpass"];
            $date = date("Y/m/d H:i:s");
        
            $sql = "INSERT INTO comment (username, str, newpass, date) VALUES (:username, :str, :newpass, :date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':str', $str, PDO::PARAM_STR);
            $stmt->bindParam(':newpass', $newpass, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->execute();
            
            echo "投稿を受け付けました"."<br>";
            echo "<hr>";
        }
    }
    
//削除処理

    //送信すると
    if(!empty($_POST["delsubmit"])){
        //番号が空欄なら
        if(empty($_POST["delete"])){
            echo "削除したい番号を書き込んでください"."<br>";
        }
        //パスワードが空欄なら
        if(empty($_POST["delpass"])){
            echo "パスワードを書き込んでください"."<br>";
        }
        
        //番号とパスを受信したとき、
        if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
            //変数定義
            $delpass=$_POST["delpass"];
            $delete = $_POST["delete"];
        
            //保存データの取り出し準備
                //SELECT文
                $sql = 'SELECT * FROM comment';
                $stmt = $pdo->prepare($sql);                  
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
            //一行ずつ確認
                foreach ($results as $row){
                    //リクエストと番号一致するなら
                    if($row['id'] == $delete){
                    //パスが一致しないなら
                        if($row['newpass'] != $delpass){
                            echo "パスワードは無効です";
                        }else{//一致するなら
                            echo "削除に成功しました";
                            $sql = 'delete from comment where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
        }

    }
    
    
//編集準備
    //送信すると
    if(!empty($_POST["presubmit"])){
        //変数定義
        $edit = $_POST["edit"];
        $prepass = $_POST["prepass"];
        
        if(empty($_POST["edit"])){
            echo "編集したい番号を書き込んでください"."<br>";
        }
        
        //保存データの取り出し準備
        //SELECT文
        $sql = 'SELECT * FROM comment where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
        $stmt->execute();                             
        $results = $stmt->fetchAll();
        
        //一行ずつ確認
        foreach ($results as $row){
            
                //パスワードが埋まっているか
                if(empty($_POST["prepass"])){
                    echo "パスワードを書き込んでください"."<br>";
                }else{
                    //パスワードが一致するか
                    if($row['newpass'] == $prepass){
                        echo "フォームから編集を行ってください";
                    }else{
                        echo "パスワードは無効です";
                    }
                }
        }
    }

//編集
    //マークつきでリクエストとパスを受信したとき、
                    
    if(!empty($_POST["submit"]) && !empty($_POST["editmark"]) && !empty($_POST["username"]) && !empty($_POST["str"]) && !empty($_POST["newpass"])){
    //変数定義
        $id = $_POST["editmark"]; 
        $username = $_POST["username"];
        $str = $_POST["str"];
        $newpass = $_POST["newpass"];
        $date = date("Y/m/d H:i:s");
                            
        $sql = 'SELECT * FROM comment where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();                             
        $results = $stmt->fetchAll();
                            
        foreach($results as $row){        
                    //パスが一致しないなら
            if($row['newpass'] != $newpass){
                echo "パスワードが無効です";
            }else{//一致するなら
                echo "編集に成功しました"."<br>";
                //UPDATE文：入力されているデータレコードの内容を編集
                $sql = 'UPDATE comment SET username=:username,str=:str,newpass=:newpass,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':str', $str, PDO::PARAM_STR);
                $stmt->bindParam(':newpass', $newpass, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
            
    }elseif(empty(["newpass"])){
        echo "パスワードを記入をしてください";
    }
?>
<hr>
<?php
    //SELECT文：入力したデータレコードを抽出し、表示する
        $sql = 'SELECT * FROM comment';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['username'].' ';
            echo $row['str'].' ';
            echo $row['date'].'<br>';
        }

?>
</body>
</html>

