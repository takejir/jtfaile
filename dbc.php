<?php
require_once('env.php');

Class Dbc
{
    protected $table_name;

   // 1.データベースを接続
   // 引数：なし
   // 返り値：接続結果を返す
   protected function dbConnect() {
       $host = DB_HOST;
       $dbname = DB_NAME;
       $user = DB_USER;
       $pass = DB_PASS;
       $dsn = "データベース";
    
       try{
          $dbh = new PDO($dsn,$user,$pass,[
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          ]);
        }catch(PDOException $e){
           echo '接続失敗'. $e->getMessage();
           exit();
        };

       return $dbh;
    }

   // 2.データを取得する
   // 引数：なし
   // 返り値：取得したデータ
   public function getAll() {
       $dbh = $this->dbConnect();
       //①SQLの準備
       $sql = "SELECT * FROM $this->table_name";
       //②SQLの実行
       $stmt = $dbh->query($sql);
       //③SQLの結果を受け取る
       $result = $stmt->fetchall(PDO::FETCH_ASSOC);
       return $result;
       $dbh = null;
    }

   //引数：$id
   //返り値：$result
   public function getById($id) {
       if(empty($id)) {
           exit('IDが不正です。');
        }
    
       $dbh = $this->dbConnect();
    
       //SQL準備
       $stmt = $dbh->prepare("SELECT * FROM $this->table_name Where id = :id");
       $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
       //SQL実行
       $stmt->execute();
       //結果を取得
       $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
       if(!$result) {
           exit('ブログがありません');
        }

       return $result;

    }

    public function delete($id) {
        if(empty($id)) {
            exit('IDが不正です。');
         }
     
        $dbh = $this->dbConnect();
     
        //SQL準備
        $stmt = $dbh->prepare("DELETE FROM $this->table_name Where id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        //SQL実行
        $stmt->execute();
        echo 'ブログを削除しました！';
 
        return ; 
    }

}
?>
