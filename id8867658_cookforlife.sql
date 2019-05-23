-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 03 日 07:33
-- 伺服器版本： 10.1.38-MariaDB
-- PHP 版本： 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `id8867658_cookforlife`
--

-- --------------------------------------------------------

--
-- 資料表結構 `article`
--

CREATE TABLE `article` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//文章編號',
  `title` varchar(100) NOT NULL COMMENT '//標題',
  `focus_img` varchar(300) NOT NULL COMMENT '//焦點文章封面',
  `summary` varchar(300) NOT NULL COMMENT '//摘要(快照用）',
  `content` mediumtext NOT NULL COMMENT '//內容',
  `date` date NOT NULL COMMENT '//日期',
  `modify_date` datetime DEFAULT NULL COMMENT '//修改日期',
  `modify_user` mediumint(8) UNSIGNED DEFAULT NULL COMMENT '//修改者的會員 id',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '//作者會員 id',
  `state` tinyint(1) NOT NULL COMMENT '// 0發布 1草稿',
  `focus` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '//焦點文章'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `article`
--

INSERT INTO `article` (`id`, `title`, `focus_img`, `summary`, `content`, `date`, `modify_date`, `modify_user`, `user_id`, `state`, `focus`) VALUES
(1, '白醋、烏醋有什麼不同？料理怎麼使用？烏醋原來還多了', '../uploaded/article_img/0-2-20190424-173203.png', '調味料中有許多畫龍點睛的醬料，像是一白一黑的調味醋，每回煮羹湯、醬燒魚等料理，如果少了點醋，雖然仍然美味，心裡卻明白缺了個味。醋有白醋和烏醋兩種，嚐起來風味、香氣不同，有時也不知道白醋、烏醋較適合使用在哪些料理、如何使用更速配？白醋、烏醋這兩種微酸、但會上癮的滋味，值得你花點時間認識其中差異！', '<h2 style=\"margin-left: 0px;\"><strong><span style=\"color: rgb(255, 102, 0);\">白醋</span></strong></h2><p><img src=\"https://uploads-blog-icook.icook.network/2019/02/4bf68c1b-vinegar-pickled-chilli_41338-1636.jpg\" alt=\"\" width=\"735\" height=\"489\"><span style=\"color: rgb(117, 115, 115);\">Photo：<a href=\"https://www.freepik.com/premium-photo/vinegar-pickled-chilli_3997277.htm\">freepik.com</a></span></p><ul><li><strong><span style=\"color: rgb(128, 0, 0);\">特性：</span></strong>清澈透明、酸味足</li><li><strong><span style=\"color: rgb(128, 0, 0);\">適合搭配的料理：</span></strong>醃漬、湯品、沾醬、肉類</li></ul><p style=\"\">透明澄澈的白醋，主要使用<strong>糯米或糙米</strong>等穀物為主原料，經過添加酵母菌、醋酸菌，或是使用酒精發酵而成，成分為糯米、水，或含酒精。白醋酸味足夠，適合開胃、解膩以及增加酸味使用。</p><p>白醋顏色透明，使用在料理中不影響到顏色，而酸味夠，只需要少許便能達到效果。<strong>醋酸有軟化肉質的效果</strong>，醃肉時想讓肉變得軟嫩，可在醃肉時滴入少許。</p><p>白醋的經典料理用來醃漬蔬果、小菜，如醃泡菜、小黃瓜、白蘿蔔等，就是那酸中帶勁、能一口接一口的絕配滋味！醋酸使蔬果入味，搭配鹽巴也利於食物保存。</p><h2 style=\"margin-left: 0px;\"><strong><span style=\"color: rgb(255, 102, 0);\">烏醋</span></strong></h2><p></p><p><img src=\"../uploaded/article_img/0-2-20190424-173203.png\" width=\"735\" height=\"550.6562709226096\"></p>', '2019-04-22', '2019-04-25 09:37:05', 2, 1, 0, NULL, 1),
(2, '完美水煮蛋煮法（小撇步）', '../uploaded/article_img/0-2-20190502-114303.png', '中間微微濕潤且沒有蛋腥味的漂亮水煮蛋其實只需要幾個小撇步就ok！', '<p><span style=\"color: rgb(51, 51, 51);\">1.使用室溫雞蛋可避免蛋殼煮到一半裂開（也可在使用前一小時取出退冰）</span><br></p><p><img src=\"../uploaded/article_img/0-2-20190502-114115.png\" width=\"200\" height=\"150\"><br></p><p><span style=\"color: rgb(51, 51, 51);\">2.</span><span style=\"color: rgb(51, 51, 51);\">在\"鈍端\"用針戳出三個洞，可幫助氣室中的氣體排出，這樣就會有完美的橢圓形</span><br></p><p><img src=\"../uploaded/article_img/0-2-20190502-113947.png\" width=\"200\" height=\"150\"></p><p>3.水量蓋過雞蛋即可，加入鹽巴放入滾水前先拿著雞蛋畫圓，讓蛋黃在中央滾煮7分鐘 關火悶1分鐘⚠️仔細看能看到氣孔在冒泡喔！</p><p><img src=\"../uploaded/article_img/0-2-20190502-113839.png\" width=\"200\" height=\"150\"></p><p>4.滾煮7分鐘 關火悶1分鐘時間到馬上泡入冷水（冰水效果更好，不過我煮的時候忘了準備冰塊，哈哈）</p><p><img src=\"../uploaded/article_img/0-2-20190502-114036.png\" width=\"200\" height=\"150\"></p><p>5.蛋都涼透後，在水中將表面敲到佈滿裂紋撥出一個小洞再利用\"蛋膜\"連著一起在水中撥開，這樣比較不會戳出坑洞喔</p><p><img src=\"../uploaded/article_img/0-2-20190502-113750.png\" width=\"200\" height=\"150\"></p><p>6.光滑圓潤的蛋就煮好了</p><p><img src=\"../uploaded/article_img/0-2-20190502-113723.png\" width=\"200\" height=\"150\"></p><p>7.切的時候可由上往下 由後往前 切起來會比較漂亮</p><p><img src=\"../uploaded/article_img/0-2-20190502-113645.png\" width=\"200\" height=\"150\"></p>', '2019-05-02', NULL, NULL, 2, 0, NULL, 1),
(8, '你吃過、沒吃過的羅勒超多種，泰國羅勒、紫羅勒、打抛葉也是羅勒的一種！', '../uploaded/article_img/0-2-20190502-130556.png', '羅勒在世界各地是普及運用的香料植物，世界上光羅勒品種就有超過100種，而每種羅勒也隨使用區域與地方的飲食特性，被廣泛運用到各地料理中，造就菜餚的不同風味。除了大家最熟知的九層塔和西式料理常見的甜羅勒以外，其實還有泰國羅勒、聖羅勒、檸檬羅勒、紫羅勒等這些在各地也常見的羅勒種類，試著回憶看看，你曾吃過的料理裡，是否也出現過它們呢？', '<h2><strong><span style=\"color: rgb(255, 102, 0);\">東南亞常見羅勒：泰國羅勒（Thai Basil）</span></strong></h2><p style=\"text-align: center;\"><span style=\"color: rgb(117, 115, 115);\"><img src=\"http://localhost/cookforlife/uploaded/article_img/0-2-20190502-130107.png\" width=\"768\" height=\"576.0191607205229\"><br></span></p><p><span style=\"color: rgb(117, 115, 115);\">Photo：<a href=\"https://pixabay.com/zh/photos/罗勒-泰国罗勒-甜罗勒-食品-895217/\">pixabay.com</a></span></p><p>在東南亞的泰國、越南、柬埔寨等國，料理中不乏綠色羅勒葉片點綴，此時在料理中使用到的，或許就是泰國羅勒（Thai Basil）。泰國羅勒是東南亞常見的羅勒品種之一，外觀特色是<strong>紫色莖梗</strong>、<strong>葉片帶尖頭</strong>，<strong>香氣與味道較刺激、微辛辣</strong>，氣味和台灣九層塔相近，都屬於較濃郁的味道。</p><p>泰國羅勒<strong>和肉類料理最搭</strong>，舉凡豬肉、雞肉、牛肉，加上這些國家香料使用盛行，和各種香料、辣椒等一同使用，如泰國將泰國羅勒搭配在香料咖哩中，越式常見的涼拌或河粉料理等，在湯頭裡撒入幾瓣羅勒葉片，搭起來確實是完美組合。這凝聚了酸、辣、香飽滿的香氣，刺激人們在東南亞炎熱氣候中的食慾力。</p><p><span style=\"color: rgb(117, 115, 115);\">泰式海鮮湯中，多會加入泰國羅勒提香氣。</span></p><h2><strong><span style=\"color: rgb(255, 102, 0);\">東南亞常見羅勒：檸檬羅勒<strong>（Lemon Basil）</strong></span></strong></h2><p>泰國、印尼等東南亞地區皆會使用到，檸檬羅勒具有<strong>淡淡的檸檬氣味</strong>，泰式料理多搭配咖哩、湯類等使用，有時則和海鮮類等食材來料理煮成湯品，有幫助湯頭提味、<strong>海鮮去腥</strong>的優點。</p><p style=\"text-align: center;\"><img src=\"http://localhost/cookforlife/uploaded/article_img/0-2-20190502-130556.png\" width=\"609.9142837477963\" height=\"600\"><br></p><p><strong style=\"color: rgb(0, 0, 0); font-size: 22px;\"><span style=\"color: rgb(255, 102, 0);\">印度常見羅勒：聖羅勒（Holy Basil）</span></strong><br></p><p><strong style=\"color: rgb(0, 0, 0); font-size: 22px;\"><span style=\"color: rgb(255, 102, 0);\"><img src=\"http://localhost/cookforlife/uploaded/article_img/0-2-20190502-130333.png\" width=\"768\" height=\"432\"><br></span></strong></p><p><span style=\"color: rgb(117, 115, 115);\">Photo：<a href=\"https://pixabay.com/zh/photos/草药-印度草医学疗法-罗勒-圣-1615256/\">pixabay.com</a></span></p><p>聖羅勒（Holy Basil）對台灣來說較陌生，不過在印度等地卻非常熱門，聖羅勒在印度是傳統千年的藥用植物，被印度當地宗教視為<strong>神聖的植物和藥材</strong>。聖羅勒原產於印度，後來陸續出現在澳洲、中東、非洲等地，自古代作為藥用使用，以治療感冒、殺菌、皮膚過敏等為主，也有舒緩壓力、情緒的用途，含有豐富抗氧化力，當地很重視其<strong>對抗發炎</strong>的優勢。</p><p>聖羅勒主要用來製成精油或芳療、藥材，用於料理時，多將聖羅勒葉片以熱水沖泡後飲用茶水作食療保養。</p><h2><strong><span style=\"color: rgb(255, 102, 0);\">泰國常見羅勒：泰國聖羅勒（Thai holy basil）</span></strong></h2><p><img src=\"http://localhost/cookforlife/uploaded/article_img/0-2-20190502-130426.png\" width=\"781\" height=\"520.2507987220447\"><br></p><p><span style=\"color: rgb(117, 115, 115);\">Photo：<a href=\"https://www.freepik.com/premium-photo/thai-holy-basil-vegetable-garden-nature_2789903.htm\">freepik.com</a></span></p><p>泰國聖羅勒是聖羅勒的變種品種，更為人知的應該是「<strong>打拋葉</strong>」這個名稱！泰國聖羅勒的泰文音譯是kaphrao，也被唸作嘎抛、打抛。泰式特色料理打拋豬肉，即是使用打拋葉製作，打抛葉的<strong>葉片為橢圓型、具尖端</strong>，比九層塔的葉子再稍微細些、表面帶有少許絨毛，而香氣特殊，<strong>帶丁香的氣味、微辛辣</strong>。</p><p>一道打抛豬，搭上蒜頭、魚露、辣椒等，更多虧了打抛葉，香氣十足，讓人愛不釋口。在台灣來盤打拋豬，大多因為取材不便而會用九層塔來替換，但對泰式打拋豬而言其實不正統，一定要用「打抛葉」才行，香氣和台灣九層塔是不同的。除了這道料理，泰國也會將打拋葉用作<strong>醬料材料</strong>、<strong>酸辣涼拌</strong>等用途。</p>', '2019-05-02', NULL, NULL, 2, 0, NULL, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `collections`
--

CREATE TABLE `collections` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//收藏編號',
  `rec_id` mediumint(8) NOT NULL COMMENT '//食譜 id',
  `user_id` mediumint(8) NOT NULL COMMENT '//收藏人 id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `collections`
--

INSERT INTO `collections` (`id`, `rec_id`, `user_id`) VALUES
(9, 1, 3),
(10, 1, 2);

-- --------------------------------------------------------

--
-- 資料表結構 `comment`
--

CREATE TABLE `comment` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//留言編號',
  `rec_id` mediumint(8) UNSIGNED NOT NULL COMMENT '//食譜編號',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '//會員編號',
  `reply` mediumint(8) UNSIGNED DEFAULT NULL COMMENT '//被回覆者的id',
  `pid` mediumint(8) DEFAULT NULL COMMENT '//父留言，若 null 則為 子留言 ',
  `content` varchar(500) NOT NULL COMMENT '//留言內容',
  `date` datetime NOT NULL COMMENT '//留言時間',
  `modify_date` datetime DEFAULT NULL COMMENT '//修改時間',
  `timestamp` varchar(10) DEFAULT NULL COMMENT '//時間戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `comment`
--

INSERT INTO `comment` (`id`, `rec_id`, `user_id`, `reply`, `pid`, `content`, `date`, `modify_date`, `timestamp`) VALUES
(1, 1, 2, NULL, NULL, '請問大大，牛肉要煮到幾分熟才行呢?', '2019-03-05 15:00:00', NULL, NULL),
(2, 1, 3, NULL, NULL, '趕快來試作，老婆和小孩最喜歡牛肉料理了!感謝大大分享食譜。', '2019-03-05 22:03:00', NULL, NULL),
(3, 1, 1, 2, 1, '約 7 分熟，就可以了喔!', '2019-03-06 09:00:00', NULL, NULL),
(4, 1, 1, 3, 2, '不會喔!', '2019-03-05 23:00:00', NULL, NULL),
(5, 1, 2, NULL, NULL, '到此一遊!', '2019-03-14 11:23:46', NULL, '1552562626'),
(6, 1, 3, NULL, NULL, '你們好!', '2019-03-15 04:11:04', NULL, '1552623063'),
(7, 1, 3, 3, 6, 'Cc', '2019-03-15 04:11:19', NULL, '1552623079'),
(8, 1, 1, 3, 6, '你好!', '2019-03-15 12:40:41', NULL, '1552653640');

-- --------------------------------------------------------

--
-- 資料表結構 `privileges`
--

CREATE TABLE `privileges` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//權限編號',
  `name` varchar(10) NOT NULL COMMENT '//等級名',
  `info` varchar(50) NOT NULL COMMENT '//說明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `privileges`
--

INSERT INTO `privileges` (`id`, `name`, `info`) VALUES
(1, '一般會員', '不可訪問後台，無權使用付費功能。可編輯、刪除、發布、食譜與試作'),
(2, '付費會員', '不可訪問後台，可使用前台付費功能。可編輯、刪除、發布、食譜與試作'),
(3, '特約作者', '專欄文檔發布、編輯，沒有刪除。可編輯、刪除、發布、食譜與試作'),
(4, '小編', '發布、編輯、刪除文檔。'),
(5, '普通管理員', '除了無法編修會員與管理員外，其他都可以辦到。'),
(6, '超級管理員', '除了無法編修自己帳號外，其他都可以辦到。超級管理員至少一位!');

-- --------------------------------------------------------

--
-- 資料表結構 `recipes`
--

CREATE TABLE `recipes` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//食譜 id',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '//會員編號',
  `follow` mediumint(8) UNSIGNED DEFAULT NULL COMMENT '//若這篇是跟著做則存放原創食譜的id',
  `follow_people` mediumint(8) UNSIGNED DEFAULT '0' COMMENT '//是原創食譜則存放跟著做的人數',
  `title` varchar(20) NOT NULL COMMENT '//食譜標題',
  `summary` varchar(500) NOT NULL COMMENT '//心得',
  `finish_img` varchar(100) NOT NULL COMMENT '//成品照路徑',
  `step_xml` varchar(100) NOT NULL COMMENT '//調理方式 .xml 檔 路徑',
  `date` date NOT NULL COMMENT '//新增時間',
  `modify_date` datetime DEFAULT NULL COMMENT '//修改時間',
  `modify_user` mediumint(8) NOT NULL COMMENT '//修改者 id',
  `state` tinyint(4) UNSIGNED DEFAULT '0' COMMENT '//發布狀態  1 草稿 0 已發布',
  `tag_xml` varchar(100) DEFAULT NULL COMMENT '//標籤連結 .xml 路徑',
  `collected` mediumint(8) NOT NULL COMMENT '//被收藏數',
  `shared` mediumint(8) NOT NULL COMMENT '//被分享數'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `follow`, `follow_people`, `title`, `summary`, `finish_img`, `step_xml`, `date`, `modify_date`, `modify_user`, `state`, `tag_xml`, `collected`, `shared`) VALUES
