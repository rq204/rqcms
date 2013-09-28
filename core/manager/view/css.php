<?php
echo <<<EOT
body {
	margin: 0;
	padding: 0;
	font: 12px Verdana, Tahoma, sans-serif;
	color: #333;
	background-color: #fff;
	background-position: top;
	line-height: 15px;
}
td {
	font: 12px Verdana, Tahoma, sans-serif;
	line-height: 15px;
}
a {
	color: #333399;
	text-decoration: none;
}
a:hover {
	color: #A0A4C1;
	text-decoration: none;
}
.copyright {
	clear: both;
	border-top: 1px solid #D9DBEB;
	background: #fff url({$cssdir}copyright_bg.jpg) repeat-x left top;
	margin: 1px 0;
	padding: 14px 0;
	text-align: center;
}
.copyright td {
	font-size: 11px;
	color: #666666;
	line-height: 18px;
}
.loginBox {
	background: #fff url({$cssdir}gradient_bg.jpg) repeat-x left top;
	color: #333;
	border-top: #A9B6D8 1px solid;
	border-bottom: #AEB2D1 1px solid;
	padding: 30px;
	text-align: left;
	margin-top: 1px;
}
.loginBox h4 {
	font-size: 14px;
}
#simpleHeader {  
	background: #5B60A1;
	height: 15px;
	border-bottom: #2E345D 1px solid;
	margin: 0;
	padding: 0;
}
form {
	margin: 0;
	padding: 0;
	border: 0;
}
input, textarea, select {
	font: 12px Verdana, Tahoma, sans-serif;
}
.formarea {
	border: 1px solid #979AC2;
	color: #333;
	padding: 3px;
	margin: 0;
	line-height: 160%;
}
.formfield {
	border: 1px solid #979AC2;
	color: #333;
	padding: 3px;
	margin: 0;
}
select {
	background: #fff;
	color: #333;
}
.formbutton {
	border-top: 1px solid #9EA3D5;
	border-left: 1px solid #9EA3D5;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
	margin: 0;
	background: #3F4471;
	color: #fff;
	cursor: pointer;
	padding: 4px 3px 0px 5px;
}
.cpnavmenu { 
	color: #2C3467;
	background: #D0D4EA url({$cssdir}nav_tab_bg.jpg) repeat-x left bottom;
	text-align: center;
	padding: 6px 4px 5px 4px;
	margin: 0;
	font-weight: bold;
	border-left: #ddd 1px solid;
	border-right: #999 1px solid;
	border-bottom: #7B81A9 1px solid;
	white-space: nowrap;
}
.cpnavmenuHover {
	text-transform: uppercase;
	color: #787FA4;
	background: #DCE0F6 url({$cssdir}nav_tab_bg_on.jpg) repeat-x left bottom;
	text-align: center;
	padding: 6px 4px 5px 4px;
	margin: 0;
	font-weight: bold;
	border-left: #ddd 1px solid;
	border-right: #999 1px solid;
	border-bottom: #7B81A9 1px solid;
	white-space: nowrap;
}
.cpnavmenu a	{
	text-decoration: none;
}
.cpnavmenu a:hover { 
	text-decoration: none;  
	color: #FFFFFF; 
}
.cpnavmenu_cure  { 
	text-transform:	uppercase;
	color: #333;
	background: #fff;
	padding: 6px 4px 5px 4px;
	margin: 0;
	text-align: center;
	font-weight: bold;
	border-left: #eee 1px solid;
	border-right: #999 1px solid;
	border-bottom: #fff 1px solid;
}
.navcell { 
	padding: 0;
	margin: 0;
	cursor: pointer;
}
.navcell_cure { 
	padding: 0;
	margin: 0;
}
.tableheader {
	font-weight: bold;
	white-space: nowrap;
	color:  #343859;
	background: #AEB1D0 url({$cssdir}bg_table_heading_alt.jpg) repeat-x left top;
	padding: 5px 6px 5px 6px;
	border: 1px solid #FFFFFF;
}
.tdbheader td{
	padding: 5px 6px;
	font-weight: bold;
	color: #fff;
	background: #6E73A5 url({$cssdir}bg_table_heading.jpg) repeat-x left top;
	border-top: 1px solid #696E9E;
	border-bottom: 1px solid #535782;
}
.tdbheader td a{
	color: #fff;
}
.mainbody {
	line-height: 18px;
	width: auto;
	margin: 15px 15px 18px 15px;
	padding: 0 10px;
}
.navlink {
	font-size: 14px;
	font-weight: bold;
	margin: 0;
	padding: 5px 0 10px 0;
	border-bottom: #7B81A9 1px solid;
}
.tableborder {
	margin: 0 0 10px 0;
	padding: 0;
	border-top: 1px solid #B1B6D2;
	border-right: 1px solid #B1B6D2;
	border-left: 1px solid #B1B6D2;
}
.leftmenubody {
	margin-bottom: 0;
	background: #FBFBFD url({$cssdir}gradient_bg.jpg) repeat-x left top;
	border-bottom: 1px solid #B1B6D2;
	padding: 5px 10px;
}
.leftmenuitem {
	padding: 2px 0 3px 0;
	line-height: 160%;
}
.tablecell td{
	color: #333;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #B1B6D2;
	background: #ECEEF3 url({$cssdir}bg_cell.jpg) repeat-x left top;
	padding: 5px 10px 5px 6px;
}
.celltable td{
	color: #333;
	border: none;
	background: none;
	padding: 0 0 5px;
}
.tablecellHover {
	color: #333;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #B1B6D2;
	background: #C0C3E2 url({$cssdir}bg_cell_hover.jpg) repeat-x left top;
	padding: 4px 10px 4px 6px;
}
.tablecelllight {
	color: #333;
	padding: 4px;
	border-top: 1px solid #fff;
	background: #F0F0F2 url({$cssdir}bg_cell_light.jpg) repeat-x left top;
	font-weight: bold;
}
.rightmainbody {
	background: #FBFBFD url({$cssdir}gradient_bg.jpg) repeat-x left top;
	padding: 1px 1px 1px 1px;
	border: #BABEDA 1px solid;
	margin: 0 0 0 0;
}
.topLinks {
	color: #fff;
	background: transparent;
	text-align: right;
	font-weight: bold;
	padding: 0px 30px 0px 0px;
}
.topLinks a { 
	color: #fff;
	text-decoration: none;
}
.topLinks a:hover { 
	color: #B9BDD4;
	text-decoration: none;
}
.topLinksLeft {
	background: transparent;
	font-weight: bold;
	text-align: left;
	padding-left: 30px;
	color: #fff;
}
.topBar {
	background: transparent;
	margin-top: 12px;
	margin-bottom: 18px;
}
.tablebottom {
	margin: 0;
	padding: 0;
	height: 3px;
	background: #8588A0 url({$cssdir}bg_crumb_right.jpg) repeat-x left top;
}
.alertheader td{
	font-weight: bold;
	color: #fff;
	padding: 5px 6px 5px 6px;
	background: #6D0000 url({$cssdir}bg_alert.jpg) repeat-x left top;
	border-top: 1px solid #660000;
	border-bottom: 1px solid #660000;
	margin-bottom: 1px;
}
.alertbox {
	background: #F7F8FA url({$cssdir}box_bg.jpg) repeat-x left top;
	padding: 15px;
	margin: 1px 0 1px 0;
}
.box {
	background: #F7F8FA url({$cssdir}box_bg.jpg) repeat-x left top;
	border: #B1B6D2 1px solid;
	padding: 10px;
	margin-bottom: 15px;
}
.smbox {
	background: #F7F8FA url({$cssdir}box_bg.jpg) repeat-x left top;
	border: #B1B6D2 1px solid;
	padding: 10px;
	margin: 10px;
}
.smbox td {
	border: none;
	background: none;
	text-align: center;
}
.smbox img {
	vertical-align: middle;
	border: none;
	cursor:pointer;
}
.alert {
	margin: 5px 0;
	color: #990000;
	font-weight: bold;
	font-size:14px;
}
.alertmsg {
	padding: 5px 0 2px 0;
	background: transparent;
	line-height: 20px;
}
.desc {
	color: #990000;
}
.multipage {
	float:right;
	font-weight: bold;
	line-height:22px;
}
.records {
	float:left;
	font-weight: bold;
	line-height:22px;
}
.screenshot {
	overflow: hidden;
	width: 300px;
	border: 1px solid #ccc;
	background: #f1f1f1;
}
.templateinfo {
	margin: 0px; 
	padding: 0px;
	list-style-type: none;
}
.templateinfo li {
	line-height: 32px;
	font-size: 16px;
	font-weight: bold;
}
.templateinfo2 {
	margin: 20px 0; 
	line-height:20px;
}
.availabletheme {
	width: 45%;
	margin: 0 1em;
	float: left;
	text-align: center;
	overflow: hidden;
	margin-bottom: 20px; 
}
.availabletheme a.screenshot {
	width: 300px;
	height: 250px;
	display: block;
	margin: auto;
	background: #f1f1f1;
	border: 1px solid #aaa;
	margin-bottom: 10px;
	overflow: hidden;
}
.yes {
	color:#009933;
}
.no {
	color:#990000;
}
.p_nav {
	font-weight: bold;
	text-align: right;
}
EOT;
?>