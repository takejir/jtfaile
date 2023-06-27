<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>①mission_3-5</title>
</head>
<body>
    <?php
    
      $filename = "①mission_3-5.txt";
      $passfile = "①mission_3-5_pass.txt";
      //投稿機能

  //フォーム内が空でない場合に以下を実行する
  if (!empty($_POST["name"]) && !empty($_POST["comment"])) {

    //入力データの受け取りを変数に代入
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    //日付データを取得して変数に代入
    $postedAt = date("Y年m月d日 H:i:s");

    // editNoがないときは新規投稿、ある場合は編集 ***ここで判断
    if (empty($_POST["editNO"])&&!empty($_POST["pass"])) {
      // 以下、新規投稿機能
      //ファイルの存在がある場合は投稿番号+1、なかったら1を指定する
      if (file_exists($filename)) {
        $num = count(file($filename)) + 1;
      } else {
        $num = 1;
      }

      //書き込む文字列を組み合わせた変数
      $newdata = $num."<>".$name."<>".$comment."<>" . $postedAt;

      //ファイルを追記保存モードでオープンする
      $fp = fopen($filename, "a");
      //入力データのファイル書き込み
      fwrite($fp, $newdata . "\n");
      fclose($fp);
      
      $fp1 = fopen($passfile, "a");
            $pas = $num."<>".$pass;
            fwrite($fp1, $pas.PHP_EOL);
            fclose($fp1);
    } else {
      // 以下編集機能
      //入力データの受け取りを変数に代入
      $editNO = $_POST["editNO"];
      //読み込んだファイルの中身を配列に格納する
      $ret_array = file($filename);
      //ファイルを書き込みモードでオープン＋中身を空に
      $fp = fopen($filename, "w");
      //配列の数だけループさせる
      foreach ($ret_array as $line) {
        //explode関数でそれぞれの値を取得
        $data = explode("<>", $line);
        //投稿番号と編集番号が一致したら
        if ($data[0] == $editNO) {
          //編集のフォームから送信された値と差し替えて上書き
          fwrite($fp, $editNO . "<>" . $name . "<>" . $comment . "<>" . $postedAt . "\n");
        } else {
          //一致しなかったところはそのまま書き込む
          fwrite($fp, $line);
        }
      }
      fclose($fp);
    }
  }
      
      //削除機能

      //削除フォームの送信の有無で処理を分岐
      if (!empty($_POST["dnum"])&&!empty($_POST["delpass"])) {
          //入力データの受け取りを変数に代入
          $delete = $_POST["dnum"];
          $delpass = $_POST["delpass"];
          //読み込んだファイルの中身を配列に格納する
          $delCon = file($filename, FILE_IGNORE_NEW_LINES);
          $paslines = file($passfile,FILE_IGNORE_NEW_LINES);
          //ファイルを書き込みモードでオープン＋中身を空に
          $fp = fopen($filename,"w");
          $fp1 = fopen($passfile, "w");
          $flg = 0;
         //パスワードファイル
        for($i = 0; $i < count($paslines); $i++){
            $pasline = explode("<>", $paslines[$i]);
            if($pasline[0] == $delete && $pasline[1] == $delpass){
                $flg = 1;
            }
        }
        //投稿リスト
        for($j = 0; $j < count($delCon); $j++){
            $delline = explode("<>", $delCon[$j]);
            if($flg == 1){
                if($delline[0] != $delete){
                    fwrite($fp, $delCon[$j].PHP_EOL);
                    fwrite($fp1, $paslines[$j].PHP_EOL);
                }
            }
        }
        fclose($fp1);
        fclose($fp);
    }

      //編集選択機能

      //編集フォームの送信の有無で処理を分岐
      if (!empty($_POST["edit"])&&!empty($_POST["editpass"])) {
          //入力データの受け取りを変数に代入
          $edit = $_POST["edit"];
          $editpass = $_POST["editpass"];
          //読み込んだファイルの中身を配列に格納する
          $editCon = file($filename,FILE_IGNORE_NEW_LINES);
          $paslines = file($passfile,FILE_IGNORE_NEW_LINES);
          for($i = 0; $i < count($paslines); $i++){
            $paslin = explode("<>", $paslines[$i]);
            if($paslin[0] == $edit && $paslin[1] == $editpass){
          //配列の数だけループさせる
          foreach ($editCon as $line) {
              //explode関数でそれぞれの値を取得
              $editdata = explode("<>",$line);
              //投稿番号と編集対象番号が一致したらその投稿の「名前」と「コメント」を取得
              if ($edit == $editdata[0]) {
                  //投稿のそれぞれの値を取得し変数に代入
                  $editnumber = $editdata[0];
                  $editname = $editdata[1];
                  $editcomment = $editdata[2];

                  //既存の投稿フォームに、上記で取得した「名前」と「コメント」の内容が既に入っている状態で表示させる
                  //formのvalue属性で対応
              }
            }
      }
          }
      }
  ?>
  
  <form action=" " method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
      <input type="hidden" name="editNO" placeholder="編集する番号" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="password" name="pass" placeholder="パスワード">
      <input type="submit" name="submit" value="送信">
    </form>

    <form action=" " method="post"><br>
      <input type="text" name="dnum" placeholder="削除対象番号"><br>
      <input type="password" name="delpass" placeholder="パスワード">
      <input type="submit" name="delete" value="削除">
    </form>

    <form action=" " method="post"><br>
      <input type="text" name="edit" placeholder="編集対象番号"><br>
      <input type="password" name="editpass" placeholder="パスワード">
      <input type="submit" value="編集">
    </form>

<?php
      //表示機能
      //ファイルの存在がある場合だけ行う
      if (file_exists($filename)) {
          //読み込んだファイルの中身を配列に格納する
          $array = file($filename);
          //取得したファイルデータを全て表示する（ループ処理）
          foreach ($array as $word) {
                //explode関数でそれぞれの値を取得
                $getdata = explode("<>",$word);
                //取得した値を表示する
                echo $getdata[0] . " " . $getdata[1] . " " . $getdata[2] . " " . $getdata[3] . "<br>";
          }
      }
    ?>
</body>
</html>