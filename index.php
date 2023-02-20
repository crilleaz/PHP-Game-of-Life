<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Game of Life</title>
	

</head>
<body>
	<style>
	.grid {
	  display: grid;
	  grid-template-columns: repeat(<?php echo $grid_size; ?>, 20px);
	  grid-template-rows: repeat(<?php echo $grid_size; ?>, 20px);
	  grid-gap: 1px;
	  margin: 10px 0;
	}
	.cell {
	  display: inline-block;
	  width: 20px;
	  height: 20px;
	  margin: 1px;
	  background-color: white;
	  border: 1px solid black;
	}
	body {
	  margin: 0;
	  padding: 0;
	}
		.alive {
	  background-color: #888;
	}
	
	#grid-container {
     transform: scale(0.4);
     transform-origin: 0% 0% 0px;
	}

	</style>
	    <div id="controls">
        <button id="start-button">Start</button>
        <button id="stop-button">Stop</button>
        <div>
            Cells born: <span id="cells-born">0</span>
        </div>
        <div>
            Cells died: <span id="cells-died">0</span>
        </div>
		<div>
		Click dead or alive cell and drag to feed
		</div>
    </div>
    <div id="grid-container">

	<br>
	  
        <?php
        // Define the grid size
        $grid_size = 100;

        // Initialize the grid array
        $grid = array();
        for ($i = 0; $i < $grid_size; $i++) {
            for ($j = 0; $j < $grid_size; $j++) {
                $grid[$i][$j] = 'dead';
            }
        }

        // Randomly set some cells to 'alive'
        for ($i = 0; $i < $grid_size; $i++) {
            for ($j = 0; $j < $grid_size; $j++) {
                $rand_num = rand(0, 9);
                if ($rand_num < 3) {
                    $grid[$i][$j] = 'alive';
                }
            }
        }

        // Display the grid cells
        for ($i = 0; $i < $grid_size; $i++) {
            for ($j = 0; $j < $grid_size; $j++) {
                $cell_id = $i . '_' . $j;
                $cell_class = $grid[$i][$j] == 'alive' ? 'cell alive' : 'cell';
                echo "<div class=\"$cell_class\" id=\"$cell_id\"></div>";
            }
            echo '<br>';
        }
        ?>
    </div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			// Add click listener to cells
			$('.cell').click(function() {
				console.log('Clicked on cell');
				$(this).toggleClass('alive');
			});

			// Add click listener to start button
			var intervalId;
			$('#start-button').click(function() {
				console.log('Start button clicked');
				clearInterval(intervalId); // Clear any existing intervals
				intervalId = setInterval(updateGrid, 100); // Start a new interval
			});
						// Add click listener to stop button
			$('#stop-button').click(function() {
				console.log('Stop button clicked');
				clearInterval(intervalId); // Clear the interval to stop the game
			});
			
		});

	function updateGrid() {
		// Get the current state of the grid
		var gridState = {};
		$('.cell').each(function() {
			var cellId = $(this).attr('id');
			var cellState = $(this).hasClass('alive') ? 'alive' : 'dead';
			gridState[cellId] = cellState;
		});

		// Initialize counters for cells born and cells died
		var cellsBorn = 0;
		var cellsDied = 0;

		// Update the grid based on the game of life rules
		var newGridState = {};
		$('.cell').each(function() {
			var cellId = $(this).attr('id');
			var i = parseInt(cellId.split('_')[0]);
			var j = parseInt(cellId.split('_')[1]);
			var aliveNeighbors = 0;
			for (var di = -1; di <= 1; di++) {
				for (var dj = -1; dj <= 1; dj++) {
					if (di == 0 && dj == 0) {
						continue;
					}
					var ni = i + di;
					var nj = j + dj;
					if (ni < 0 || nj < 0 || ni >= 100 || nj >= 100) {
						continue;
					}
					if (gridState[ni+'_'+nj] == 'alive') {
						aliveNeighbors++;
					}
				}
			}
			if (gridState[cellId] == 'alive') {
				if (aliveNeighbors < 2 || aliveNeighbors > 3) {
					newGridState[cellId] = 'dead';
					cellsDied++;
				} else {
					newGridState[cellId] = 'alive';
				}
			} else {
				if (aliveNeighbors == 3) {
					newGridState[cellId] = 'alive';
					cellsBorn++;
				} else {
					newGridState[cellId] = 'dead';
				}
			}
		});

		// Update the grid with the new state
		for (var cellId in newGridState) {
			var newCellState = newGridState[cellId];
			if (newCellState == 'alive') {
				$('#' + cellId).addClass('alive');
			} else {
				$('#' + cellId).removeClass('alive');
			}
		}

		// Update the counter display
		$('#cells-born').text(cellsBorn);
		$('#cells-died').text(cellsDied);
	}

	let isMouseDown = false;
	let isCellAliveOnMouseDown = false;

	$('.cell').mousedown(function() {
		isMouseDown = true;
		isCellAliveOnMouseDown = $(this).hasClass('alive');
		$(this).toggleClass('alive');
	});

	$('.cell').mouseenter(function() {
		if (isMouseDown) {
			if (isCellAliveOnMouseDown) {
				$(this).addClass('alive');
			} else {
				$(this).removeClass('alive');
			}
		}
	});

	$(document).mouseup(function() {
		isMouseDown = false;
	});

	</script>

</body>
</html>
