<?
	/* SETTINGS */
	
	/*
	Include URL - If you are including this script in another file, 
	please define the URL to the Directory Listing script (relative
	from the host)
	*/
	$includedir = false;
	
	/*
	Start Directory - To list the files contained within the current 
	directory enter '.', otherwise enter the path to the directory 
	you wish to list. The path must be relative to the current 
	directory and cannot be above the location of index.php within the 
	directory structure.
	*/	
	$startdir = 'dir/';
	
	/*
	Show Thumbnails? - Set to true if you wish to use the 
	scripts auto-thumbnail generation capabilities.
	This requires that GD2 is installed.
	*/
	$showthumnails = false;
	
	/*
	Memory Limit - The image processor that creates the thumbnails
	may require more memory than defined in your PHP.INI file for 
	larger images. If a file is too large, the image processor will
	fail and not generate thumbs. If you require more memory, 
	define the amount (in megabytes) below
	*/
	$memorylimit = false;
	
	/*
	Show Directories - Do you want to make subdirectories available?
	If not set this to false
	*/
	$showdirs = true;
	
	/* 
	Force downloads - Do you want to force people to download the files
	rather than viewing them in their browser?
	*/
	$forcedownloads = false;
	
	/*
	Hide Files - If you wish to hide certain files or directories 
	then enter their details here. The values entered are matched
	against the file/directory names. If any part of the name 
	matches what is entered below then it is not shown.
	*/
	$hide = array('.htaccess', '.htpasswd');

	/* Only Display Files With Extension... - if you only wish the user
	to be able to view files with certain extensions, add those extensions
	to the following array. If the array is commented out, all file
	types will be displayed.
	*/
	# $showtypes = array('jpg', 'png', 'gif', 'zip', 'txt');
	
	/* 
	Show index files - if an index file is found in a directory
	to you want to display that rather than the listing output 
	from this script?
	*/	
	$displayindex = false;
	
	/*
	Allow uploads? - If enabled users will be able to upload 
	files to any viewable directory. You should really only enable
	this if the area this script is in is already password protected.
	*/
	$allowuploads = false;
	
	/* Upload Types - If you are allowing uploads but only want
	users to be able to upload file with specific extensions,
	you can specify these extensions below. All other file
	types will be rejected. Comment out this array to allow
	all file types to be uploaded.
	*/
	$uploadtypes = array(
							'doc',
							'docx',
							'xls',
							'xlsx',
							'ppt',
							'pptx',
							'txt',
							'pages',
							'numbers',
							'png',
							'jpg',
							'jpeg',
							'gif',
							'swf',
							'fla',
							'psd',
							'ai',
							'eps',
							'indd',
							'psb',
							'ico',
							'mov',
							'm4v',
							'mp4',
							'mpg',
							'mpeg',
							'avi',
							'wmv',
							'aiff',
							'mp3',
							'wav',
							'rm',
							'zip',
							'rar',
							'dmg',
							);
	
	/*
	Overwrite files - If a user uploads a file with the same
	name as an existing file do you want the existing file
	to be overwritten?
	*/
	$overwrite = false;
	
	/*
	Index files - The follow array contains all the index files
	that will be used if $displayindex (above) is set to true.
	Feel free to add, delete or alter these
	*/
	$indexfiles = array(
							'index.html',
							'index.htm',
							'default.htm',
							'default.html',
							'index.php',
							'default.php'
							);
	
	/*
	File Icons - Each entry relates to the extension of the 
	given file, in the form <extension> => <filename>. 
	These files must be located within the img/ico directory.
	*/
	$filetypes = array(
							'png' => 'jpg.gif',
							'jpeg' => 'jpg.gif',
							'bmp' => 'jpg.gif',
							'jpg' => 'jpg.gif', 
							'gif' => 'gif.gif',
							'zip' => 'archive.png',
							'rar' => 'archive.png',
							'exe' => 'exe.gif',
							'setup' => 'setup.gif',
							'txt' => 'text.png',
							'htm' => 'web.png',
							'html' => 'web.png',
							'fla' => 'swf.png',
							'swf' => 'swf.png',
							'xls' => 'xls.gif',
							'doc' => 'doc.gif',
							'docx' => 'doc.gif',
							'sig' => 'sig.gif',
							'fh10' => 'fh10.gif',
							'pdf' => 'pdf.png',
							'psd' => 'psd.png',
							'rm' => 'real.gif',
							'mpg' => 'mov.png',
							'mpeg' => 'mov.png',
							'mov' => 'mov.png',
							'mp4' => 'mov.png',
							'mp3' => 'audio.png',
							'wav' => 'audio.png',
							'aiff' => 'audio.png',
							'avi' => 'video.gif',
							'eps' => 'eps.gif',
							'gz' => 'archive.png',
							'asc' => 'sig.gif',
							'php' => 'web.png'
							);
	
	/* BRAINS */
	if($includeurl) {
		$includeurl = preg_replace("/^\//", "${1}", $includeurl);
		if(substr($includeurl, strrpos($includeurl, '/')) != '/')
			$includeurl .= '/';
	}
	
	if(Group::can('upload_files'))
		$allowuploads = true;
	
	//error_reporting(0);
	if(!function_exists('imagecreatetruecolor'))
		$showthumbnails = false;
		
	if($startdir)
		$startdir = preg_replace("/^\//", "${1}", $startdir);
		
	$leadon = $startdir;
	if($leadon=='.')
		$leadon = '';
	
	if((substr($leadon, -1, 1)!='/') && $leadon!='')
		$leadon = $leadon . '/';
		
	$startdir = $leadon;
	
	if($_GET['dir']) {
		if(substr($_GET['dir'], -1, 1)!='/')
			$_GET['dir'] = strip_tags($_GET['dir']) . '/';
		
		$dirok = true;
		$dirnames = @split('/', strip_tags($_GET['dir']));
		for($di=0; $di<sizeof($dirnames); $di++) {
			if($di<(sizeof($dirnames)-2))
				$dotdotdir = $dotdotdir . $dirnames[$di] . '/';
			
			if($dirnames[$di] == '..')
				$dirok = false;
		}
		
		if(substr($_GET['dir'], 0, 1)=='/')
			$dirok = false;
		
		if($dirok)
			 $leadon = $leadon . strip_tags($_GET['dir']);
	}
	
	if($_GET['download'] && $forcedownloads) {
		$file = str_replace('/', '', $_GET['download']);
		$file = str_replace('..', '', $file);
	
		if(file_exists($includeurl . $leadon . $file)) {
			header("Content-type: application/x-download");
			header("Content-Length: ".filesize($includeurl . $leadon . $file)); 
			header('Content-Disposition: attachment; filename="'.$file.'"');
			readfile($includeurl . $leadon . $file);
			die();
		}
		die();
	}
	
	if($_POST['delete']){
		foreach($_POST as $key => $value){
			if(preg_match('/sel_/i', $key)){
				if(preg_match('/_d_/i', $key)){
					$dir = $leadon.urldecode($value);
					error_log($dir, 0);
					rrmdir($dir);
				}elseif(preg_match('/_f_/', $key)){
					unlink($value);
				}
			}
		}
	}
	
	if($_POST['new']){
		mkdir($leadon.$_POST['new'].'/', 0777, true);
	}
	
	if($_POST['rename']){
		foreach($_POST as $key => $value){
			if(preg_match('/sel_/i', $key)){
				if(preg_match('/_d_/i', $key)){
					$base = $leadon;
					$dir = $base.urldecode($value);
					$new = $base.$_POST['newname']."/";
					rename($dir, $new);
				}
			}
		}
	}
	
	if($allowuploads && $_FILES['file']) {
		$upload = true;
		if(!$overwrite) {
			if(file_exists($leadon.$_FILES['file']['name']))
				$upload = false;
		}
		
		if($uploadtypes) {
			if(!in_array(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')+1, strlen($_FILES['file']['name'])), $uploadtypes)) {
				$upload = false;
				$uploaderror = "<strong>ERROR: </strong> You may only upload files of type ";
				$i = 1;
				foreach($uploadtypes as $k => $v) {
					if($i == sizeof($uploadtypes) && sizeof($uploadtypes) != 1)
						$uploaderror.= ' and ';
					else if($i != 1)
						$uploaderror.= ', ';
					
					$uploaderror.= '.'.strtoupper($v);
					$i++;
				}
			}
		}
		
		if($upload)
			move_uploaded_file($_FILES['file']['tmp_name'], $includeurl.$leadon . $_FILES['file']['name']);
	}
	
	$opendir = $includeurl.$leadon;
	if(!$leadon)
		$opendir = '.';
		
	if(!file_exists($opendir)) {
		$opendir = '.';
		$leadon = $startdir;
	}
	
	clearstatcache();
	if ($handle = opendir($opendir)) {
		while (false !== ($file = readdir($handle))) { 
			//first see if this file is required in the listing
			if ($file == "." || $file == "..")
				continue;
			
			$discard = false;
			for($hi=0;$hi<sizeof($hide);$hi++) {
				if(strpos($file, $hide[$hi])!==false)
					$discard = true;
			}
			
			if($discard)
				continue;
			
			if (@filetype($includeurl.$leadon.$file) == "dir") {
				if(!$showdirs)
					continue;
			
				$n++;
				if($_GET['sort']=="date")
					$key = @filemtime($includeurl.$leadon.$file) . ".$n";
				else
					$key = $n;
				
				$dirs[$key] = $file . "/";
			
			} else {
				$n++;
				if($_GET['sort']=="date")
					$key = @filemtime($includeurl.$leadon.$file) . ".$n";
					
				elseif($_GET['sort']=="size")
					$key = @filesize($includeurl.$leadon.$file) . ".$n";
					
				else
					$key = $n;
				
				if($showtypes && !in_array(substr($file, strpos($file, '.')+1, strlen($file)), $showtypes))
					unset($file);
					
				if($file)
					$files[$key] = $file;
				
				if($displayindex) {
					if(in_array(strtolower($file), $indexfiles)) {
						header("Location: $leadon$file");
						die();
					}
				}
			}
		}
		closedir($handle); 
	}
	
	//sort files
	if($_GET['sort']=="date") {
		@ksort($dirs, SORT_NUMERIC);
		@ksort($files, SORT_NUMERIC);
	}
	elseif($_GET['sort']=="size") {
		@natcasesort($dirs); 
		@ksort($files, SORT_NUMERIC);
	}
	else {
		@natcasesort($dirs); 
		@natcasesort($files);
	}
	
	//order files correctly
	if($_GET['order']=="desc" && $_GET['sort']!="size")
		$dirs = @array_reverse($dirs);
	
	if($_GET['order']=="desc")
		$files = @array_reverse($files);
		
	$dirs = @array_values($dirs);
	$files = @array_values($files);
	$filecount = 0;
	
?>