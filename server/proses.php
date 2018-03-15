<?php
	if(isset($_POST["action"])){
		if ($_POST["action"] == "insert") {
			$title = $_POST["title"];
			$description = $_POST["description"];
			$coverName = date("Ymd-His") . $_FILES["cover"]["name"];
			$coverTempName = $_FILES["cover"]["tmp_name"];
			$coverLocation = "./cover/".$coverName;

			if(move_uploaded_file($coverTempName, $coverLocation)){
				if (file_exists("./database/post.json")) {
					$fileData = file_get_contents('./database/post.json');
					$getData = json_decode($fileData, true);
					$newData = array(
						"title" => $title,
						"description" => $description,
						"cover" => $coverName
					);
					array_push($getData, $newData);
					$fileData = fopen("./database/post.json", "w") or die("404");
					fwrite($fileData, json_encode($getData, JSON_PRETTY_PRINT));
					fclose($fileData);
					echo "200";
				}
				else{
					$fileData = fopen("./database/post.json", "w") or die("404");
					$newData = array(
						"title" => $title,
						"description" => $description,
						"cover" => $coverName
					);
					fwrite($fileData, json_encode(array($newData)));
					fclose($fileData);
					echo "200";
				}
			}
			else{
				echo "404";
			}
		}
	}
?>