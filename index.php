<?php

$newPosition = null;

$sequence = [];
$landed = [];

$sizeX = 6;
$sizeY = 6;

$complete = $sizeX * $sizeY;

$movePatterns = [
	['x' => 2, 'y' => 1],
	['x' => 2, 'y' => -1],
	['x' => -2, 'y' => 1],
	['x' => -2, 'y' => -1],
	['x' => 1, 'y' => 2],
	['x' => 1, 'y' => -2],
	['x' => -1, 'y' => 2],
	['x' => -1, 'y' => -2]
];

function computePath($startPosition, $sequence, $landed, $newPosition, $levelsDeep)
{
	global $movePatterns;
	global $sizeX;
	global $sizeY;
	global $complete;
	
	static $iterations = 0;
	
	$iterations++;
	
	if(0 === $iterations % 10000000)
	{
		echo '	Start Position: ' . $startPosition['x'] . ', ' . $startPosition['y'] . '    ' .
				'Iteration: ' . sprintf('%15d', $iterations) . '    ' . 
				'Level: ' . sprintf('%15d', $levelsDeep) . '    ' . 
				'Sequence: ' . sprintf('%3d', count($sequence)) . 
				PHP_EOL;
	}
	
	$newX = $newPosition['x'];
	$newY = $newPosition['y'];
	
	// Outside of bounds
	if($newX >= $sizeX || $newX < 0)
	{
		return false;
	}
	
	// Outside of bounds
	if($newY >= $sizeY || $newY < 0)
	{
		return false;
	}
	
	// Already been there
	if(isset($landed[$newX][$newY]) && true === $landed[$newX][$newY])
	{
		return false;
	}
	
	// Current position is ok! Yay!
	$sequence[] = ['x' => $newX, 'y' => $newY];
	$landed[$newX][$newY] = true;
	
	// We did it! Woooooo!
	if($complete === count($sequence))
	{
		return $sequence;
	}
	
	// Check all of the next positions
	foreach($movePatterns as $movePattern)
	{
		$nextPosition = ['x' => $newX + $movePattern['x'], 'y' => $newY + $movePattern['y']];
		
		$result = computePath($startPosition, $sequence, $landed, $nextPosition, $levelsDeep + 1);
		
		if(is_array($result))
		{
			return $result;
		}
	}
	
	// Ut oh. None of the positions worked.
	return false;
}

// Initialize landed
for($x = 0; $x < $sizeX; $x++)
{
	$landed[$x] = [];
	
	for($y = 0; $y < $sizeY; $y++)
	{
		$landed[$x][$y] = false;
	}
}

$result = null;

// Iterate over all possible starting positions
for($x = 0; $x < $sizeX; $x++)
{
	for($y = 0; $y < $sizeY; $y++)
	{
		$newPosition = ['x' => $x, 'y' => $y];
		
		$startPosition = $newPosition;
		
		$result = computePath($startPosition, $sequence, $landed, $newPosition, 0);
		
		if(is_array($result))
		{
			break 2;
		}
	}
}

echo 'Final Result: ';
var_dump($result);
