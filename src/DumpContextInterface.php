<?php

namespace AmpedWeb\VarDumperWithContext;

interface DumpContextInterface
{

    public function getContext($file, $line): string;

}