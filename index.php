<?php 

	setlocale(LC_ALL, 'ru_RU.utf8'); // ИНАЧЕ pathinfo ОТРЕЖЕТ ПЕРВОЕ СЛОВО
	session_start();	
	if (isset($_GET['CWD']) && $_GET['CWD']) {
		$_SESSION['CWD'] = (preg_match("/(MSIE|Trident|govno)/", $_SERVER['HTTP_USER_AGENT']))
			? str_replace("\\","/",iconv("cp1251", "utf-8", $_GET['CWD']))
			: str_replace("\\","/",$_GET['CWD']);
	} else {
		$_SESSION['CWD'] = isset($_SESSION['CWD'])
			? $_SESSION['CWD']
			: '';
	}	

	/* *********************************************
	 * based on Sabre File manager 2.0.1 - Main file by Mark Norton
	 * original code at https://github.com/mrknorton4007/sabre
	 * Version: 1.0
	 * ********************************************* */

	// Include function.php
	include("./core/functions.php"); 

	// Load and check settings
	include("./core/redirect.php");

	// Complete requests operations and load header
	include("./core/header.php"); 


	// Print Sabre menu
	echo "      <div id=\"menu\">\n";
	// Check for messages
	if (isset($act_class, $act_text)) {
		showMsg($act_class, $act_text);
	}

	// Check if folder exist
	if (!file_exists(sb_file_path)) {
		showMsg('danger', 'Can\'t open <strong>'.sb_file_path.'</strong> folder. Check your Sabre settings!');
	}
	
	if (constant(sb_user_upload)) {
		echo "        <form id=\"drop\" method=\"post\" action=\"./core/upload.php\" enctype=\"multipart/form-data\">\n";
		echo "        <label for=\"upl\">Выберите файл </label> <input type=\"file\" name=\"upl\" /> <input type=\"submit\" value=\"Загрузить\" />\n";
		echo "        </form>\n";
	}
	if (!constant(sb_public_file)) {
		echo "        <a href=\"./core/logout.php?c=1\" class=\"btn btn-blue\">Выйти</a>\n";
	}
	echo "      </div> <!-- /menu -->\n\n";


	// Open container tag
	echo "      <div class=\"container\">\n";
	// Show file list
	echo "        <table class=\"filesys\">\n";
	echo "        <thead><tr><th class=\"file-index\">#</th><th class=\"file-name\">Имя <a href=\"./\" >[обновить список файлов]</a></th><th class=\"file-size\">Размер</th><th class=\"file-date\">Загружен</th></tr></thead>\n";
	echo "        <tbody>\n";
	
	// $mod_rewrite = (function_exists('apache_get_modules'))
		// ? in_array('mod_rewrite', apache_get_modules())
		// : (getenv('HTTP_MOD_REWRITE')=='On' ? true : false);
	
	$filecount = 0;
	$path = "./".sb_file_path.$_SESSION['CWD'];
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $path = iconv("utf-8", "cp1251", $path);
	foreach(glob($path."/*") as $file){
		if (!is_dir($file)) {
			$filecount++;
			$filename = basename($file);
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $filename = iconv("cp1251", "utf-8", $filename);
			$filelink = $path."/".$filename;
			$date = date(sb_date_style, filemtime($file));
			$filedim = makeSize($file);

			echo "        <tr>";
			echo "<td class=\"file-index\">".$filecount."</td>";
			// echo ($mod_rewrite)
				// ? "<td class=\"file-name\"><p><a title=\"Скачать\" href=\"./download/".$filename."\">".$filename."</a>"
				// : "<td class=\"file-name\"><p><a title=\"Скачать\" href=\"./?action=download&amp;file=".$filename."\">".$filename."</a>";
			echo "<td class=\"file-name\"><p><a title=\"Скачать\" href=\"./?action=download&amp;file=".$filename."\">".$filename."</a>";
			if(constant(sb_user_delete)) 
				echo " <a class=\"float-right color-danger\" href=\"./?action=delete&amp;file=".$filename."\">Удалить</a>";	
			echo "</p>";
			echo "</td>";

			echo "<td class=\"file-size\">".$filedim."</td>";
			echo "<td class=\"file-date\">".$date."</td>";
			echo "</tr>\n";	
		} else {
			// Sorry, no recursive scan for now...
		}
	}

	echo "        </tbody>\n";
	echo "        </table>\n";
	echo "      </div> <!-- /container -->\n";

	// Include footer
	include("./core/footer.php");
?>
