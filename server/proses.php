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
		// ==== > DELETE SYSTEM
		if ($_POST["action"] == "delete") {
			if(!file_exists("./database/post.json")){
				echo "404";
			}
			else{
				$fileData = file_get_contents('./database/post.json');
				$getData = json_decode($fileData, true);
				$find = $_POST["find"];
				$index = array_search($find, array_column($getData, "title"), true);
				if(array_splice($getData, $index, 1) == true){
					echo "200";
				}
				else{
					echo "404";
				}
			}
		}
		// ==== > UPDATE SYSTEM
		// if ($_POST["action"] == "update") {
			// PROTOTYPING
		// }
		// ==== > VIEW SYSTEM
		if ($_POST["action"] == "select") {
			if(!file_exists("./database/post.json")){
				echo "404";
			}
			else{
				$fileData = file_get_contents('./database/post.json');
				$getData = json_decode($fileData, true);
				foreach($getData as $viewData){
					?>
					<div class="w3-card">
						<div class="w3-border-bottom w3-padding w3-padding-16 w3-row">
							<div class="w3-col m5">
								<img src="../server/cover/<?php echo $viewData['cover'];?>" style="max-width: 100%" />
							</div>
							<div class="w3-col m7">
								<div class="w3-margin-left w3-text-deep-orange">
									<b><?php echo $viewData['title'] ?></b>
								</div>
							</div>
						</div>
						<div class="w3-padding w3-padding-16 w3-text-grey">
							<?php echo substr($viewData["description"], 0, 256);?>
						</div>
					</div>
					<?php
				}
			}
		}
	}
?>