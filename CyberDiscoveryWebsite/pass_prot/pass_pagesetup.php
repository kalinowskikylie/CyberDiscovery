<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nasa Threads: Welcome Teachers!</title>
<style type="text/css"> 
<!-- 
.twoColLiqRtHdr #container {
	width: 80%;  /* this will create a container 80% of the browser width */
	background: #FFFFFF;
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	border: 0px solid #000000;
	text-align: left; /* this overrides the text-align: center on the body element. */
	page-break-before: always;
	background-color: #FFF;
} 
.twoColLiqRtHdr #header {
	background: #ffffff;
	padding: 0 .0px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
} 
.twoColLiqRtHdr #header h1 {
	margin: 0; /* zeroing the margin of the last element in the #header div will avoid margin collapse - an unexplainable space between divs. If the div has a border around it, this is not necessary as that also avoids the margin collapse */
	padding: 10px 0; /* using padding instead of margin will allow you to keep the element away from the edges of the div */
}

/* Tips for sidebar1:
1. since we are working in percentages, it's best not to use padding on the sidebar. It will be added to the width for standards compliant browsers creating an unknown actual width. 
2. Space between the side of the div and the elements within it can be created by placing a left and right margin on those elements as seen in the ".twoColLiqRtHdr #sidebar1 p" rule.
3. Since Explorer calculates widths after the parent element is rendered, you may occasionally run into unexplained bugs with percentage-based columns. If you need more predictable results, you may choose to change to pixel sized columns.
*/
.twoColLiqRtHdr #sidebar1 {
	float: right; /* since this element is floated, a width must be given */
	width: 250px;
	background: #EBEBEB; /* the background color will be displayed for the length of the content in the column, but no further */
	padding-top: 15px; /* top and bottom padding create visual space within this div  */
	height: 450px;
}
body  {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	background: #666666;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
	background-color: #F90;
}
.twoColLiqRtHdr #sidebar1 h3, .twoColLiqRtHdr #sidebar1 p {
	margin-left: 10px; /* the left and right margin should be given to every element that will be placed in the side columns */
	margin-right: 10px;
}

/* Tips for mainContent:
1. the space between the mainContent and sidebar1 is created with the right margin on the mainContent div.  No matter how much content the sidebar1 div contains, the column space will remain. You can remove this right margin if you want the #mainContent div's text to fill the #sidebar1 space when the content in #sidebar1 ends.
2. to avoid float drop at a supported minimum 800 x 600 resolution, elements within the mainContent div should be 430px or smaller (this includes images).
3. in the Internet Explorer Conditional Comment below, the zoom property is used to give the mainContent "hasLayout." This avoids several IE-specific bugs.
*/
.twoColLiqRtHdr #mainContent { 
	margin: 0 26% 0 10px; /* the left margin should mirror the margin on the header and footer for proper alignment. */
} 

.twoColLiqRtHdr #footer { 
	padding: 0 10px; /* this padding matches the left alignment of the elements in the divs that appear above it. */
	background:#DDDDDD; 
} 
.twoColLiqRtHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
}

/* Miscellaneous classes for reuse */
.fltrt { /* this class can be used to float an element right in your page. The floated element must precede the element it should be next to on the page. */
	float: right;
	margin-left: 8px;
}
.fltlft { /* this class can be used to float an element left in your page. The floated element must precede the element it should be next to on the page. */
	float: left;
	margin-right: 8px;
}
.clearfloat { /* this class should be placed on a div or break element and should be the final element before the close of a container that should fully contain a float */
	clear:both;
    height:0;
    font-size: 1px;
    line-height: 0px;
}
--> 
</style><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColLiqRtHdr #sidebar1 { padding-top: 30px; }
.twoColLiqRtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17681999-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body class="twoColLiqRtHdr" onload="MM_preloadImages('Pictures/EMrollover.png','Pictures/WMrollover.png','Pictures/TFrollover.png','Pictures/docsrollover.png')">

<?php include('http://www.latech.edu/nasathreads/pagesetup.php'); ?>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>

