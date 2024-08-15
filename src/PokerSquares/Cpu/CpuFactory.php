<?php

namespace App\PokerSquares\Cpu;

use Exception;

/**
 * Factory class for creating cpu intel classes
 */
class CpuFactory
{
    /**
     * Get cpu intel of relevant level (name of the class)
     *
     * @param int $level - cpu level
     * @throws Exception
     * @return string - class name
     */
    public static function getCpuIntel(int $level): string
    {
        $cpuClassName = __NAMESPACE__ . '\\Cpu' . $level;

        if (!class_exists($cpuClassName)) {
            throw new Exception("CPU class '$cpuClassName' does not exist.");
        }

        return $cpuClassName;
    }
}
