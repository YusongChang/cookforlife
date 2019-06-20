<?php
/**
 * 食譜模型實體類
 */
class RecipeModel extends Model{
  private $id;          //原創食譜 id / 也可以是跟著試作
  private $user_id;     //使用者 id
  private $follow;      //*若這是試作食譜則 紀錄 原創食譜 id
  private $user_name;   //使用者名稱
  private $user_ckey;   //使用者 驗證序號
  private $dish_name;   //食譜名稱
  private $finish_img;  //成品圖片地址(或會員跟著做)
  private $summary;     //烹飪心得(或會員跟著做)
  private $step_xml;    //調理方式 xml 檔 路徑
  private $state;       //發布狀態

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

  public function __get($name){ // *取得外部類的私有成員"名稱"及其值
    return $this->$name; //$ 依照 參照位址 取得變數值
  }

  /** 新增食譜 */
  public function addRecipe(){

    $sql="INSERT  INTO
                      recipes(
                              user_id,
                              title,
                              summary,
                              finish_img,
                              step_xml,
                              state,
                              date

                      )
                  VALUES(
                          '$this->user_id',
                          '$this->dish_name',
                          '$this->summary',
                          '$this->finish_img',
                          '$this->step_xml',
                          '$this->state',
                          NOW()
                  )";
    return parent::CUD($sql);
  }

  /** 更新食譜 */
  public function updateRecipe(){
    $sql="UPDATE  
                  LOW_PRIORITY 
                  recipes 
            SET
                  title = '$this->dish_name',
                  summary = '$this->summary',
                  finish_img = '$this->finish_img',
                  step_xml = '$this->step_xml',
                  state = '$this->state',
                  modify_date = NOW(),
                  modify_user = '$this->user_id'
          WHERE
               id = '$this->id'
          LIMIT 1
          ";
    return parent::CUD($sql);
  }

  /** 更新點閱數 */
  public function updatehits(){
    $sql="UPDATE 
                  LOW_PRIORITY  
                  recipes 
            SET
                  hits = hits + 1
            WHERE
                  id = '$this->id'
            LIMIT 1
          ";
  }

  /** 更新食譜 */
  public function deleteRecipe(){
    $sql="DELETE 
            FROM  
                  recipes 
          WHERE
                id = '$this->id'
          LIMIT 1
          ";
    return parent::CUD($sql);
  }


  /** 獲取食譜快照內容 */
  public function getSnapShot(){
    $sql="SELECT
                  r.id,
                  r.finish_img,
                  r.title,
                  r.date,
                  r.modify_date,
                  r.hits,
                  r.collected,
                  u.nickname
            FROM
                  recipes r,
                  web_user u
            WHERE 
                  r.follow IS NULL
              AND
                  u.id = r.user_id
              AND 
                  r.state = 0
          ";
    return parent::all($sql);
  }

  /**獲取個人發布的食譜表 */
  public function getUserRecipeList(){
    $sql="SELECT
                  id,
                  finish_img,
                  title,
                  date
            FROM
                  recipes 
            WHERE 
                  follow IS NULL
              AND
                  user_id = '$this->user_id'

              AND 
                  state = 0
          ";
    return parent::all($sql);
  }

  /** 食譜詳細內容 */
  public function getDetails(){

    $sql="SELECT
                  r.id,
                  r.user_id,
                  r.follow_people,
                  r.title,
                  r.summary,
                  r.finish_img,
                  r.step_xml,
                  r.date,
                  r.modify_date,
                  r.tag_xml,
                  r.hits,
                  r.collected,
                  r.shared,
                  u.id iid,
                  u.nickname
            FROM
                  recipes r,
                  web_user u
            WHERE 
                  r.id = '$this->id'
            AND
                  u.id = r.user_id
            AND
                  r.state = 0
            AND 
                  r.follow IS NULL
            LIMIT 1";
    return parent::one($sql);
  }

  /** 新增食譜試作 */
  public function addTrial(){
    $parent = "
              "; //取得原創食譜目前的試作人數
    $sql="INSERT  INTO
                      recipes(
                              user_id,
                              follow,
                              title,
                              summary,
                              finish_img,
                              date
                      )
                  VALUE(
                        '$this->user_id',
                        '$this->id',
                        '$this->dish_name',
                        '$this->summary',
                        '$this->finish_img',
                        NOW()
                  );
          UPDATE 
                    recipes 
            SET 
                    follow_people=follow_people+1 
            WHERE 
                    id = '$this->id'
            ";
    return parent::CUD($sql);
  }

    /** 新增食譜試作 */
    public function updateTrial(){
      $parent = "
                "; //取得原創食譜目前的試作人數
      $sql="UPDATE
                    recipes

                SET
                      summary = '$this->summary',
                      finish_img = '$this->finish_img',
                      modify_date = NOW()
                WHERE
                      id = '$this->id'
                LIMIT 1
            ";
      return parent::CUD($sql);
    }

  /** 食譜快照內容 
   * @param String $flag 'follow' 獲取所有試作資料 'id' 指定一筆試做資料
   * @return Object Aarry
  */
  public function getTrialSnapShot($flag='follow'){
      $sql="SELECT
                    r.id,
                    r.follow,
                    r.finish_img,
                    r.summary,
                    r.title,
                    r.date,
                    u.user,
                    u.id iid
              FROM
                    recipes r,
                    web_user u
              WHERE 
                    r.$flag = ".$this->$flag."
                AND
                    u.id = r.user_id
                AND r.state = 0;
              ";
      if($flag == 'id') return parent::one($sql);
      return parent::all($sql);
    }
  
  /** 獲取試作食譜內容 */
  
  public function getTrialContent(){
    $sql="SELECT
                  r.id,
                  r.user_id,
                  r.summary,
                  r.finish_img,
                  r.date,
                  u.id iid,
                  u.user
            FROM
                  recipes r,
                  web_user u
            WHERE 
                  r.follow ='$this->follow'
            AND
                  r.user_id = '$this->user_id'
            LIMIT 1";
    return parent::one($sql);
  }

  /** 計算試作食譜的人數*/
  public function getTrialTotal(){
    $sql = "SELECT * FROM recipes WHERE follow ='$this->follow'";
    return parent::total($sql);
  }  

  /** 搜尋食譜是否存在 */
  public function checkRecipe(){
    $sql = "SELECT 
                    id,
                    user_id 
              FROM 
                    recipes 
              WHERE 
                    id ='$this->id' 
                OR 
                    follow ='$this->follow' 
              LIMIT 1
            ";

    return parent::one($sql);
  }
  /** 取得個人的食譜收藏 */
  public function getUserCollection(){
    $sql="SELECT
                r.id,
                r.finish_img,
                r.title,
                r.date,
                w.nickname
            FROM
                recipes r,
                web_user w,
                collections c
            WHERE 
                r.follow IS NULL
            AND
                c.user_id = '$this->user_id'
            AND
                r.id = c.rec_id
            AND
               w.id =  c.user_id
            AND 
                r.state = 0
            ";
    return parent::all($sql);
  }
  /** 取得預計產生的新 id*/
  public function nextId(&$tableName){
    return parent::nextId($tableName);
  }

}