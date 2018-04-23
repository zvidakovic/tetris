<?php declare(strict_types=1);

namespace Tetris;

final class Screen
{
    /**
     * @var Tetromino[] List af all Tetrominos currently on screen.
     */
    private $tetrominos = [];
    /**
     * @var int Index of Tetromino that is currently controlled by user.
     */
    private $activeTetromino = 0;
    /**
     * @var int Number of lines cleared.
     */
    private $score = 0;

    public function recalculateAll(): void
    {
        /*
         * Check if current active Tetromino is at the bottom.
         * If yes, merge it and add a new one.
         */

    }

    /**
     * Loop through all Tetrominos and redraw them in playing screen.
     */
    public function redrawPlayingScreen(): void
    {
        foreach ($this->tetrominos as $index => $t) {
            for ($sy = 0; $sy < APP_PLAYING_SCREEN_LINES; $sy++) {
                for ($sx = 0; $sx < APP_PLAYING_SCREEN_COLUMNS; $sx++) {
                    $hit = false;
                    foreach ($t->points as $row) {
                        foreach ($row as $point) {
                            if ($sx === $point[0] && $sy === $point[1]) {
                                $hit = true;
                                echo $t->char;
                            }
                        }
                    }
                    if (!$hit) {
                        echo ' ';
                    }
                }
                echo APP_VERTICAL_WALL . PHP_EOL;
            }
        }
    }

    /**
     * Drop currently active Tetromino.
     * @param int $places
     */
    public function dropActiveTetromino(int $places = 1): void
    {
        if (!empty($this->tetrominos[$this->activeTetromino])) {
            $this->tetrominos[$this->activeTetromino]->moveDown($places);
        }
    }

    /**
     * @param int $key
     * @param int $places
     */
    public function playerMoveActiveTetromino(int $key, int $places = 1): void
    {
        if (!empty($this->tetrominos[$this->activeTetromino])) {
            switch ($key) {
                case 115: //s
                    $this->tetrominos[$this->activeTetromino]->moveDown($places);
                    break;
                case 97: //a
                    $this->tetrominos[$this->activeTetromino]->moveLeft($places);
                    break;
                case 100: //d
                    $this->tetrominos[$this->activeTetromino]->moveRight($places);
                    break;
            }
        }
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * Add new Tetromino to Screen and sets it as currently active Tetromino.
     * @param Tetromino $t
     */
    public function addNewTetromino(Tetromino $t): void
    {
        $this->tetrominos[] = $t;
        end($this->tetrominos);
        $this->activeTetromino = key($this->tetrominos);
        reset($this->tetrominos);
    }

    /**
     * Show some info.
     */
    public function showInfo(): void
    {
        echo str_repeat(APP_HORIZONTAL_WALL, APP_PLAYING_SCREEN_COLUMNS) . PHP_EOL;
        echo 'USAGE: ' . getMemoryUsage() . PHP_EOL;
        echo ' REAL: ' . getMemoryUsage(true) . PHP_EOL;
    }
}
