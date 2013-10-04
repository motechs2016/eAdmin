<?php 
	header("Content-type:text/html;charset=utf-8");
	$head = new lib_head();
	echo $head->addCss('shop/general.css')->addCss('shop/main.css')->getCss();
	echo $head->addScript('utils.js')->addScript('jquery-1.6.js')->getScript();
	$head->setTitle('ECSHOP 管理中心');
?>

<div class="list-div">
<table cellspacing="1" cellpadding="3">
  <tbody><tr>
    <th class="group-title" colspan="4">系统信息</th>
  </tr>
  <tr>
    <td width="20%">服务器操作系统:</td>
    <td width="30%"><?php echo "{$response->base_info['os']}({$response->base_info['ip']})";?></td>
    <td width="20%">Web 服务器:</td>
    <td width="30%"><?php echo $response->base_info['web_server'];?></td>
  </tr>
  <tr>
    <td>PHP 版本:</td>
    <td><?php echo $response->base_info['php_version'];?></td>
    <td>MySQL 版本:</td>
    <td><?php echo $response->base_info['mysql_version'];?></td>
  </tr>
  <tr>
    <td>安全模式:</td>
    <td><?php echo $response->base_info['safe_mode'] ? '是':'否';?></td>
    <td>安全模式GID:</td>
    <td><?php echo $response->base_info['safe_mode_gid'] ? '是':'否';?></td>
  </tr>
  <tr>
    <td>Socket 支持:</td>
    <td><?php echo $response->base_info['socket'];?></td>
    <td>时区设置:</td>
    <td><?php echo $response->base_info['timezone'];?></td>
  </tr>
  <tr>
    <td>GD 版本:</td>
    <td><?php echo $response->base_info['GD_version'];?></td>
    <td>Zlib 支持:</td>
    <td><?php echo $response->base_info['zlib'];?></td>
  </tr>
  <tr>
    <td>编码:</td>
    <td><?php echo $response->base_info['charset'];?></td>
    <td>文件上传的最大大小:</td>
    <td><?php echo $response->base_info['upload_max_filesize'];?></td>
  </tr>
  <tr>
    <td>占用内存:</td>
    <td><?php echo number_format($response->base_info['memory'],3).'M';?></td>
    <td></td>
    <td></td>
  </tr>
</tbody></table>
</div>