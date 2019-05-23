<?php
/**
 * 留言模型實體類
 */
class CommentModel extends Model{
  private $id;          //留言 id
  private $rec_id;      //食譜 id
  private $user_id;     //使用者 id
  private $user_name;   //使用者名稱
  private $user_ckey;   //使用者 驗證序號
  private $comment;     //留言內容
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

  /** 新增留言 */
  public function addComment(){
    $timestamp = time();
    if(intval($this->reply) === 0){
      $sql="INSERT  INTO
                          comment(
                                  rec_id,
                                  user_id,
                                  content,
                                  date,
                                  timestamp
                          )
                  VALUE(
                        '$this->rec_id',
                        '$this->user_id',
                        '$this->comment',
                        NOW(),
                        $timestamp
                      )
              ";
    }else{
      $sql="INSERT  INTO
                      comment(
                              rec_id,
                              user_id,
                              reply,
                              pid,
                              content,
                              date,
                              timestamp
                      )
                  VALUE(
                        '$this->rec_id',
                        '$this->user_id',
                        '$this->reply',
                        '$this->pid',
                        '$this->comment',
                        NOW(),
                        $timestamp
            )";
    }
    return parent::CUD($sql);
  }

  /** 計算留言數*/
  public function getCommentTotal(){
    $sql = "SELECT 
                    id 
              FROM 
                    comment 
              WHERE 
                    rec_id ='$this->rec_id'
              AND
                    reply IS NULL
            ";
    return parent::total($sql);
  }  

  /** 或取留言內容*/
  public function getComment(){

    $sql="SELECT 
                c.id,
                c.user_id,
                c.content,
                c.date,
                c.reply,
                c.date,
                c.timestamp,
                w.id iid,
                w.nickname
            FROM
                comment c,
                web_user w
            WHERE
                c.rec_id = '$this->rec_id'
            AND
                w.id = c.user_id
            AND
                c.reply IS NULL
            ORDER BY date ASC
          ";
    return parent::all($sql);
  }
  
  /** 或取回覆留言內容*/
  public function getReply(){
    $sql = "SELECT 
                  c.id,
                  c.user_id,
                  c.reply,
                  c.pid,
                  c.content,
                  c.date,
                  c.timestamp,
                  w.id iid, 
                  w.nickname,
                  w.photo,
                  w2.nickname uuser
              FROM
                  comment c,
                  web_user w,
                  web_user w2
              WHERE 
                  c.rec_id ='$this->rec_id'
              AND 
                  c.reply IS NOT NULL
              AND
                  w.id = c.user_id
              AND 
                  w2.id = c.reply
              ";
    return parent::all($sql);
  }

}