<script language="javascript">
<!--
var clickvalue='';
function faqShow(idnum) {
	var faq=eval("faq"+idnum+".style");
	if(clickvalue != faq) {
		if(clickvalue!='') {
			clickvalue.display='none';
		}
		faq.display='table';
		clickvalue=faq;
	} else {
		faq.display='none';
		clickvalue='';
	}
	if(parent.document.getElementById('bbs_frame') != null) {
		parent.resizeFrame(parent.document.getElementById('bbs_frame'));
	}
}
-->
</script>

<!-- 카테고리 -->
<div class="category_list clearfix">
	<?=$catlist?>
</div>
<!-- 카테고리 끝-->

<!-- 게시물 시작 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_common_table faq">
 