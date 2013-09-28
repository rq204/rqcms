// 下面的FCK的。如果要改变编辑器。先找到要改变编辑器的插入内容到编辑器中的方法。
// 否则插入附件的功能将不能使用。

//插入内容到编辑器
function addhtml(content){
	var oEditor = FCKeditorAPI.GetInstance('content');
	if ( oEditor.EditMode == FCK_EDITMODE_WYSIWYG ) {
		oEditor.InsertHtml(content) ;
	} else {
		alert('请先转换到所见即所得模式') ;
	}
}