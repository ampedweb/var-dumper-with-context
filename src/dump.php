<?php

namespace AmpedWeb\VarDumperWithContext;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

    if ($dumper instanceof HtmlDumper) {
        $dumper->setRemoteBasePath($_ENV('VAR_DUMPER_REMOTE_BASE_PATH') ?? null);
        $dumper->setLocalBasePath($_ENV('VAR_DUMPER_LOCAL_BASE_PATH') ?? null);
        $dumper->setEditor($_ENV('VAR_DUMPER_EDITOR') ?? null);
    }

    $dumper->dump($cloner->cloneVar($var));
});