<?php 
error_reporting(0);
ini_set('max_execution_time','0');
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');
$name='admin';//暂时没有通过登录获取用户名

$startdate=strtotime (date("y-m-d h:i:s"));
$sql="select img,isbn from book where img='' ";
$result=mysql_query($sql) or die(mysql_error());
$num_y=0;
$num_x=0;
while($row = mysql_fetch_array($result)){
	$isbn=$row['isbn'];
	$book = get_book_data($isbn); 
	$title = $book->gettitle();
	$author=$book->getAuthor();
	$publisher=$book->getpublisher();
	$pubyear=$book->getpubyer();
	$price=$book->getprice();
	$pages=$book->getpages();
	$abstract=$book->getabstract();
	$authorinfor=$book->getauthorinfo();
	$catalog=$book->getcatalog();
	$cover=$book->getcover();
	$source=$book->getsource();
	if(!isset($title) or !isset($author) or $author=='') {
		$message = $message.$isbn.' ';
		//echo "没找到";
		$num_x++;
		
	}else{
		
/* 		echo $title."!".$author."作者<br>";
		echo $publisher."<br>";
		echo $pubyear."<br>";
		echo $price."<br>";
		echo $pages."<br>";
		echo $abstract."<br>";
		echo $authorinfor."<br>";
		echo $catalog."<br>";
		echo $source."<br>";
		echo "书封：".$cover."<br>"; */
		//echo $isbn."...";
		
		//这里准备构造update的sql语句
		$sql_update="update book set b_name='$title',b_publisher='$publisher',b_author='$author',b_price='$price',b_pages='$pages',b_pubyear='$pubyear',b_abstract='$abstract',b_authorinfo='$authorinfo',b_catalog='$catalog',img='$cover',get_operator='$name',get_source='$source',get_date=now() where isbn='$isbn'";
		//echo $sql_update;
		$result_update=mysql_query($sql_update) or die(mysql_error());
		$num_y++;
	}
	
}
$enddate=strtotime (date("y-m-d h:i:s"));
$minute=floor((strtotime($enddate)-strtotime($startdate))%86400/60);
$num_xy=$num_x+$num_y;
echo "本次查询运行了".$minute."分钟。<br>共进行了".$num_xy."次查询。<br>检索到了".$num_y."本书。<br>未查询到的有：".$message;
	
	

	
	
//下载文件的函数
function dlfile($file_url, $save_to)
{
	$content = file_get_contents($file_url);
	file_put_contents($save_to, $content);
}




