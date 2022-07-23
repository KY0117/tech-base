

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-27</title>
</head>
<body>
<form action="" method="post">
        <input type="text" name="edit" placeholder="編集対象番号">
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit">
    </form>
    
    <?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS list"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "myname char(32),"
    . "mycomment TEXT,"
    . "mypassword char(32),"
    . "mydate TEXT"
    .");";
    $stmt = $pdo->query($sql);

    if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $key=-1;

        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row["id"]==$_POST["edit"] && $row["mypassword"]==$_POST["editpass"]){
                echo "下の欄で新しい名前、コメント、パスワードを設定してください。<br><br>";
                $key=1;
                $editnum=$_POST["edit"];
                $sql = 'SELECT * FROM list';
                $stmt = $pdo->query($sql);
                $results2 = $stmt->fetchAll();
                foreach ($results2 as $row2){
                    if($row2["id"]==$_POST["edit"]){
                        echo '<font color="red">'.$row2['id'].','.$row2['myname'].','.$row2['myname'].','.$row2['mydate'].'<br>'.'</font>'.'<hr>';
                    }else{
                        echo $row2['id'].','.$row2['myname'].','.$row2['myname'].','.$row2['mydate'].'<br><hr>';
                    }
                }
            }
        }
        if($key==-1){
            echo "削除対象番号orパスワードが間違っています。<br><br>";
            $sql = 'SELECT * FROM list';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['myname'].',';
                echo $row['mycomment'].',';
                echo $row['mypassword'].',';
                echo $row['mydate'].'<br>';
                echo "<hr>";
            }
        }
    }elseif(!empty($_POST["editpass"])){
        echo "編集対象番号が入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }elseif(!empty($_POST["edit"])){
        echo "パスワードが入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value=<?php if(!empty($editname)){echo $editname;} ?>>
        <input type="text" name="comment" placeholder="コメント" value=<?php if(!empty($editcomment)){echo $editcomment;}?>>
        <input type="text" name="password" placeholder="パスワード">
        <input type="checkbox" name="new" value="新規"<?php if(empty($editnum)){echo "checked";}?> disabled>新規
        <input type="checkbox" name="editcontent" value="編集"<?php if(!empty($editnum)){echo "checked";}?> disabled>編集
        <input type="hidden" name="editnum" value=<?php if(!empty($editnum)){echo $editnum;}?>>
        <input type="submit">
    </form>
    <form action="" method="post">
        <input type="text" name="delete" placeholder="削除対象番号">
        <input type="text" name="deletepass" placeholder="パスワード">
        <input type="submit">
    </form>
    
    <?php
    
    if(!empty($_POST["comment"]) && !empty($_POST["name"]) && empty($_POST["editnum"]) && !empty($_POST["password"])){
        
        $sql = $pdo -> prepare("INSERT INTO list (myname, mycomment, mypassword, mydate) VALUES (:myname, :mycomment, :mypassword, :mydate)");
        $sql -> bindParam(':myname', $myname, PDO::PARAM_STR);
        $sql -> bindParam(':mycomment', $mycomment, PDO::PARAM_STR);
        $sql -> bindParam(':mypassword', $mypassword, PDO::PARAM_STR);
        $sql -> bindParam(':mydate', $mydate, PDO::PARAM_STR);
        $myname = $_POST["name"];
        $mycomment = $_POST["comment"]; 
        $mypassword = $_POST["password"];
        $mydate = date("Y/m/d H:i:s");
        $sql -> execute();

        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }elseif((!empty($_POST["comment"]) or !empty($_POST["name"]) or !empty($_POST["password"])) && empty($_POST["editnum"]) ){
        echo "名前orコメントorパスワードが入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }
    
    if(!empty($_POST["comment"]) && !empty($_POST["name"]) && !empty($_POST["editnum"]) && !empty($_POST["password"])){
        $id = $_POST["editnum"]; 
        $myname = $_POST["name"];
        $mycomment = $_POST["comment"]; 
        $mypassword = $_POST["password"];
        $mydate = date("Y/m/d H:i:s");
        $sql = 'UPDATE list SET myname=:myname,mycomment=:mycomment,mypassword=:mypassword,mydate=:mydate WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':myname', $myname, PDO::PARAM_STR);
        $stmt->bindParam(':mycomment', $mycomment, PDO::PARAM_STR);
        $stmt->bindParam(':mypassword', $mypassword, PDO::PARAM_STR);
        $stmt->bindParam(':mydate', $mydate, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
        
    }elseif((!empty($_POST["comment"]) or !empty($_POST["name"]) or !empty($_POST["password"]) )&& !empty($_POST["editnum"])){
        echo "名前orコメントorパスワードが入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }
    
    
    if(!empty($_POST["delete"]) && !empty($_POST["deletepass"])){
        $key=-1;

        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id']==$_POST["delete"] && $row['mypassword']==$_POST["deletepass"]){
                $key=1;
                $id = $_POST["delete"];
                $sql = 'delete from list where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        if($key==-1){
            echo "削除対象番号orパスワードが間違っています。<br><br>";
        }

        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }

    }elseif(!empty($_POST["deletepass"])){
        echo "削除対象番号が入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }elseif(!empty($_POST["delete"])){
        echo "パスワードが入力されていません。<br><br>";
        $sql = 'SELECT * FROM list';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['myname'].',';
            echo $row['mycomment'].',';
            echo $row['mydate'].'<br>';
            echo "<hr>";
        }
    }
    
    ?>
</body>
</html>