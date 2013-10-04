<div class="list">
	<table border="1" cellpadding="0" cellspacing="0" bordercolor="red">
	<caption>用户密码表</caption>
		<tr><th>用户名</th><th>密码</th><th>操作</th></tr>
		<?php foreach($response->users as $value):?>
			<tr>
				<td><?php echo $value['name'];?></td>
				<td><?php echo $value['pass'];?></td>
				<td><a href="update/<?php echo $value['id'];?>">查看</a>  <a href="delete/<?php echo $value['id'];?>">删除</a></td>
			</tr>
		<?php endforeach;?>
	</table>
</div>
<form action='index' method="post">
username:<input type="text" name="user"/>
password:<input type="password" name="pass"/>
<input type="submit" name="submit" value="save"/>
</form>
<?php lib_functions::action('test/add', 'ADD', array('id'=>'add_id','class'=>'add_class'));?>
<div id="gallery">
	<?php echo lib_functions::image('hello_cc.gif');?>
	<?php echo lib_functions::image('jia_fm.gif');?>
	<?php echo lib_functions::image('xi_yy.gif');?>
</div>
<div id="outer">
<a href="javascript:" id="btn" >left</a>
<div id="scroll" style="height:20px;overflow: hidden">1234567789<br/>k0123456789<br/>0k12345667888123345677888899</div>
</div>
<script>
var url = "<?php echo lib_functions::file_path('layout/js/plugins/galleria/themes/classic/galleria.classic.min.js')?>";

Galleria.loadTheme(url);
var data = [
            {
                image: "<?php echo lib_functions::image_src('hello_cc.gif');?>",
                thumb: "<?php echo lib_functions::image_src('hello_cc.gif');?>",
                big: 'big1.jpg',
                title: 'my first image',
                description: 'Lorem ipsum caption',
                link: 'http://domain.com'
            },
            {
                image: "<?php echo lib_functions::image_src('xi_yy.gif');?>",
                thumb: "<?php echo lib_functions::image_src('xi_yy.gif');?>",
                big: 'big2.jpg',
                title: 'my second image',
                description: 'Another caption',
                link: '/path/to/destination.html'
            }
        ];

/*
    $("#gallery").galleria({
        width: 500,
        height: 500,
        data_source:data
    });
  */  
    $(document).ready(function(){
        var n =10;
        $('#btn').click(function(){
	        	$('div#scroll').scrollTop(n);
	        	n +=10;
        })
    	setInterval(function(){
    		$('div#scroll').scrollTop(n);
        	n +=3;
        	if(n>50) { n=0;}
        },100);
     });
</script>