(1, 1, NULL, 2, '蒜香紐約客骰子牛', '吃膩了煎牛排嗎！ \n滿滿的蒜香味～ \n甜鹹甜鹹的醬汁好下飯！ \n大人小孩超喜歡的，一上桌秒殺。', 'uploaded/pics/0-2-20190502-105307.png', 'recipe_detail/2-20190502-105307.xml', '2019-03-02', '2019-05-02 10:53:07', 2, 0, NULL, 2, 2, 0),
(2, 2, 1, NULL, '蒜香紐約客骰子牛', '換成雞肉也很棒喔!大家可以試試看。 ^_^>', 'uploaded/pics/0-20190327-125410.png', '', '2019-03-05', '2019-03-27 12:54:10', 0, 0, NULL, 0, 0, 0),
(3, 3, 1, NULL, '蒜香紐約客骰子牛', '真的比夜市賣的還好吃呢！^.^', 'uploaded/pics/0-20190302-103736.png', '', '2019-03-27', NULL, 0, 0, NULL, 0, -1, 0),
(5, 2, NULL, 0, '和風炸黃金豆腐', '黃金豆腐外皮香酥，內軟又嫩，一口咬下充滿豐富的口感~\n想吃不用到餐廳囉！自己在家做看看，是新手做炸物的入門首選，不易失敗又好上手喔！快來刷一波成就感吧~????', 'uploaded/pics/0-2-20190502-132241.png', 'recipe_detail/1-20190503-114443.xml', '2019-05-02', '2019-05-03 11:44:43', 1, 0, NULL, 0, 0, 0),
(6, 1, NULL, 0, '蔥爆雞丁', '川菜中少不了辣豆瓣醬及花椒的元素，螞蟻上樹迷人的地方就是辣中帶香讓人一口接一口，炎熱的夏天小廚分享這道給您食慾大開的料理，做法其實不難，當中利用史雲生清雞湯取代清水讓冬粉更加入味，希望您會喜歡。', 'uploaded/pics/0-1-20190503-114340.png', 'recipe_detail/1-20190503-114340.xml', '2019-05-03', '2019-05-03 11:43:40', 1, 0, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `recommendation`
--

CREATE TABLE `recommendation` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//推薦廣告編號',
  `title` varchar(25) NOT NULL COMMENT '//標題',
  `url` varchar(300) NOT NULL COMMENT '//廣告連結',
  `date` datetime NOT NULL COMMENT '//日期',
  `modify_date` datetime NOT NULL COMMENT '//修改日期',
  `state` tinyint(1) NOT NULL COMMENT '// 0發布 1草稿'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `web_user`
--

CREATE TABLE `web_user` (
  `id` mediumint(8) UNSIGNED PRIMARY KEY NOT NULL COMMENT '//會員編號',
  `ckey` varchar(40) NOT NULL COMMENT '//會員驗證序號',
  `user` varchar(12) NOT NULL COMMENT '//會員帳號',
  `nickname` varchar(10) DEFAULT NULL COMMENT '//暱稱',
  `pass` char(40) NOT NULL COMMENT '//會員密碼',
  `photo` varchar(100) NOT NULL COMMENT '//頭像路徑',
  `email` varchar(30) DEFAULT NULL COMMENT '//email',
  `intro` varchar(500) DEFAULT NULL COMMENT '//自介',
  `phone` varchar(10) DEFAULT NULL COMMENT '//手機',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '//等級',
  `login_count` smallint(5) NOT NULL COMMENT '//登入次數',
  `last_ip` varchar(20) NOT NULL DEFAULT '000.000.000.000' COMMENT '//最近登入IP',
  `last_time` datetime NOT NULL COMMENT '//最近登入時間',
  `reg_date` datetime NOT NULL COMMENT '//註冊時間',
  `modify_date` datetime DEFAULT NULL COMMENT '//修改時間',
  `modify_user` mediumint(8) DEFAULT NULL COMMENT '//修改者的會員 id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '//審核 0未認證 1已認證 2凍結 3限制',
  `follow` mediumint(8) UNSIGNED DEFAULT NULL COMMENT '//關注人數',
  `fans` mediumint(8) UNSIGNED NOT NULL COMMENT '//粉絲數',
  `collections` mediumint(8) UNSIGNED NOT NULL COMMENT '//食譜收藏數',
  `recipes` mediumint(8) UNSIGNED NOT NULL COMMENT '//食譜發布數'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `web_user`
--

INSERT INTO `web_user` (`id`, `ckey`, `user`, `nickname`, `pass`, `photo`, `email`, `intro`, `phone`, `level`, `login_count`, `last_ip`, `last_time`, `reg_date`, `modify_date`, `modify_user`, `state`, `follow`, `fans`, `collections`, `recipes`) VALUES
(1, 'f3f958a9b2f4921b2c78ba125001ce30d5939746', 'coldforest', '寒森肉123', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'uploaded/self_photo/0-1-20190422-094131.png', 'coldforest@gmail.com', '<p>大家好，我是寒森肉。</p>', '0906891273', 6, 12, '::1', '2019-05-02 13:35:24', '2018-10-29 16:13:59', '2019-04-22 09:41:31', 1, 0, NULL, 0, 0, 1),
(2, '2c4836d54c80e296cf6113573643ec841bc4b78f', 'bear', '熊熊', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'uploaded/self_photo/22.gif', NULL, NULL, NULL, 5, 12, '::1', '2019-04-30 10:24:43', '2018-10-29 16:19:46', NULL, NULL, 0, NULL, 0, 2, 0),
(3, '1ad06b9fac22147de39a0cc46158e345515d3637', 'luffy', '蒙奇D.魯夫', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'uploaded/self_photo/22.gif', 'xiangrui9988@gmail.com', '<p><span style=\"\">蒙奇D.魯夫，不煮飯的，只負責吃肉。</span><br></p>', '0906613590', 1, 62, '::1', '2019-04-27 12:03:00', '2018-10-29 16:20:00', '2019-04-18 16:46:43', 3, 0, NULL, 0, 0, 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `recommendation`
--
ALTER TABLE `recommendation`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `web_user`
--
ALTER TABLE `web_user`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `article`
--
ALTER TABLE `article`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//文章編號', AUTO_INCREMENT=9;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `collections`
--
ALTER TABLE `collections`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '//收藏編號', AUTO_INCREMENT=11;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `comment`
--
ALTER TABLE `comment`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//留言編號', AUTO_INCREMENT=9;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '//權限編號', AUTO_INCREMENT=7;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '//食譜 id', AUTO_INCREMENT=7;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `recommendation`
--
ALTER TABLE `recommendation`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//推薦廣告編號';

--
-- 使用資料表自動增長(AUTO_INCREMENT) `web_user`
--
ALTER TABLE `web_user`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//會員編號', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
