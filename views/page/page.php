<div id="turn-page">
	总计 <span id="totalRecords"><?php echo $response->pageinfo['total_num'];?></span>
	个记录分为 <span id="totalPages"><?php echo $response->pageinfo['page_num'];?></span>
	页当前第 <span id="pageCurrent"><?php echo $response->pageinfo['page'];?></span>
	页，每页 <input type="text" onchange="page.changePageSize(this)" value="<?php echo $response->pageinfo['page_size'];?>" id="pageSize" size="3">
	<span id="page-link">
		<a href="javascript:page.gotoPageFirst()">第一页</a>
		<a href="javascript:page.gotoPagePrev()">上一页</a>
		<a href="javascript:page.gotoPageNext()">下一页</a>
		<a href="javascript:page.gotoPageLast()">最末页</a>
		<select onchange="page.gotoPage(this)" id="gotoPage">
			<?php foreach($response->pageinfo['select_page'] as $value): ?>
			<option value="<?php echo $value;?>" <?php if($response->pageinfo['page'] == $value){ echo 'selected';}?>><?php echo $value;?></option>
			<?php endforeach;?>
		</select>
		跳转到<input type="text" onchange="return page.gotoPage(this)" value="<?php echo $response->pageinfo['page'];?>" id="pageNum" size="3">页
	</span>
</div>

<script type="text/javascript">

<?php foreach($response->pageinfo['filter'] as $k => $v):?>
page.filter['<?php echo $k; ?>'] = <?php echo $v;?> ;
<?php endforeach;?>

page.filter.query = 1;

	function check_type(obj) {
		var Utils = new Utils();
		var value = Utils.trim(obj.value);

		//判断是否是正整数
		var regex = /^\d+$/;
		return regex.test(value);
	}
</script>