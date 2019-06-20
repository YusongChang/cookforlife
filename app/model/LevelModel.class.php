<?php
/**
 * 會員模型實體類
 */
  class LevelModel extends Model{
    private $id;   //使用者 ID

  /**
  * 取得 private variables ，
  * 魔術函數--攔截器 __set()
  *  __set('$_name',$_value)，*重要!!! 須轉譯 跳脫字元 避免被惡意注入攻擊
  * @param string $name 變數名稱
  * @param mixed $value 數值
  */
  public function __set($name,$value){  // *外部類存取本類的私有成員及為其賦值
    $this->$name = Tool::mysqliString($value); //ex: $name = 'state'; $this->$name;(指的是依照變數名取得該變數位址);$this->$name = $value 賦值給 $this->state;
  }

    /**查詢 1 筆等級資料 */
    public function getOneLevel(){

      $sql="SELECT 
                    id,
                    name
              FROM
                    privileges
              WHERE
                    id = '$this->id'

              LIMIT 
                    1
              ";
      return parent::one($sql);
    }

    /** 查找特定會員存在*/
    public function OneUserExist(){
      $sql="SELECT 
                  w.id,
                  w.user,
                  w.photo,
                  w.ckey,
                  w.level,
                  p.name
            FROM
                  web_user w,
                  privileges p
            WHERE
                  w.id = '$this->id'
            AND
                  p.id = w.level
            LIMIT 
                  1
            ";
      return parent::one($sql);
    } 
    
    /**查詢 所有 會員資料 */
    public function getAllLevel(){
      
      $sql="SELECT 
                  id,
                  name
            FROM
                  privileges
            ";
      return parent::all($sql);
    }

    /** 驗證 會員身分權限 */
    public function validateUser(){
      //p.iid 為 privileges 等級編號
      $sql="SELECT 
                  w.id
            FROM
                  web_user w,
                  privileges p
            WHERE
                  w.id = '$this->user_id'
            AND
                  w.ckey = '$this->ckey'
            AND   
                  p.id = '$this->level'
            AND
                  p.name = '$this->levelName'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }

    /** 驗證 管理員權限 */
    public function validateManager(){
      //p.id 為 privileges 等級編號
      $sql="SELECT 
                  p.id
            FROM
                  web_user w,
                  privileges p
            WHERE
                  w.id = '$this->user_id'
            AND
                  w.ckey = '$this->ckey'
            AND   
                  p.id = '$this->level'
            AND
                  p.name = '$this->levelName'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }

    /** 紀錄使用者登入 ip 時間 次數 */
    public function userLogin(){
      $sql = "UPDATE
                    web_user
              SET
                    last_ip = '".$_SERVER['REMOTE_ADDR']."',
                    last_time = NOW(),
                    login_count = login_count + 1
              WHERE
                    id = '$this->id'
              LIMIT 
                    1";
      return parent::CUD($sql);
    }

  }
      
?>