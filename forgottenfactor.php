<!DOCTYPE HTML>

<html>

	<?php
		//forgotten factor is a game where the player moves from number to number based on 'steps'. One number steps to another based on it's number of prime factors, if it has a odd number it steps forward that numer, if it has an even, it steps back that number. The player can move to the number that their current number stepps to and any numbers that step to it. The player can also transision to a number if it is ajacent to their current number and it it is not posible for the player to get to the other number in any other way through via any other numbers (excluding other transisions).

		//returns the position the player is now on, it retrieves the posted data and takes account of how the player tried to move
		function newPosition()
		{
			return aMove(getPosition(), getNumberMove());
		}

		//returns the move attempted by the player. If it is not numerical it returns the last position
		function getNumberMove()
		{
			$move = getMove();
			if (is_int($move))
			{
				return $move;
			}
			else
			{
				return getPosition();
			}
		}

		//returns the move attempted by the player, which is posted to the page
		function getMove()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				$move = htmlspecialchars($_POST["move"]);
				if (ctype_digit($move))
				{
					return (int)$move;
				}
				else
				{
					return $move;
				}
			}
			else
			{
				return 1;
			}
		}

		//returns the last position the player was at, which is posted to the page
		function getPosition()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				return (int)htmlspecialchars($_POST["position"]);
			}
			else
			{
				return 1;
			}
		}

		//returns where the player ends up, given a starting position and an attempted move
		function aMove($position, $move)
		{
			if ($move < 1)
			{
				return 1;
			}
			elseif (isValid($position, $move))
			{
				return $move;
			}
			elseif ($position == $move)
			{
				return $move;
			}
			else
			{
				return 1;
			}
		}

		//returns true for a valid standard move, given a starting position and a move
		function isValid($position, $move)
		{
			if (isStep($position, $move) or isTransition($position, $move))
			{
				return True;
			}
			else
			{
				return False;
			}
		}

		//returns true for a valid translation move, given a starting position and a move
		function isTransition($position, $move)
		{
			if (pow($position - $move, 2) == 1 and isNotIn(array_pop(journey($move)), journey($position)))//tests if the numbers are one away from each other and if there journeys end in the same loop
			{
				return True;
			}
			else
			{
				return False;
			}
		}

		//returns an array of intigers, staring with the provided number and each subsiquent number being the only valid step from the one before. (a number steps to another based on its prime factors, if it has a odd number it steps forward that numer, if it has an even, it steps back that number)
		function journey($number)
		{
			$record = [];
			$location = $number;
			while (isNotIn($location, $record))
			{
				$record[] = $location;
				$location = step($location);
			}
			return $record;
		}

		//returns true if position steps to move or if move steps to position. (a number steps to another based on its prime factors, if it has a odd number it steps forward that numer, if it has an even, it steps back that number)
		function isStep($position, $move)
		{
			if (step($position) == $move or step($move) == $position)
			{
				return True;
			}
			else
			{
				return False;
			}
		}

		//returns the number position steps to
		function step($position)
		{
			$length = primeFactors($position);
			if ($length % 2 == 0)
			{
				return $position - $length;
			}
			else
			{
				return $position + $length;
			}
		}

		//returns the number of prime factors number has
		function primeFactors($number)
		{
			$factors = 0;
			$aFactor = 2;
			while (1 < $number)
			{
				while ($number % $aFactor == 0)
				{
					$factors = $factors + 1;
					$number /= $aFactor;
				}
				$aFactor = $aFactor + 1;
				if ($number < $aFactor * $aFactor)
				{
					if (1 < $number)
					{
						$factors = $factors + 1;
						break;
					}
				}
			}
			return $factors;
		}

		//searches an array for value, if PRESENT returns FALSE else returns True
		function isNotIn($value, $array)
		{
			foreach ($array as &$search)
			{
				if ($search == $value)
				{
					return False;
				}
			}
			
			return True;
		}

		$currentPosition = newPosition();

	?>

	<head>

		<title>Forgotten Factor | Prime Number Math Game</title>

		<meta name="description" content="Forgotten factor is a game where numbers form mazes and you are trying to navigate through them to the highest number you can. The mazes are made up of paths between numbers. It is a fun math game base on prime numbers.">

		<meta name="keywords" content="prime, number, math, game, maze, factor, product, multiply, divide">

		<meta name="author" content="Joshua Boulton">

		<html lang="en">

		<style>
			body
				{
				font-family: sans-serif;
				color: black;
				background-color: lightgrey;
			}
			h1
			{
					text-align: center;
			}
			header, article, footer
			{
				display: block;
				width: 80%;
				margin-left: auto;
				margin-right: auto;
			}
		</style>

	</head>

	<body>

		<header>

			<aside>

			<h3>Forgotten Factor</h3>

			<p>You are in a maze of numbers. Type a number to travel to it. If there is a path or bridge to that number from the one you're on, you will travel to it. If not, you are lost and will go back to 1. How far can you get?</p>

			</aside>

			<article>
			
				<form action="#" method="post">

					<p>You are at:  <?php echo $currentPosition; ?></p>

					<input type="hidden" name="position" value="<?php echo $currentPosition ?>">

					<input type="text" name="move" autofocus>

					<input type="submit" value="Move">

				</form>
			
			</article>
			
		</header>
			
		<article>
			
			<h1>Forgotten Factor</h1>

			<p>Forgotten factor is a game where numbers form mazes and you are trying to navigate through them to the highest number you can. The mazes are made up of paths between numbers. There are two types of paths.</p>

			<h3>Forward paths</h3>

			<p>Every number has exactly one forward path. Forward paths and based on the number of prime factors a number has. If it has an even number of prime factors, the path takes you lower by the number of prime factors. If it has an odd number of prime factors, it takes you higher by that amount.</p>

			<h3>Backwards paths</h3>

			<p>You can also follow forward paths backwards. If a different number has a forward path to the number you are on, you can travel back to that number as well.</p>

			<h3>Mazes</h3>

			<p>Following these paths, you will soon discover that you cannot get very far. However many paths you travel down there will only be a limited set of numbers you can travel to. This is because you are trapped in a single maze. If you want to get to any number not in your maze, you will have to cross to a new one.</p>

			<h3>Bridges</h3>

			<p>Fortunately, there are bridges that will let you travel between mazes. Bridges exist between numbers that are one away from each other, but only when they are in different mazes. To know that there is a bridge to the number next to you, to will need to be sure that there is no other way of traveling to it inside the maze you are in.</p>

			<h3>Exploring</h3>

			<p>To explore, you type the number you want to travel to and click 'Move'. You can only travel to numbers that are connected to yours by paths or bridges. If you type a number that you cannot travel to, you are lost and go back to 1.</p>

			<p>Either try to get as high as you can or just explore. Whatever you do, don't get lost!</p>

			<aside>

				<h4>Prime factors</h4>

				<p>The prime factors of a number comprise the unique list of prime numbers that multiply together to make it.</p>

				<h4>Prime number</h4>

				<p>A prime number is a number that the only whole numbers you can multiply to make it are itself and 1 (except that 1 isn't prime).</p>

			</aside>

		</article>

		<footer>

			<p>&copy; 2015 Joshua Boulton</p>

		</footer>

	</body>

</html>