<?php 

	function resizeImage ($oldFilepath, $newFilepath, $dimensionImage, $scaleMode = 0) { 

		if (!(file_exists($oldFilepath)) || file_exists($newFilepath)) {

			return false; 

		}

		$attributeImage = getimagesize($oldFilepath); 
		$oldImageWidth = $attributeImage[0]; 
		$oldImageHeight = $attributeImage[1]; 
		$fileTypeImage = $attributeImage[2]; 

		if ($oldImageWidth <= 0 || $oldImageHeight <= 0) {

			return false;

		} 

		$aspectRatioImage = $oldImageWidth / $oldImageHeight; 

		if ($scaleMode == 0) { 

			$scaleMode = ($aspectRatioImage > 1 ? -1 : -2); 

		} elseif ($scaleMode == 1) { 

			$scaleMode = ($aspectRatioImage > 1 ? -2 : -1); 

		} 

		if ($scaleMode == -1) { 

			$newImageWidth = $dimensionImage; 
			$newImageHeight = round($dimensionImage / $aspectRatioImage); 

		} elseif ($scaleMode == -2) { 

			$newImageHeight = $dimensionImage; 
			$newImageWidth = round($dimensionImage * $aspectRatioImage); 

		} else { 

			return false; 

		} 

		switch ($fileTypeImage) { 

			case 1: 

				$oldImage = imagecreatefromgif($oldFilepath); 
				$newImage = imagecreate($newImageWidth, $newImageHeight); 

				imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $oldImageWidth, $oldImageHeight);
				imagegif($newImage, $newFilepath); 

				break; 

			case 2: 

				$oldImage = imagecreatefromjpeg($oldFilepath); 
				$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight); 
				imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $oldImageWidth, $oldImageHeight);
				imagejpeg($newImage, $newFilepath); 

				break; 

			case 3: 

				$oldImage = imagecreatefrompng($oldFilepath); 
				$colorDepthImage = imagecolorstotal($oldImage); 

				if ($colorDepthImage == 0 || $colorDepthImage > 255) { 

					$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight); 

				} else { 

					$newImage = imagecreate($newImageWidth, $newImageHeight); 

				} 

				imagealphablending($newImage, false); 
				imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $oldImageWidth, $oldImageHeight);
				imagesavealpha($newImage, true); 
				imagepng($newImage, $newFilepath); 

				break; 

			default: 

				return false; 

		} 

		imagedestroy($oldImage); 
		imagedestroy($newImage);

		return true; 

	} 

?>
