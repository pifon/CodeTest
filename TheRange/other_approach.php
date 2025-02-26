<?php

declare(strict_types=1);

class SpatialHashGrid
{
    private array $grid = [];
    private int $cellSize;

    public function __construct(int $cellSize = 10)
    {
        $this->cellSize = $cellSize;
    }

    /**
     * Computes the cell keys an object occupies based on its coordinates and size.
     */
    private function getCells(float $x, float $y, float $width, float $height): array
    {
        $cells = [];
        $startX = (int) floor($x / $this->cellSize);
        $startY = (int) floor($y / $this->cellSize);
        $endX = (int) floor(($x + $width) / $this->cellSize);
        $endY = (int) floor(($y + $height) / $this->cellSize);

        for ($i = $startX; $i <= $endX; $i++) {
            for ($j = $startY; $j <= $endY; $j++) {
                $cells[] = "$i,$j";
            }
        }
        return $cells;
    }

    /**
     * Adds an object to the spatial hash grid.
     */
    public function addObject(int $id, float $x, float $y, float $width, float $height): void
    {
        foreach ($this->getCells($x, $y, $width, $height) as $cell) {
            $this->grid[$cell][] = $id;
        }
    }

    /**
     * Detects collisions using spatial hashing.
     */
    public function detectCollisions(array $objects): array
    {
        $collisions = [];
        foreach ($this->grid as $cell => $ids) {
            if (count($ids) > 1) {
                foreach ($ids as $i => $id1) {
                    for ($j = $i + 1; $j < count($ids); $j++) {
                        $id2 = $ids[$j];
                        if ($this->checkIntersection($objects[$id1], $objects[$id2])) {
                            $collisions[] = [$id1, $id2];
                        }
                    }
                }
            }
        }
        return $collisions;
    }

    /**
     * Checks if two objects overlap.
     */
    private function checkIntersection(array $obj1, array $obj2): bool
    {
        return !(
            $obj1['x'] + $obj1['width'] <= $obj2['x'] || // obj1 is left of obj2
            $obj1['x'] >= $obj2['x'] + $obj2['width'] || // obj1 is right of obj2
            $obj1['y'] + $obj1['height'] <= $obj2['y'] || // obj1 is above obj2
            $obj1['y'] >= $obj2['y'] + $obj2['height']    // obj1 is below obj2
        );
    }
}

/**
 * Generates random objects and tests for collisions.
 */
function runTest(int $numObjects, int $gridSize, int $maxSize): void
{
    $spatialGrid = new SpatialHashGrid(10);
    $objects = [];

    for ($i = 1; $i <= $numObjects; $i++) {
        $x = rand(0, $gridSize);
        $y = rand(0, $gridSize);
        $width = rand(2, $maxSize);
        $height = rand(2, $maxSize);

        $objects[$i] = ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height];
        $spatialGrid->addObject($i, $x, $y, $width, $height);
    }

    // Detect and print collisions
    $collisions = $spatialGrid->detectCollisions($objects);
    if (empty($collisions)) {
        echo "No collisions detected.\n";
    } else {
        echo "Collisions detected:\n";
        print_r($collisions);
    }

    // Print visual representation
    printGrid($objects, $gridSize);
}

/**
 * Prints a visual representation of the plane.
 */
function printGrid(array $objects, int $gridSize): void
{
    $grid = array_fill(0, $gridSize, array_fill(0, $gridSize, '.'));

    foreach ($objects as $id => $obj) {
        for ($i = (int) $obj['x']; $i < (int) ($obj['x'] + $obj['width']); $i++) {
            for ($j = (int) $obj['y']; $j < (int) ($obj['y'] + $obj['height']); $j++) {
                $grid[$j][$i] = (string) $id;
            }
        }
    }

    foreach ($grid as $row) {
        echo implode(' ', $row) . "\n";
    }
}

// Run with 10 objects in a 50x50 grid with max size 8
runTest(10, 50, 8);
