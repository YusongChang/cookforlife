<?php
/**
 * 文章模型實體類
 */
  class FocusArticleModel extends Model{

    private $id;   //Article ID
    private $user_id;   //編輯者的 ID
    private $title;   //文章標題
    private $summary;   //文章摘要
    private $content;   //內文
    private $state;   //是否發布
    private $modify_user;   //編輯者的 ID
    private $focus_img;   //封面圖


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

    /** 新增文章 */
    public function addArticle(){
      $focus = 1;
      $sql = "INSERT INTO 
                      article(
                              title,  
                              focus_img,
                              summary,
                              content,
                              date,
                              user_id,
                              state,
                              focus
                      )
              VALUES(
                      '$this->title',  
                      '$this->focus_img',
                      '$this->summary',
                      '$this->content',
                       NOW(),
                      '$this->user_id',
                      '$this->state',
                      '$focus'
                    )
              ";
      return parent::CUD($sql);
    } 

    /** 更新文章 */
    public function updateArticle(){
      $sql = "UPDATE 
                      article
              SET
                      title = '$this->title',  
                      focus_img = '$this->focus_img',  
                      summary = '$this->summary',  
                      content = '$this->content',
                      modify_date = NOW(),
                      modify_user = '$this->modify_user',
                      state = '$this->state'
              WHERE
                      id = '$this->id'
              LIMIT
                      1
              ";
      return parent::CUD($sql);
    } 

    /** 刪除文章 */
    public function deleteArticle(){
      $sql="DELETE FROM article WHERE id='$this->id' AND focus = 1 LIMIT 1";
      return parent::CUD($sql);
    }

    /** 顯示 1 篇文章 詳細 資料 */
    public function getOneArticleDetail(){

      $sql="SELECT 
                    a.id,
                    a.title,
                    a.summary,
                    a.content,
                    a.date,
                    a.modify_date,
                    a.user_id,
                    w.nickname
              FROM
                    article a,
                    web_user w
              WHERE
                    a.state != 1
                AND
                    a.id = '$this->id' 
                AND
                    w.id = a.user_id
                AND
                    a.focus = 1
              LIMIT 
                    1
              ";

      return parent::one($sql);
    }

    /** 查詢 1 篇文章資料 */
    public function getOneArticle(){
      $sql="SELECT 
                    id,
                    title,
                    focus_img,
                    summary,
                    content,
                    state
              FROM
                    article
              WHERE
                    id = '$this->id'
                AND
                    focus = 1 
              LIMIT 
                    1
              ";
      return parent::one($sql);
    }

    /**查詢 所有 文章資料 */
    public function getAllArticle(){
      $sql="SELECT 
                    a.id,
                    a.title,
                    a.summary,
                    a.content,
                    a.date,
                    a.modify_date,
                    a.user_id,
                    a.state,
                    a.hits,
                    w.nickname
              FROM
                    article a,
                    web_user w
              WHERE
                    w.id = a.user_id
                AND 
                    a.focus = 1
              ";
      return parent::all($sql);
    }

    /** 取得 1篇 焦點文章快照 */
    public function getOneFocus(){
      $sql="SELECT 
                  id,
                  title,
                  focus_img,
                  summary
            FROM
                  article
            WHERE
                  state != 1
              AND 
                  focus = 1

            ORDER BY id DESC
            
            LIMIT 1
            ";
      return parent::all($sql);
    }

    /** 查詢所有編輯人員資料 */
    public function getAllEditor(){
      $sql="SELECT
                    w.nickname
              FROM
                    article a,
                    web_user w
              WHERE
                    w.id = a.modify_user
              AND
                    a.focus = 1
              ";
      return parent::all($sql);
    }
    /** 查詢編輯人員資料 */
    public function getEditor(){
      $sql="SELECT
                    w.nickname
              FROM
                    article a,
                    web_user w
              WHERE
                    a.id = '$this->id'
              AND
                    w.id = a.modify_user
              AND
                    a.focus = 1
              LIMIT 1;
              ";
      return parent::one($sql);
    }

  }
      
?>