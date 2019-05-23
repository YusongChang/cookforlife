<?php
/**
 * 收藏模型實體類
 */
  class CollectRecipeModel extends Model{
    private $collec_id;//收藏編號
    private $id;   //被收藏食譜的 ID
    private $user_id;   //收藏者的 ID
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

    public function collect(){
      $sql = "INSERT INTO
                    collections(
                      rec_id,
                      user_id
                    )
                VALUES(
                  '$this->id',
                  '$this->user_id'
                );
              UPDATE 
                    recipes
                SET
                    collected = collected+1
                WHERE
                    id = '$this->collec_id'
                LIMIT 1;
              UPDATE
                    web_user
                SET
                    collections = collections + 1
                WHERE
                    id = $this->user_id
                LIMIT 1;
              ";
      return parent::CUD($sql);
    }

    public function cancelCollect(){
      $sql = "DELETE 
                FROM
                    collections
                WHERE
                    rec_id = '$this->id'
                AND
                    user_id = '$this->user_id'
                LIMIT 1;

              UPDATE 
                    recipes
                SET
                    collected = collected-1
                WHERE
                    id = '$this->collec_id'
                LIMIT 1;

              UPDATE 
                    web_user
              SET
                  collections = collections-1
              WHERE
                  id = '$this->user_id'
              LIMIT 1;
              ";
      return parent::CUD($sql);
    }

    /** 驗證是否此食譜為自己的收藏 */
    public function selfCollection(){
      $sql = "SELECT
                    id
                FROM
                    collections
                WHERE
                    rec_id = '$this->id'
                  AND
                    user_id = '$this->user_id'
              LIMIT 1
            ";
      return parent::one($sql);
    }

    /** 移除收藏前驗證是否為自己的收藏 */
    public function deleteSelfCollection(){
      $sql = "SELECT
                    id
                FROM
                    collections
                WHERE
                    id = '$this->collec_id'
                  AND
                    rec_id = '$this->id'
                  AND
                    user_id = '$this->user_id'
              LIMIT 1
            ";
      return parent::one($sql);
    }
  }