<?php

/**
 * Formats "New Twitter" lists in the correct order suitable for reading on WordPress.
 */

if ( empty($_FILES) ) //Show file selection form
{
	include "form.html";
}

else //Process the selected file
{
	$uploaded = array_pop($_FILES); //Uploaded file
	$input_file = $uploaded["tmp_name"]; //Temporary input filename
	$output_file = str_replace(".png", "_fixed.png", $uploaded["name"]); //Output filename
	$input = imagecreatefrompng($input_file); //Input image object
	$imagesize = getimagesize($input_file); //Input image info
	$width = $imagesize[0];
	$height = $imagesize[1];
	$output = imagecreatetruecolor($width, $height); //Creates blank output image
	$prevy = $height - 1; //Keeps track of previous y value
	$separator = 15461355; //Tweet separator color
	
	imagefill($output, 0, 0, $separator); //Replace black fill with separator color

	//Process input image pixel by pixel
	for ( $y = $prevy; $y >= 0; $y-- )
	{
		$color = imagecolorat($input, 0, $y); //RGB of this pixel
		
		if ( $color == $separator ) //If there's a tweet separator here, copy the tweet
		{
			imagecopy($output, $input, 0, $height - $prevy - 2, 0, $y - 1, $width, $prevy - $y);
			$prevy = $y;
		}
	}
	
	//Copies the final tweet from the very top of the source image to the bottom of the destination
	imagecopy($output, $input, 0, $height - $prevy - 1, 0, 0, $width, $prevy);
	
	//Saves the output image
	imagepng($output, $output_file);
	
	//Displays the final image
	echo "<img src='$output_file' />";
}
