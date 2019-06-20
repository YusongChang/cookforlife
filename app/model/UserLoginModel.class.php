<?php
  class UserLoginModel extends Model{
    private $user;   //使用者名稱
    private $pass;   //使用者密碼
    private $id;   //使用者 ID
    private $ckey;   //使用者 驗證序號
    private $level;   //使用者 等級
    private $levelName;   //使用者 等級 名

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

    /**查詢 1 位會員資料 */
    public function getOneUser(){

      $sql="SELECT 
                  id,
                  user,
                  ckey
            FROM
                  web_user 

            WHERE
                  user = '$this->user'
            AND
                  pass = '$this->pass'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }


    /**驗證 管理員身分*/ 
    public function getOneManager(){

      $sql="SELECT 
                  w.id,
                  w.user,
                  w.ckey,
                  w.level,
                  p.name
            FROM
                  web_user w,
                  privileges p
            WHERE
                  w.user = '$this->user'
            AND
                  w.pass = '$this->pass'
            AND
                  p.id = w.level
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }

    /** 驗證 會員身分 */
    public function validateUser(){

      $sql="SELECT 
                  id
            FROM
                  web_user
            WHERE
                  id = '$this->id'
            AND
                  ckey = '$this->ckey'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }

    /** 驗證 管理員身分 */
    public function validateManager(){

      $sql="SELECT 
                  w.id
                  p.id iid
            FROM
                  web_user w,
                  privileges p

            WHERE
                  id = '$this->id'
            AND
                  ckey = '$this->ckey'
            AND   
                  p.id = '$this->level'
            AND
                  p.name = '$this->levelName'
            
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }

  }
      
?>