<?php 
include_once('inc/common_function.php');
require_once 'inc/Mobile_Detect.php';
$detect = new Mobile_Detect;
 
session_start();
$_SESSION['mobile']='n';
if ( $detect->isMobile() ) {
 	$_SESSION['mobile']='y';
 	echo '<script>'."location='m_index.php'".'</script>';
}


include_once("header.php");
include_once('inc/conn.php');
?>
<link rel="stylesheet" href="demo/css/styles.css" >
<div class="container-fluid">
	<div class="page-header" style="display: none">
		<h1>我是首页</h1>
	</div>
	<div style="display: none">
		<ul>
			<li><a href="opac.php">书目检索</a></li>
			<li><a href="bookrec.php">图书荐购</a></li>
			<li><a href="bookrec_list.php">荐购列表</a></li>
			<li><a href="yuyue_list.php">座位预约</a></li>
			<li><a href="help.php">预约说明</a></li>
			<li><a href="book_manager.php">图书借还</a></li>
			<li><a href="store.php">积分商城</a></li>
			<li><a href="admin_regscore.php">主管管理</a></li>
			<li><a href="test_change_role.php">角色变身</a></li>
		</ul>
	</div>
	<div style="height: 840px;">
        <div id="base" class="">

            <!-- Unnamed (组合) -->
            <div id="u16" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <a href="opac.php">
                    <div id="u17" class="ax_default ellipse">
                        <img id="u17_img" class="img " src="images/sy/u17.png"/>
                        <!-- Unnamed () -->
                        <div id="u18" class="text" style="display: none; visibility: hidden">
                            <p><span></span></p>
                        </div>
                    </div>
                </a>
                <!-- Unnamed (图片) -->
                <div id="u19" class="ax_default image">
                    <a href="opac.php"><img id="u19_img" class="img " src="images/sy/u19.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u20" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u21" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u22" class="ax_default ellipse">
                    <img id="u22_img" class="img " src="images/sy/u22.png"/>
                    <!-- Unnamed () -->
                    <div id="u23" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u24" class="ax_default image">
                    <a href="bookrec_list.php"><img id="u24_img" class="img " src="images/sy/u24.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u25" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u26" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u27" class="ax_default ellipse">
                    <img id="u27_img" class="img " src="images/sy/u27.png"/>
                    <!-- Unnamed () -->
                    <div id="u28" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u29" class="ax_default image">
                    <a href="yuyue_list.php">
                        <img id="u29_img" class="img " src="images/sy/u29.png"/>
                    </a>
                    <!-- Unnamed () -->
                    <div id="u30" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u31" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u32" class="ax_default ellipse">
                    <img id="u32_img" class="img " src="images/sy/u32.png"/>
                    <!-- Unnamed () -->
                    <div id="u33" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u34" class="ax_default image">
                    <a href="help.php">
                    <img id="u34_img" class="img " src="images/sy/u34.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u35" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u36" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u37" class="ax_default ellipse">
                    <img id="u37_img" class="img " src="images/sy/u37.png"/>
                    <!-- Unnamed () -->
                    <div id="u38" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u39" class="ax_default image">
                    <a href="book_manager.php"><img id="u39_img" class="img " src="images/sy/u39.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u40" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u41" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u42" class="ax_default ellipse">
                    <img id="u42_img" class="img " src="images/sy/u42.png"/>
                    <!-- Unnamed () -->
                    <div id="u43" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u44" class="ax_default image">
                    <a href="store.php"><img id="u44_img" class="img " src="images/sy/u44.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u45" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u46" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u47" class="ax_default ellipse">
                    <img id="u47_img" class="img " src="images/sy/u47.png"/>
                    <!-- Unnamed () -->
                    <div id="u48" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u49" class="ax_default image">
                    <a href="#"><img id="u49_img" class="img " src="images/sy/u49.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u50" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u51" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u52" class="ax_default ellipse">
                    <img id="u52_img" class="img " src="images/sy/u52.png"/>
                    <!-- Unnamed () -->
                    <div id="u53" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u54" class="ax_default image">
                    <img id="u54_img" class="img " src="images/sy/u54.png"/>
                    <!-- Unnamed () -->
                    <div id="u55" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u56" class="ax_default">

                <!-- Unnamed (椭圆形) -->


                <!-- Unnamed (图片) -->

            </div>

            <!-- Unnamed (矩形) -->
            <div id="u61" class="ax_default _一级标题">
                <a href="opac.php">
                    <div id="u61_div" class=""></div>
                    <!-- Unnamed () -->
                    <div id="u62" class="text" style="visibility: visible;">
                        <p><span>书目检索</span></p>
                    </div>
                </a>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u63" class="ax_default _一级标题">
                <div id="u63_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u64" class="text" style="visibility: visible;">
                    <a href="bookrec_list.php"><p><span>荐购列表</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u65" class="ax_default _一级标题">
                <div id="u65_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u66" class="text" style="visibility: visible;">
                    <a href="yuyue_list.php"><p><span>预约座位</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u67" class="ax_default _一级标题">
                <div id="u67_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u68" class="text" style="visibility: visible;">
                    <a href="help.php"><p><span>预约说明</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u69" class="ax_default _一级标题">
                <div id="u69_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u70" class="text" style="visibility: visible;">
                    <a href="book_manager.php"><p><span>图书借还</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->


            <!-- Unnamed (矩形) -->
            <div id="u73" class="ax_default _一级标题">
                <div id="u73_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u74" class="text" style="visibility: visible;">
                    <a href="store.php"><p><span>积分商城</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u75" class="ax_default _一级标题">
                <div id="u75_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u76" class="text" style="visibility: visible;">
                    <a href="#"><p><span>主管管理</span></p></a>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u77" class="ax_default _一级标题">
                <div id="u77_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u78" class="text" style="visibility: visible;">
                    <a href="#"><p><span>测试变身</span></p></a>
                </div>
            </div>

            <!-- Unnamed (动态面板) -->
            <div id="u79" class="ax_default ax_default_hidden" style="display: none; visibility: hidden">
                <div id="u79_state0" class="panel_state" data-label="State1">
                    <div id="u79_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u80" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u81" class="ax_default ellipse">
                                <img id="u81_img" class="img " src="images/sy/u81.png"/>
                                <!-- Unnamed () -->
                                <div id="u82" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u83" class="ax_default image">
                                <img id="u83_img" class="img " src="images/sy/u19.png"/>
                                <!-- Unnamed () -->
                                <div id="u84" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u85" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u85_state0" class="panel_state" data-label="State1">
                    <div id="u85_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u86" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u87" class="ax_default ellipse">
                                <img id="u87_img" class="img " src="images/sy/u87.png"/>
                                <!-- Unnamed () -->
                                <div id="u88" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u89" class="ax_default image">
                                <img id="u89_img" class="img " src="images/sy/u24.png"/>
                                <!-- Unnamed () -->
                                <div id="u90" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u91" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u91_state0" class="panel_state" data-label="State1">
                    <div id="u91_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u92" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u93" class="ax_default ellipse">
                                <img id="u93_img" class="img " src="images/sy/u93.png"/>
                                <!-- Unnamed () -->
                                <div id="u94" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u95" class="ax_default image">
                                <img id="u95_img" class="img " src="images/sy/u29.png"/>
                                <!-- Unnamed () -->
                                <div id="u96" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u97" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u97_state0" class="panel_state" data-label="State1">
                    <div id="u97_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u98" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u99" class="ax_default ellipse">
                                <img id="u99_img" class="img " src="images/sy/u99.png"/>
                                <!-- Unnamed () -->
                                <div id="u100" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u101" class="ax_default image">
                                <img id="u101_img" class="img " src="images/sy/u34.png"/>
                                <!-- Unnamed () -->
                                <div id="u102" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u103" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u103_state0" class="panel_state" data-label="State1">
                    <div id="u103_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u104" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u105" class="ax_default ellipse">
                                <img id="u105_img" class="img " src="images/sy/u105.png"/>
                                <!-- Unnamed () -->
                                <div id="u106" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u107" class="ax_default image">
                                <img id="u107_img" class="img " src="images/sy/u39.png"/>
                                <!-- Unnamed () -->
                                <div id="u108" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u109" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u109_state0" class="panel_state" data-label="State1">
                    <div id="u109_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u110" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u111" class="ax_default ellipse">
                                <img id="u111_img" class="img " src="images/sy/u111.png"/>
                                <!-- Unnamed () -->
                                <div id="u112" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u113" class="ax_default image">
                                <img id="u113_img" class="img " src="images/sy/u44.png"/>
                                <!-- Unnamed () -->
                                <div id="u114" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u115" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u115_state0" class="panel_state" data-label="State1">
                    <div id="u115_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u116" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u117" class="ax_default ellipse">
                                <img id="u117_img" class="img " src="images/sy/u117.png"/>
                                <!-- Unnamed () -->
                                <div id="u118" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u119" class="ax_default image">
                                <img id="u119_img" class="img " src="images/sy/u49.png"/>
                                <!-- Unnamed () -->
                                <div id="u120" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u121" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u121_state0" class="panel_state" data-label="State1">
                    <div id="u121_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u122" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u123" class="ax_default ellipse">
                                <img id="u123_img" class="img " src="images/sy/u123.png"/>
                                <!-- Unnamed () -->
                                <div id="u124" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u125" class="ax_default image">
                                <img id="u125_img" class="img " src="images/sy/u54.png"/>
                                <!-- Unnamed () -->
                                <div id="u126" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1111 (动态面板) -->
            <div id="u127" class="ax_default ax_default_hidden" data-label="1111" style="display: none; visibility: hidden">
                <div id="u127_state0" class="panel_state" data-label="State1">
                    <div id="u127_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u128" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u129" class="ax_default ellipse">
                                <img id="u129_img" class="img " src="images/sy/u129.png"/>
                                <!-- Unnamed () -->
                                <div id="u130" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u131" class="ax_default image">
                                <img id="u131_img" class="img " src="images/sy/u59.png"/>
                                <!-- Unnamed () -->
                                <div id="u132" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unnamed (组合) -->
            <div id="u133" class="ax_default">

                <!-- Unnamed (椭圆形) -->
                <div id="u134" class="ax_default ellipse">
                    <img id="u134_img" class="img " src="images/sy/u134.png"/>
                    <!-- Unnamed () -->
                    <div id="u135" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>

                <!-- Unnamed (图片) -->
                <div id="u136" class="ax_default image">
                    <a href="attend.php"><img id="u136_img" class="img " src="images/sy/u136.png"/></a>
                    <!-- Unnamed () -->
                    <div id="u137" class="text" style="display: none; visibility: hidden">
                        <p><span></span></p>
                    </div>
                </div>
            </div>

            <!-- Unnamed (矩形) -->
            <div id="u138" class="ax_default _一级标题">
                <div id="u138_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u139" class="text" style="visibility: visible;">
                    <a href="attend.php"><p><span>签到</span></p></a>
                </div>
            </div>

            <!-- 2222 (动态面板) -->
            <div id="u140" class="ax_default ax_default_hidden" data-label="2222" style="display: none; visibility: hidden">
                <div id="u140_state0" class="panel_state" data-label="State1">
                    <div id="u140_state0_content" class="panel_state_content">

                        <!-- Unnamed (组合) -->
                        <div id="u141" class="ax_default">

                            <!-- Unnamed (椭圆形) -->
                            <div id="u142" class="ax_default ellipse">
                                <img id="u142_img" class="img " src="images/sy/u142.png"/>
                                <!-- Unnamed () -->
                                <div id="u143" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>

                            <!-- Unnamed (图片) -->
                            <div id="u144" class="ax_default image">
                                <img id="u144_img" class="img " src="images/sy/u136.png"/>
                                <!-- Unnamed () -->
                                <div id="u145" class="text" style="display: none; visibility: hidden">
                                    <p><span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>

<?php 
include_once("footer.php");
?>


  </body>
</html>
