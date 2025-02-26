<?php

declare(strict_types=1);

class Plane
{
    private array $planeMap = [];

    private array $items = [];

    private array $collisions = [];

    private bool $collisionsAllowed = false;

     public function allowCollisions(): void
     {
         $this->collisionsAllowed = true;
     }

    public function place(Shape $shape, int $rowX, int $columnY): void
    {
        $point = count($this->items) + 1;

        foreach ($shape->getSprite() as $row) {
            $colY = $columnY;
            foreach ($row as $mark) {
                if ($mark) {
                    if (!empty($this->planeMap[$rowX][$colY])) {
                        if (!$this->collisionsAllowed) {
                            echo sprintf("Cannot place shape #%d - collision with shape #%d at [%d, %d]", $point, $this->planeMap[$rowX][$colY], $rowX, $colY)."\n";
                            return;
                        }
                        $this->collisions[] = [
                            'shape1' => $this->planeMap[$rowX][$colY],
                            'shape2' => $point,
                            'x' => $rowX,
                            'y' => $colY
                        ];
                    }
                    $this->planeMap[$rowX][$colY] = $point;
                }
                $colY++;
            }
            $rowX++;
        }
        $this->items[$point] = $shape;
    }

    public function reportCollisions(): void
    {
        if (!$this->collisionsAllowed) {
           echo "No collisions allowed\n";
           return;
        }

        if (empty($this->collisions)) {
            echo "No collisions detected.\n";
            return;
        }

        echo "Collisions: \n" . print_r($this->collisions, true)."\n";
    }

}

readonly class Shape
{
    public function __construct(private array $sprite){
    }

    public function getSprite(): array
    {
        return $this->sprite;
    }
}

$cross = [
    [0,1,0],
    [1,1,1],
    [0,1,0]
];
$square = [
    [1,1,1],
    [1,0,1],
    [1,1,1]
];

$plane = new Plane();
//$plane->allowCollisions();

$crossShape = new Shape($cross);
$squareShape = new Shape($square);

$plane->place($crossShape, 0, 0); // first shape, OK - no collision
$plane->place($squareShape, 2, 2); // second shape, OK - no collision, touch, but no overlap
$plane->place($squareShape, 4, 4); // third shape - collision with previous square on [4,4]

/*
 *    [0][1][2][3][4][5][6]
 *[0]    1
 *[1] 1  1  1
 *[2]    1  2  2  2
 *[3]       2     2
 *[4]       2  2  3  3  3
 *[5]             3     3
 *[6]             3  3  3
 *
 */

$plane->reportCollisions();
