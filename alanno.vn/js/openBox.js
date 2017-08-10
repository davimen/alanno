function openBox(winWidth, winHeight, fileSrc) {
	var w=(screen.availWidth-winWidth)/2;
	var h=(screen.availHeight-winHeight)/2;
	newParameter = "width=" + winWidth + ",height=" + winHeight + ",addressbar=no,scrollbars=yes,toolbar=no,top="+h+",left="+w+", resizable=no";
    newWindow = window.open (fileSrc, "a", newParameter);
	newWindow.focus();
}
function modelessDialogShow(url,width,height)
{
if (document.all&&window.print) //if ie5
eval('window.showModelessDialog(url,window,"dialogWidth:'+width+'px;dialogHeight:'+height+'px;edge:Raised;center:1;help:0;resizable:1;")');
else
eval('window.open(url,"a","width='+width+'px,height='+height+'px,resizable=1,scrollbars=1,copyhistory=yes")')
}
