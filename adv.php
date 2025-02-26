<?php

declare(strict_types=1);

/**
 * QuadTree Node Class
 */
class QuadTree
{
    private int $capacity;
    private array $shapes = [];
    private ?array $bounds;
    private ?array $children = null;

    public function __construct(int $capacity, array $bounds)
    {
        $this->capacity = $capacity;
        $this->bounds = $bounds;
    }

    /**
     * Inserts a shape into the QuadTree
     */
    public function insert(Shape $shape): bool
    {
        if (!$this->intersectsBoundingBox($shape)) {
            return false; // Shape is out of bounds
        }

        if (count($this->shapes) < $this->capacity) {
            $this->shapes[] = $shape;
            return true;
        }

        if ($this->children === null) {
            $this->subdivide();
        }

        foreach ($this->children as $child) {
            if ($child->insert($shape)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Subdivide the QuadTree into four equal quadrants
     */
    private function subdivide(): void
    {
        $x = $this->bounds['x'];
        $y = $this->bounds['y'];
        $w = $this->bounds['width'] / 2;
        $h = $this->bounds['height'] / 2;

        $this->children = [
            new QuadTree($this->capacity, ['x' => $x,       'y' => $y,       'width' => $w, 'height' => $h]),
            new QuadTree($this->capacity, ['x' => $x + $w, 'y' => $y,       'width' => $w, 'height' => $h]),
            new QuadTree($this->capacity, ['x' => $x,       'y' => $y + $h, 'width' => $w, 'height' => $h]),
            new QuadTree($this->capacity, ['x' => $x + $w, 'y' => $y + $h, 'width' => $w, 'height' => $h]),
        ];
    }

    /**
     * Check if the shape's bounding box intersects the QuadTree bounds
     */
    private function intersectsBoundingBox(Shape $shape): bool
    {
        [$minX, $maxX, $minY, $maxY] = $shape->getBoundingBox();
        return !(
            $maxX < $this->bounds['x'] ||
            $minX > $this->bounds['x'] + $this->bounds['width'] ||
            $maxY < $this->bounds['y'] ||
            $minY > $this->bounds['y'] + $this->bounds['height']
        );
    }

    /**
     * Retrieve all possible colliding shapes
     */
    public function queryCollisions(Shape $shape): array
    {
        $collisions = [];
        foreach ($this->shapes as $otherShape) {
            if ($shape !== $otherShape && Shape::doPolygonsCollide($shape->getPoints(), $otherShape->getPoints())) {
                $collisions[] = $otherShape;
            }
        }

        if ($this->children !== null) {
            foreach ($this->children as $child) {
                $collisions = array_merge($collisions, $child->queryCollisions($shape));
            }
        }

        return $collisions;
    }
}

/**
 * Shape Class (Stores Points of a Polygon)
 */
class Shape
{
    private array $points;

    public function __construct(array $points)
    {
        $this->points = $points;
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * Get bounding box for optimization
     */
    public function getBoundingBox(): array
    {
        $xs = array_column($this->points, 'x');
        $ys = array_column($this->points, 'y');

        return [min($xs), max($xs), min($ys), max($ys)];
    }

    /**
     * SAT (Separating Axis Theorem) Collision Detection
     */
    public static function doPolygonsCollide(array $polygon1, array $polygon2): bool
    {
        foreach ([$polygon1, $polygon2] as $polygon) {
            for ($i = 0; $i < count($polygon); $i++) {
                $j = ($i + 1) % count($polygon);
                $axis = [
                    'x' => $polygon[$j]['y'] - $polygon[$i]['y'],
                    'y' => $polygon[$i]['x'] - $polygon[$j]['x']
                ];

                $projections1 = self::projectPolygon($polygon1, $axis);
                $projections2 = self::projectPolygon($polygon2, $axis);

                if ($projections1['max'] < $projections2['min'] || $projections2['max'] < $projections1['min']) {
                    return false;
                }
            }
        }
        return true;
    }

    private static function projectPolygon(array $polygon, array $axis): array
    {
        $min = $max = ($polygon[0]['x'] * $axis['x'] + $polygon[0]['y'] * $axis['y']);
        foreach ($polygon as $point) {
            $projection = ($point['x'] * $axis['x'] + $point['y'] * $axis['y']);
            if ($projection < $min) $min = $projection;
            if ($projection > $max) $max = $projection;
        }
        return ['min' => $min, 'max' => $max];
    }
}

/**
 * Example Usage
 */

// Define a "moon-like" shape using an array of points
$moon = new Shape([
    ['x' => 10, 'y' => 5],  // Outer crescent points
    ['x' => 12, 'y' => 8],
    ['x' => 15, 'y' => 12],
    ['x' => 17, 'y' => 10],
    ['x' => 13, 'y' => 6],  // Inner crescent (subtracted area)
]);

$square = new Shape([
    ['x' => 14, 'y' => 9], ['x' => 18, 'y' => 9], ['x' => 18, 'y' => 13], ['x' => 14, 'y' => 13]
]);

$quadTree = new QuadTree(4, ['x' => 0, 'y' => 0, 'width' => 50, 'height' => 50]);

$quadTree->insert($moon);
$quadTree->insert($square);

$collisions = $quadTree->queryCollisions($moon);
echo "Collisions Found: " . count($collisions) . "\n";
foreach ($collisions as $collidedShape) {
    echo "Collision detected with a shape at " . json_encode($collidedShape->getPoints()) . "\n";
}
