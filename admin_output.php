<?php
// session_start();
// $islogin=$_SESSION['islogin'];
// if($islogin!=3 and $islogin!=9){
// 	echo '<script>';
// 	echo "location='index.php'";
// 	echo '</script>';
// }


// 输出Excel文件头，可把user.csv换成你要的文件名
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="预约记录表.csv"');
// header('Content-Disposition: attachment;filename="书目表.csv"');
header('Cache-Control: max-age=0');

include('inc/conn.php');
// 从数据库中获取数据，为了节省内存，不要把数据一次性读到内存，从句柄中一行一行读即可
    //$sql = "select b.name,b.isbn,a.findcode,a.bookcode,b.category,b.price,b.author,b.num,b.resntum from book a,bookinfo b where a.bookinfo_id=b.id ";
    // $sql = "select b.name,b.isbn,a.findcode,a.bookcode,b.category,b.price,b.author,b.num,b.restnum,c.name edit_name,a.donor_name from book a,bookinfo b, user c where a.bookinfo_id=b.id and b.edit_id=c.id ";
	// $sql = "select b.name,b.isbn,a.findcode,a.bookcode,b.category,b.price,b.author,b.num,b.restnum,c.name edit_name,d.name ruku_name,a.donor_name from book a,bookinfo b, user c,user d where a.bookinfo_id=b.id and b.edit_id=c.id and b.ruku_id=d.id ";
	$sql = "select a.s_id 座位编号,a.yuyue_time 预约时间,a.queren_time 现场确认时间, a.tuichu_time 退出时间, a.bzyuyue 预约说明,a.bztuichu 退出说明, b.name 用户姓名, b.username,b.cengci from booklist a, user b where a.u_id=b.id ";

$stmt = mysql_query($sql);
 
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
$fp = fopen('php://output', 'a');
 
// 输出Excel列名信息
// $head = array('书名', 'ISBN', '索书号','图书编号','分类号：','价格','作者','副本数','可借册数','编目员','捐赠人');
// $head = array('书名', 'ISBN', '索书号','图书编号','分类号：','价格','作者','副本数','可借册数','更新人员','入库员','捐赠人');
$head = array('座位编号', '预约时间', '现场确认时间','退出时间','预约说明','退出说明','用户姓名','学号','层次');
/* foreach ($head as $i => $v) {
    // CSV的Excel支持GBK编码，一定要转换，否则乱码
    $head[$i] = iconv('utf-8', 'gb2312', $v);
} */
 
// 将数据通过fputcsv写到文件句柄
fputcsv($fp, $head);
 
// 计数器
$cnt = 0;
// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
$limit = 100000;
 
// 逐行取出数据，不浪费内存
while ($row = mysql_fetch_array($stmt,MYSQL_ASSOC)) {
 
    $cnt ++;
    if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
        ob_flush();
        flush();
        $cnt = 0;
    }
 
    foreach ($row as $i => $v) {
        //$row[$i] = iconv('utf-8', 'GBK//IGNORE', $v);
        //$row[$i]=$row[$i].'';
    }
    fputcsv($fp, $row); 
}


?>