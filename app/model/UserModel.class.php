<?php
/**
 * 會員模型實體類
 */
  class UserModel extends Model{
    private $user;   //使用者名稱
    private $pass;   //使用者密碼
    private $id;   //使用者 ID
    private $user_id;   //編輯者的 ID
    private $ckey;   //使用者 驗證序號
    private $level;   //使用者 等級
    private $levelName;   //使用者 等級 名
    private $nickname;
    private $email;
    private $phone;
    private $intro;
    private $photo;
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
                    w.id,
                    w.user,
                    w.ckey,
                    w.nickname,
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

    /** 查找特定會員存在*/
    public function OneUserExist(){
      $sql="SELECT 
                  id
            FROM
                  web_user
            WHERE
                 id = '$this->id'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }
    
    /** 顯示 1 位會員的會員中心資料*/
    public function UserCenter(){
      $sql="SELECT 
                  id,
                  user,
                  nickname,
                  email,
                  photo,
                  last_time,
                  reg_date,
                  follow,
                  fans,
                  collections,
                  recipes,
                  intro
            FROM
                  web_user
            WHERE
                  id = '$this->id'
            LIMIT 
                  1
            ";
      return parent::one($sql);
    }
    
    /** 顯示 1 位會員的基本資料 */
    public function getProfile(){
      $sql="SELECT 
                  w.id,
                  w.user,
                  w.nickname,
                  w.photo,
                  w.intro,
                  w.email,
                  w.phone,
                  p.name levelname
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

    /** 更新會員資料 */
    public function updateProfile(){
      $sql = "UPDATE 
                      web_user
              SET
                      nickname = '$this->nickname',
                      email = '$this->email',
                      phone = '$this->phone',
                      intro = '$this->intro',
                      photo = '$this->photo',
                      modify_date = NOW(),
                      modify_user = '$this->user_id'
              WHERE
                      id = '$this->id'
              LIMIT
                      1
              ";
      return parent::CUD($sql);
    } 
    
    /**查詢 所有 會員資料 */
    public function getAllUser(){
      $sql="SELECT 
                    w.id,
                    w.user,
                    w.photo,
                    w.last_ip,
                    w.last_time,
                    w.reg_date,
                    w.modify_date,
                    p.name
              FROM
                    web_user w,
                    privileges p
              WHERE
                    p.id = w.level
              ";
      return parent::all($sql);
    }

    /** 驗證 會員身分權限 */
    public function validateUser(){
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

    /** 驗證 是否是會員本人*/
    public function checkUserSelf(){
      $sql="SELECT 
                    id
              FROM
                    web_user
              WHERE
                    id = '$this->user_id'
              AND
                    ckey = '$this->ckey'
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