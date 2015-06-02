<?php
	if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
		include("../config/settings.php");

		if (constant(sb_user_upload)) {
			setlocale(LC_ALL, 'ru_RU.utf8'); // ИНАЧЕ pathinfo ОТРЕЖЕТ ПЕРВОЕ СЛОВО
			if (!$_SESSION) session_start();
			$file_name = pathinfo($_FILES['upl']['name'], PATHINFO_FILENAME);
			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
			$upload_path = "../".sb_file_path.$_SESSION['CWD']."/";
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $upload_path = iconv("utf-8", "cp1251", $upload_path);
			
			if (!file_exists($upload_path)) mkdir($upload_path, 0775,true);
			
			
			// A list of permitted file extensions
			/* $ext_allowed = array('txt', 'jpg', 'pdf', 'zip', 'png');
			if(!in_array(strtolower($extension), $ext_allowed)){
				header('location: ../?action=upload&file=');
				exit;
			} */
			
			// Remove spaces from filename
			// $file_name = str_replace(" ", "-", $file_name);
			$file_name = preg_replace("/[^0123456789!\-\(\)_+.,\[\]ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцчшщъыьэюя]/", "_", $file_name);
			
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $file_name = iconv("utf-8", "cp1251", $file_name);
			
			// Check for duplicates
			$file_count = 0;
			$final_name = $file_name.".".$extension;
			while(file_exists($upload_path.$final_name)){
				$file_count++;
				$final_name = $file_name.'_'.$file_count.'.'.$extension;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $upload_path.$final_name)){
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $final_name = iconv("cp1251", "utf-8", $final_name);
				header('location: ../?action=upload&file='.$final_name.'');
				exit;
			}
		} else {
			// Not allowed
			header('location: ../');
			exit;
		}
	}

	header('location: ../');
	exit;
?>
