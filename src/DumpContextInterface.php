<?php

namespace AmpedWeb\VarDumperWithContext;

interface DumpContextInterface
{

    public function getContext(string $file, int | null $line): string;

}