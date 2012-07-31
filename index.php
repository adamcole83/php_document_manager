<?
	require_once('inc/init.php');
	if(!$session->is_logged_in()){ redirect_to('login.php'); }
	require_once('inc/template.head.php');
	require_once('inc/filehelper.php');
?>
<div id="container">
	<h1>Directory Listing</h1>
	<span class="sub"><a href="login.php?do=logout">Logout</a></span>
	<span class="sub"><a href="index.php"><? echo SITE_NAME; ?> Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
	<hr class="gold" />
	<div id="breadcrumbs">Dir: 
		<b>~/</b><a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>">home</a><?php
		$breadcrumbs = @split('/', str_replace($startdir, '', $leadon));
		if(($bsize = sizeof($breadcrumbs))>0) {
			$sofar = '';
			for($bi=0;$bi<($bsize-1);$bi++) {
				$sofar = $sofar . $breadcrumbs[$bi] . '/';
				echo '<b>/</b><a href="'.strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($sofar).'">'.$breadcrumbs[$bi].'</a>';
			}
		}
		
		$baseurl = strip_tags($_SERVER['PHP_SELF']) . '?dir='.strip_tags($_GET['dir']) . '&amp;';
		$fileurl = 'sort=name&amp;order=asc';
		$sizeurl = 'sort=size&amp;order=asc';
		$dateurl = 'sort=date&amp;order=asc';
		
		switch ($_GET['sort']) {
			case 'name':
				if($_GET['order']=='asc') $fileurl = 'sort=name&amp;order=desc';
				break;
			case 'size':
				if($_GET['order']=='asc') $sizeurl = 'sort=size&amp;order=desc';
				break;
			case 'date':
				if($_GET['order']=='asc') $dateurl = 'sort=date&amp;order=desc';
				break;  
			default:
				$fileurl = 'sort=name&amp;order=desc';
				break;
		} ?>
	</div><!-- #breadcrumbs -->
	
	<div id="listingcontainer">
		<div id="listingheader"> 
			<div id="headerfile"><a href="<?php echo $baseurl . $fileurl;?>">File</a></div>
			<div id="headersize"><a href="<?php echo $baseurl . $sizeurl;?>">Size</a></div>
			<div id="headermodified"><a href="<?php echo $baseurl . $dateurl;?>">Last Modified</a></div>
		</div><!-- #listingheader -->
		<div id="listing">
			<? $class = 'b'; ?>
			<? if($dirok): ?>
				<div>
					<a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($dotdotdir);?>" class="<?php echo $class;?>">
					<img src="img/ico/dirup.png" alt="Folder" /><strong>..</strong> <em>&nbsp;</em>&nbsp;</a>
				</div>
				<? $class=($class=='b')?'w':'b'; $filecount++; ?>
			<? endif; ?>
			
			<form method="post" action="<? $_SERVER['PHP_SELF'] ?>">
				<? $arsize = sizeof($dirs); ?>
				<? for($i=0;$i<$arsize;$i++): ?>
					<div>
						<? if($allowuploads): ?>
							<input class="del" type="checkbox" name="sel_d_<?php echo $i; ?>" value="<?php echo urlencode(str_replace($startdir,'',$leadon).$dirs[$i]); ?>" />
						<? endif; ?>
						<a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode(str_replace($startdir,'',$leadon).$dirs[$i]);?>" class="<?php echo $class;?>">
							<img src="img/ico/folder.png" alt="<?php echo $dirs[$i];?>" />
							<strong><?php echo $dirs[$i];?></strong> <em>-</em> <?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$dirs[$i]));?></a>
					</div>
					<? $class=($class=='b')?'w':'b'; $filecount++; ?>
				<? endfor; ?>
							
				<? $arsize = sizeof($files); ?>
				<?
				for($i=0;$i<$arsize;$i++):
					$icon = 'unknown.png';
					$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
					$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
				
					if($filetypes[$ext])
						$icon = $filetypes[$ext];
					
					$filename = $files[$i];
					if(strlen($filename)>43)
						$filename = substr($files[$i], 0, 40) . '...';
					
					$fileurl = $includeurl . $leadon . $files[$i];
					if($forcedownloads)
						$fileurl = $_SESSION['PHP_SELF'] . '?dir=' . urlencode(str_replace($startdir,'',$leadon)) . '&download=' . urlencode($files[$i]);
					else
						$target = 'target="_blank"';
				?>
				<div>
					<? if($allowuploads): ?>
						<input class="del" type="checkbox" name="sel_f_<? echo $i; ?>" value="<?php echo $fileurl;?>" />
					<? endif; ?>
					<a <? echo $target; ?> href="<?php echo $fileurl;?>" class="<?php echo $class;?>"<?php echo $thumb2;?>>
						<img src="img/ico/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" />
						<strong><?php echo $filename;?></strong> <em><?php echo round(filesize($includeurl.$leadon.$files[$i])/1024);?>KB</em> <?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$files[$i]));?><?php echo $thumb;?></a>
				</div>
				<? $class=($class=='b')?'w':'b'; $filecount++; ?>	
				<? endfor; ?>		
				
				<? if($filecount < 10): ?>
					<? for($i=0;$i<(10-$filecount);$i++): ?>
						<div>
							<a class="<?php echo $class;?> null">&nbsp;</a>
						</div>
						<? $class=($class=='b')?'w':'b'; ?>
					<? endfor; ?>
				<? endif; ?>
	
			</div><!-- #listing -->
			<? if($allowuploads): ?>
			<div id="upload">
				<div id="uploadtitle">
					<strong>Modify Selected</strong>
				</div><!-- #uploadtitle -->
				<div id="uploadcontent">
					<input type="submit" name="delete" value="Delete Selected" />
					<input type="submit" name="rename" value="Rename Selected to..." />
					<input type="text" name="newname" value="" />
				</div><!-- #uploadcontent -->
			</div><!-- #upload -->
			<? endif; ?>
		</form>
			<? if($allowuploads): ?>
			<div id="upload">
				<div id="uploadtitle">
					<strong>New Folder</strong>
				</div><!-- #uploadtitle -->
				<div id="uploadcontent">
					<form method="post" action="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?dir=<?php echo urlencode(str_replace($startdir,'',$leadon));?>" enctype="multipart/form-data">
						<input type="text" name="new" value="" />
						<input type="submit" value="Create" />
					</form>
				</div><!-- #uploadcontent -->
			</div><!-- #upload -->
			<? endif; ?>
			<?php
			if($allowuploads) {
				$phpallowuploads = (bool) ini_get('file_uploads');		
				$phpmaxsize = ini_get('upload_max_filesize');
				$phpmaxsize = trim($phpmaxsize);
				$last = strtolower($phpmaxsize{strlen($phpmaxsize)-1});
				switch($last) {
					case 'g':
						$phpmaxsize *= 1024;
					case 'm':
						$phpmaxsize *= 1024;
			}
			?>
			<div id="upload">
				<div id="uploadtitle">
					<strong>File Upload</strong> (Max Filesize: <?php echo $phpmaxsize / 1024;?> MB)
					<?php if($uploaderror) echo '<div class="upload-error">'.$uploaderror.'</div>'; ?>
				</div><!-- #uploadtitle -->
				<div id="uploadcontent">
				<? if($phpallowuploads){ ?>
					<form method="post" action="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?dir=<?php echo urlencode(str_replace($startdir,'',$leadon));?>" enctype="multipart/form-data">
						<input type="file" name="file" />
						<input type="submit" value="Upload" />
					</form>
				<? } else { ?>
					File uploads are disabled in your php.ini file. Please enable them.
				<? } ?>
				</div><!-- #uploadcontent -->
			</div><!-- #upload -->
		<? } ?>
	</div><! #listingcontainer-->
</div><!-- #container -->
<?php require_once('inc/template.foot.php') ?>