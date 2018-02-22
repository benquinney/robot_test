<?php
/**
Robot class can be constructed with a string or array for initial location and a string or array that defines the size of the board.

The robot must be placed on the baord before it is able to be moved

Create a new Robot object $robot = new Robot();
$robot->place('0,0,NORTH'); - will place the robot
$robot->move(); - will move the robot one step in the direction that it is facing provided it will not go outside the allowed area of the board
$robot->left(); - will turn the robot 90 degrees counter clockwise
$robot->right(); - will turn the robot 90 degrees clockwise
$robot->board_size('10,10'); - will resize the board

I have also put in some error reporting if the robot is place outside the available board and if the move tries to exit the board
**/

class Robot
{
	public $location = array(
		"X" => null, 
		"Y" => null, 
		"direction" => null
	);

	public $board = array(
		"height" => 5,
		"width" => 5
	);

	public $directions = array(
		"NORTH",
		"SOUTH",
		"EAST",
		"WEST"
	);

	public $placed = false;

	function __construct($location_init, $board_init)
	{
		if($location_init)
		{
			$this->location = $this->_convert_command($location_init);
			$this->location['direction'] = strtoupper($this->location['direction']);
		}	


		if($board_init)
			$this->board = $this->_convert_board($board_init);
	}

	public function place($args)
	{
		$args = $this->_convert_command($args);

		if($this->_check_location($args))
		{
			$args['direction'] = strtoupper($args['direction']);
			$this->location = $args;

			$this->placed = true;

			return true;
		}
		else
		{
			echo "Invalid placement: X => ".$args['X'].", Y => ".$args['Y'].", Direction => ".$args['direction']."<br/>";
		}
	}

	public function move()
	{
		if(!$this->placed)
			return false;

		$new_location = $this->location;
		switch ($new_location['direction']) {
			case 'NORTH':
				$new_location['Y']++;
			break;
			case 'SOUTH':
				$new_location['Y']--;
			break;
			case 'EAST':
				$new_location['X']++;
			break;
			case 'WEST':
				$new_location['X']--;
			break;
		}

		if($this->_check_location($new_location))
		{
			$this->location = $new_location;
		}
		else
		{
			echo "Invalid move: X => ".$new_location['X'].", Y => ".$new_location['Y'].", Direction => ".$new_location['direction']."<br/>";
		}
	}

	public function left()
	{
		if(!$this->placed)
			return false;

		switch ($this->location['direction']) {
			case 'NORTH':
				$this->location['direction'] = 'WEST';
			break;
			case 'SOUTH':
				$this->location['direction'] = 'EAST';
			break;
			case 'EAST':
				$this->location['direction'] = 'NORTH';
			break;
			case 'WEST':
				$this->location['direction'] = 'SOUTH';
			break;
		}
	}

	public function right()
	{
		if(!$this->placed)
			return false;

		switch ($this->location['direction']) {
			case 'NORTH':
				$this->location['direction'] = 'EAST';
			break;
			case 'SOUTH':
				$this->location['direction'] = 'WEST';
			break;
			case 'EAST':
				$this->location['direction'] = 'SOUTH';
			break;
			case 'WEST':
				$this->location['direction'] = 'NORTH';
			break;
		}
	}

	public function board_size($args)
	{
		$args = $this->_convert_board($args);
		$this->board = $args;
	}

	public function report()
	{
		echo $this->location['X'].", ".$this->location['Y'].", ".$this->location["direction"]."<br/>";
	}

	private function _check_location($args)
	{
		if($args['X'] < $this->board['height'] && $args['Y'] < $this->board['width'] && in_array($args['direction'], $this->directions))
		{
			
			return true;
		}

		return false;
	}

	private function _convert_command($args)
	{
		if(array_key_exists("X", $args))
			return $args;

		$arg_pieces = explode(",", $args);
		if(count($arg_pieces) == 3)
		{
			$args = array(
				"X" => $arg_pieces[0],
				"Y" => $arg_pieces[1],
				"direction" => strtoupper($arg_pieces[2])
			);
		}
		return $args;
	}

	private function _convert_board($args)
	{
		if(array_key_exists("height", $args))
			return $args;

		$arg_pieces = explode(",", $args);
		if(count($arg_pieces) == 2)
		{
			$args = array(
				"height" => $arg_pieces[0],
				"width" => $arg_pieces[1]
			);
		}
		return $args;
	}
}

echo '<h1>Robot</h1>';

// Initialise robot
$robot = new Robot();

// Example instructions A
echo "Example instructions A<br/>";
$robot->place("0,0,NORTH");
$robot->report();
$robot->move();
$robot->report();

// Example instructions B
echo "Example instructions B<br/>";
$robot->place("0,0,NORTH");
$robot->report();
$robot->left();
$robot->report();

// Example instructions C
echo "Example instructions C<br/>";
$robot->place("1,2,EAST");
$robot->report();
$robot->move();
$robot->move();
$robot->left();
$robot->move();
$robot->report();