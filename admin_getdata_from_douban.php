<?php
    $book = get_book_data('9787510704734'); 
    $title = $book->getTitle();
	$author=$book->getAuthor();
	$info=$book->getBookInfo();
	$cover=$book->getcover();
	$publisher=$book->getpublisher();
	$pubdate=$book->getpubdate();
	$pages=$book->getpages();
	$price=$book->getprice();
    if(!isset($title)) {
        $message = "此书未找到.";
    }else{
        echo $title.$author."<br>";
		echo $publisher."<br>";
		echo $pubdate."<br>";
		echo $pages."<br>";
		echo $price."<br>";
		echo $info."<br>";
		echo $cover."<br>";
    }
	

// 取Book信息
function get_book_data($isbn) {
    $url = "https://api.douban.com/v2/book/isbn/:".$isbn;
        
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    $result = curl_exec($curl);
    curl_close($curl);
    
    $book_array = (array) json_decode($result, true);
    
    if(!empty($book_array["title"])) {
        $book_title = $book_array["title"];
        $book_author = $book_array["author"][0];
        $book_cover = $book_array["image"];
        $book_publisher = $book_array["publisher"];
        $book_pubdate = $book_array["pubdate"];
        $book_pages = $book_array["pages"];
        $book_price = $book_array["price"];
        $book_info = $book_array["summary"];        
        $book = new Book($book_title, $book_author, $book_cover, $book_publisher,$book_pubdate,$book_pages,$book_price, $book_info);
        return $book;
    }
    
}
    
// 这里只取几个主要信息
class Book {    
    private $book_title;
    private $book_author;
    private $book_cover;
    private $book_info;
    private $book_publisher;
    private $book_pubdate;
    private $book_pages;
    private $book_price;

    public function __construct($book_title, $book_author, $book_cover, $book_publisher,$book_pubdate,$book_pages,$book_price, $book_info) {
        $this->book_title = $book_title;
        $this->book_author = $book_author;
        $this->book_cover = $book_cover;
        $this->book_publisher = $book_publisher;
        $this->book_info = $book_info;
        $this->book_pubdate = $book_pubdate;
        $this->book_pages = $book_pages;
        $this->book_price = $book_price;
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
    public function getpubdate() {
        return $this->book_pubdate;
    } 
    public function getpages() {
        return $this->book_pages;
    } 
    public function getprice() {
        return $this->book_price;
    }    
    public function getBookInfo() {
        return $this->book_info;    
    }    
}
?>