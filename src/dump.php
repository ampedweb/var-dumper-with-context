<?php

namespace AmpedWeb\VarDumperWithContext;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

    if ($dumper instanceof HtmlDumper) {
        $dumper->setRemoteBasePath($_ENV['VAR_DUMPER_CONTEXT_REMOTE_BASE_PATH'] ?? null)
               ->setLocalBasePath($_ENV['VAR_DUMPER_CONTEXT_LOCAL_BASE_PATH'] ?? null)
               ->setEditor($_ENV['VAR_DUMPER_CONTEXT_EDITOR'] ?? null)
               ->setLinkColor($_ENV['VAR_DUMPER_CONTEXT_LINK_COLOR'] ?? null);
    }

    $dumper->dump($cloner->cloneVar($var));
});