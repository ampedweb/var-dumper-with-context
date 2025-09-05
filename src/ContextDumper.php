<?php

namespace AmpedWeb\VarDumperWithContext;

use Symfony\Component\VarDumper\Cloner\Data;

trait ContextDumper
{
    /**
     * Finds the first file NOT in the /vendor/ directory from the
     * backtrace.
     */
    protected function getCaller(): ?array
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($backtrace as $trace) {
            if (!empty($trace['file']) && !empty($trace['line']) && !str_contains($trace['file'], '/vendor/')) {
                return $trace;
            }
        }

        return null;
    }

    public function dump(Data $data, $output = null, array $extraDisplayOptions = []): ?string
    {
        $caller = $this->getCaller();

        if (!$caller) return parent::dump($data, $output);

        $line = $this->getContext($caller['file'], $caller['line']);
        parent::echoLine($line, 0, '');
        return parent::dump($data, $output);
    }
}