function get_book_data($isbn) {
	
	//根据isbn查询中国图书网，得到检索的第一个结果页
	$url='http://www.bookschina.com/book_find2/?stp='.$isbn.'&sCate=2';
	$ch = curl_init(); 
	//curl_setopt($ch, CURLOPT_URL, "http://www.bookschina.com/book_find2/?stp=9787544225632&sCate=2"); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	//echo $result;
	curl_close($ch); 
	
	 //取得指定URL的內容，並储存至text
	//$text=file_get_contents($url);
	 
	//去除換行及空白字元（序列化內容才需使用）
	$text=str_replace(array("\r","\n","\t","\s"), '', $result); 
	

	
	
	//从中国图书馆第一个结果页中的cover信息
	//取出div标签且id為cover的內容，並储存至阵列match
	preg_match('/<div[^>]*class="cover"[^>]*>(.*?) <\/div>/si',$text,$match);
	//打印match[0]
	$str_cover= $match['0'];
	
	//获取了div cover之后，从中找到第一个链接地址。
		$pattern = '/a href="(.*?)"/';
		preg_match($pattern, $str_cover, $match);
		$url2 =  trim($match['1']);//url2就是详情页的相对地址

		$pattern = '/title="(.*?)"/';
		preg_match($pattern, $str_cover, $match);
		$title =  trim($match['1']);//title是书名 不过是gb2312编码
		$title =iconv('GB2312', 'UTF-8//IGNORE', $title);//转码
		//echo $title;
		//exit;
		
	//获取otherInfor的内容	
	$pattern='/<div class=\"otherInfor\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_otherInfor= $match['0'];
	//print_r($match);
	
	//$pattern ='/<span.*>(.*)<\/span>/isU';
	
	//获取otherInfor的两个a标签的内容 作者、出版社
	$pattern = '/<a.*?>(.*?)<\/a>/is';
	preg_match_all($pattern, $str_otherInfor ,$match);
	//print_r($match);
	$author=iconv('GB2312', 'UTF-8//IGNORE',$match[1][0]);
	$publisher=iconv('GB2312', 'UTF-8//IGNORE',$match[1][1]);
	//echo $publisher;
	
	if(!isset($author) or $author==''){//这里已经可以判断出在中国图书网并没有找到这个书目信息。
		//现在看看能否继续在这里追加获取豆瓣的数据。
		
		$url = "https://api.douban.com/v2/book/isbn/:".$isbn;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		$result = curl_exec($curl);
		curl_close($curl);

		$book_array = (array) json_decode($result, true);
		
		$book_title = $book_array["title"];
        $book_author = $book_array["author"][0];

		//echo "!!!!!!!!".$book_title."<br>";
		
		  if(empty($book_title) or $book_title=='' or !isset($book_author) or $book_author==''){
			  //豆瓣也没找到。
			 // echo "!!!!<br><br>!!!!!";

		  }
		  else{
			
			$book_publisher = $book_array["publisher"];
			$book_pubyer = $book_array["pubdate"];
			$book_pages = $book_array["pages"];
			$book_price = $book_array["price"];
			$book_abstract = $book_array["summary"]; 
	  
			  //下载书封
			$save_to='bookcover/'.$isbn.'.jpg';
			if(file_exists($save_to)){
				//文件已经存在
				//echo "书封原来就存在，<img src='$save_to' />";
			}
			else{

				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );//跳过ssl验证
				curl_setopt ( $ch, CURLOPT_URL, $book_array["image"] );
				ob_start ();
				curl_exec ( $ch );
				$return_content = ob_get_contents ();
				ob_end_clean ();
				
				$fp= fopen($save_to,"a");
				fwrite($fp,$return_content);
				//echo "下载完成，<img src='$save_to' />";
			}
		  }
		  $book_cover=$save_to;
		  $book_source="豆瓣读书";
		  sleep(5);
		
	
        $book = new Book($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source);
        return $book;
	}
	
	
	//获取otherInfor的一个span标签的内容；出版时间
	$pattern ='/<span.*>(.*)<\/span>/isU';
	preg_match($pattern, $str_otherInfor ,$match);
	$pubyear=substr($match[1],0,10);
	//echo $pubyear;

	
	//获取priceWrap的内容	
	$pattern='/<div class=\"priceWrap\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_priceWrap= $match['0'];
	//获取priceWrap的del标签的内容
	$pattern = '/<del.*?>(.*?)<\/del>/is';
	preg_match($pattern, $str_priceWrap ,$match);
	$price=substr($match[1],5);
	//echo $price;
	
		//下载书封
	$save_to='bookcover/'.$isbn.'.jpg';
	if(file_exists($save_to)){
		//文件已经存在
		//echo "书封原来就存在，<img src='$save_to' />";
	}
	else{
		$pattern = '/data-original="(.*?)"/';
		preg_match($pattern, $text, $match);
		$fvalue =  trim($match['1']);
		//得到书封的链接地址
		dlfile($fvalue,$save_to);
		//echo "下载完成，<img src='$save_to' />";
	}

	//根据url2查询详情页面
	$url='http://www.bookschina.com/'.$url2;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	//echo $result;
	curl_close($ch); 
	$text=str_replace(array("\r","\n","\t","\s"), '', $result);
	
	//获取otherInfor的内容	
	$pattern='/<div class=\"otherInfor\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_otherInfor= $match['0'];
	//获取otherInfor的i标签的内容——页码信息
	$pattern = '/<i.*?>(.*?)<\/i>/is';
	preg_match($pattern, $str_otherInfor ,$match);	
	$pages=$match[1];
	
	//获取brief的内容	
	$pattern='/<div class=\"brief\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_brief= $match['0'];
	//获取brief的p标签的内容——简介
	$pattern = '/<p.*?>(.*?)<\/p>/is';
	preg_match($pattern, $str_brief ,$match);	
	$abstract=iconv('GB2312', 'UTF-8//IGNORE',$match[1]);
	
	//获取catalogSwitch的内容	——目录
	$pattern='/<div id=\"catalogSwitch\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$catalog= iconv('GB2312', 'UTF-8//IGNORE',$match[0]);
	$catalog=str_replace("'",'',$catalog);//有的目录中带有' 会造成无法更新数据库的错误。这里进行删除所有的'

	
	//获取作者简介的内容	 excerpt里的p
	$pattern='/<div class=\"excerpt\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_excerpt= $match['0'];
	//获取excerpt的p标签的内容——简介
	$pattern = '/<p.*?>(.*?)<\/p>/is';
	preg_match($pattern, $str_excerpt ,$match);	
	$authorinfo=iconv('GB2312', 'UTF-8//IGNORE',$match[1]);
	
	//增加一个来源的变量
	$source='中国图书网';
    
    if(!empty($title)) {
        $book_title = $title;
        $book_author = $author;
        $book_cover = $save_to;
        $book_publisher = $publisher;
        $book_pubyer = $pubyear;
        $book_price = $price;
        $book_pages = $pages;
        $book_abstract = $abstract;
        $book_authorinfo = $authorinfo;        
        $book_catalog = $catalog;
		$book_source=$source;
        $book = new Book($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source);
        return $book;
    }
    
}
class Book {    
    private $book_title;
    private $book_author;
    private $book_cover;
    private $book_publisher;
    private $book_pubyer;
    private $book_price;
    private $book_pages;
    private $book_abstract;
    private $book_authorinfo;
    private $book_catalog;
    private $book_source;

    public function __construct($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract,$book_authorinfo,$book_catalog,$book_source) {
        $this->book_title = $book_title;
        $this->book_author = $book_author;
        $this->book_cover = $book_cover;
        $this->book_publisher = $book_publisher;
        $this->book_pubyer = $book_pubyer;
        $this->book_price = $book_price;
        $this->book_pages = $book_pages;
        $this->book_abstract = $book_abstract;
        $this->book_authorinfo = $book_authorinfo;
        $this->book_catalog = $book_catalog;
        $this->book_source = $book_source;
    }
    public function getTitle() {
        return $this->book_title;
    }
    public function getAuthor() {
        return $this->book_author;
    }
    public function getCover() {
        return $this->book_cover;
    }
    public function getpublisher() {
        return $this->book_publisher;
    }    
    public function getpubyer() {
        return $this->book_pubyer;
    }    
    public function getprice() {
        return $this->book_price;
    }    
    public function getpages() {
        return $this->book_pages;
    }    
    public function getabstract() {
        return $this->book_abstract;
    }    
    public function getauthorinfo() {
        return $this->book_authorinfo;
    }    
    public function getcatalog() {
        return $this->book_catalog;
    }    
          
    public function getsource() {
        return $this->book_source;
    }    
      
}



	

	

?> 