<?php

namespace App\PokerSquares\Cpu;

/**
 * Factory class for creating cpu intel classes
 */
class CpuFactory
{
    /**
     * Get cpu intel of relevant level
     * 
     * @param int $level - cpu level
     * @return CpuLogicInterface
     */
    public static function getCpuIntel(int $level): CpuLogicInterface
    {
        $cpuClassName = __NAMESPACE__ . '\\Cpu' . $level;

        if (!class_exists($cpuClassName)) {
            throw new \Exception("CPU class '$cpuClassName' does not exist.");
        }

        return new $cpuClassName();
    }
}
