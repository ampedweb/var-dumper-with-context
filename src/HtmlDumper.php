<?php

namespace AmpedWeb\VarDumperWithContext;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as BaseDumper;

class HtmlDumper extends BaseDumper implements DumpContextInterface
{

    use ContextDumper;

    public function getContext($file, $line): string
    {
        return "<pre><small>{$file}:{$line}</small></pre>";
    }

}