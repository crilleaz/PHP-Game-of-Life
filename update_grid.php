<?php
// Get the current grid state from the POST data
$grid_state = json_decode($_POST['grid_state'], true);

// Define the grid size
$grid_size = 50;

// Initialize the new grid array
$new_grid = array();
for ($i = 0; $i < $grid_size; $i++) {
    for ($j = 0; $j < $grid_size; $j++) {
        $new_grid[$i][$j] = 'dead';
    }
}

// Iterate over the cells in the grid
for ($i = 0; $i < $grid_size; $i++) {
    for ($j = 0; $j < $grid_size; $j++) {
        // Count the number of alive neighbors for this cell
        $alive_neighbors = 0;
        for ($x = -1; $x <= 1; $x++) {
            for ($y = -1; $y <= 1; $y++) {
                // Skip the current cell
                if ($x == 0 && $y == 0) {
                    continue;
                }

                // Get the state of the neighbor cell
                $neighbor_i = $i + $x;
                $neighbor_j = $j + $y;
                if ($neighbor_i < 0 || $neighbor_i >= $grid_size || $neighbor_j < 0 || $neighbor_j >= $grid_size) {
                    // Neighbor cell is outside the grid, count it as dead
                    $neighbor_state = 'dead';
                } else {
                    // Neighbor cell is inside the grid, get its state
                    $neighbor_state = $grid_state[$neighbor_i . '_' . $neighbor_j];
                }

                // Count the neighbor cell if it's alive
                if ($neighbor_state == 'alive') {
                    $alive_neighbors++;
                }
            }
        }

        // Apply the Game of Life rules
        if ($grid_state[$i . '_' . $j] == 'alive') {
            if ($alive_neighbors < 2 || $alive_neighbors > 3) {
                // Any live cell with fewer than two live neighbours dies, as if by underpopulation.
                // Any live cell with more than three live neighbours dies, as if by overpopulation.
                $new_grid[$i][$j] = 'dead';
            } else {
                // Any live cell with two or three live neighbours lives on to the next generation.
                $new_grid[$i][$j] = 'alive';
            }
        } else {
            if ($alive_neighbors == 3) {
                // Any dead cell with exactly three live neighbours becomes a live cell, as if by reproduction.
                $new_grid[$i][$j] = 'alive';
            } else {
                // Any dead cell with fewer than three live neighbours stays dead.
                $new_grid[$i][$j] = 'dead';
            }
        }
    }
}

// Send the new grid state back to the client
echo json_encode($new_grid);
