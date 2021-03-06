<?php declare(strict_types=1);

define('APP_LOG', __DIR__ . 'log' . DIRECTORY_SEPARATOR . date('Y-m-d-H-i-s') . '.log');
define('APP_NAME', 'Tetris');
define('APP_SLUG', 'tetris');
define('APP_VERSION_MAJOR', 1);
define('APP_VERSION_MINOR', 0);
define('APP_COLUMNS', (int)exec('tput cols'));
define('APP_LINES', (int)exec('tput lines'));
define('APP_PLAYING_SCREEN_COLUMNS_OFFSET', 0); //TODO: Implement playing screen offset
define('APP_PLAYING_SCREEN_LINES_OFFSET', 0); //TODO: Implement playing screen offset
define('APP_PLAYING_SCREEN_COLUMNS', 10);
define('APP_PLAYING_SCREEN_LINES', 24);
define('APP_VERTICAL_WALL', '|');
define('APP_HORIZONTAL_WALL', '─');

/*
 * Adjust some console settings:
 * 1. Set minimum character limit for complete command (-icanon min N)
 * 2. Set no delay/timeout on any input (time 0)
 * To know more, please consult `man stty`.
 */
exec('stty -icanon min 0 time 0');

/**
 * @param string $value Variable that will contain input value.
 * @return bool
 */
function readStream(string &$value = null): bool
{
    $read = [STDIN];
    $write = [];
    $except = [];
    $numberOfChangedStreams = stream_select($read, $write, $except, 0);
    if ($numberOfChangedStreams === false) {
        return false;
    }
    if ($numberOfChangedStreams > 0) {
        $value = stream_get_line(STDIN, 1);
        if (mb_strlen($value) > 0) {
            return true;
        }
    }
    return false;
}

/**
 * Tries to clear the whole screen. No matter what.
 * http://www.tldp.org/HOWTO/Bash-Prompt-HOWTO/x361.html
 */
function clearScreen(): void
{
    if (!defined('CLEAR_SCREEN_STRING')) {
        $cls = '';
        for ($i = 0; $i < APP_LINES; $i++) {
            /*
             * \r      RETURN TO BEGINNING OF LINE
             * \033[K  ERASE TO THE END OF LINE
             * \033[1A MOVE UP ONE LINE
             * \r      RETURN TO BEGINNING OF LINE
             * \033[K  ERASE TO THE END OF LINE
             * \r      RETURN TO BEGINNING OF LINE
             */
            $cls .= "\r\033[K\033[1A\r\033[K\r";
        }
        define('CLEAR_SCREEN_STRING', $cls);
    }
    echo CLEAR_SCREEN_STRING;
}

/**
 * Tries to reset pointer to top left corner. No matter what.
 */
function resetPointer(): void
{
    if (!defined('RESET_POINTER_STRING')) {
        $rps = '';
        for ($i = 0; $i < APP_LINES; $i++) {
            /*
             * \r      RETURN TO BEGINNING OF LINE
             * \033[K  ERASE TO THE END OF LINE
             * \033[1A MOVE UP ONE LINE
             * \r      RETURN TO BEGINNING OF LINE
             * \033[K  ERASE TO THE END OF LINE
             * \r      RETURN TO BEGINNING OF LINE
             */
            $rps .= "\r\033[1A";
        }
        define('RESET_POINTER_STRING', $rps);
    }
    echo RESET_POINTER_STRING;
}

/**
 * Get nicely formatted memory usage.
 * @param bool $realUsage
 * @return string
 */
function getMemoryUsage(bool $realUsage = false): string
{
    $memory = memory_get_usage($realUsage);
    if ($memory < 1024) {
        return $memory . 'B';
    }
    if ($memory < 1048576) {
        return round($memory / 1024, 2) . 'kB';
    }
    return round($memory / 1048576, 2) . 'MB';
}
