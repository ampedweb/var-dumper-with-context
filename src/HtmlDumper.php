<?php

namespace AmpedWeb\VarDumperWithContext;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as BaseDumper;

class HtmlDumper extends BaseDumper implements DumpContextInterface
{
    use ContextDumper;

    /**
     * All of the href formats for common editors.
     *
     * @var array<string, string>
     */
    protected array $editorHrefs = [
        'atom' => 'atom://core/open/file?filename={file}&line={line}',
        'cursor' => 'cursor://file/{file}:{line}',
        'emacs' => 'emacs://open?url=file://{file}&line={line}',
        'idea' => 'idea://open?file={file}&line={line}',
        'macvim' => 'mvim://open/?url=file://{file}&line={line}',
        'netbeans' => 'netbeans://open/?f={file}:{line}',
        'nova' => 'nova://core/open/file?filename={file}&line={line}',
        'phpstorm' => 'phpstorm://open?file={file}&line={line}',
        'sublime' => 'subl://open?url=file://{file}&line={line}',
        'textmate' => 'txmt://open?url=file://{file}&line={line}',
        'vscode' => 'vscode://file/{file}:{line}',
        'vscode-insiders' => 'vscode-insiders://file/{file}:{line}',
        'vscode-insiders-remote' => 'vscode-insiders://vscode-remote/{file}:{line}',
        'vscode-remote' => 'vscode://vscode-remote/{file}:{line}',
        'vscodium' => 'vscodium://file/{file}:{line}',
        'xdebug' => 'xdebug://{file}@{line}',
    ];

    protected string|null $editor = null;
    protected string|null $localBasePath = null;
    protected string|null $remoteBasePath = null;

    public function getContext($file, $line): string
    {
        $href = $this->resolveSourceHref($file, $line);

        if ($href === null) {
            return "<pre><small>{$file}:{$line}</small></pre>";
        }

        return "<a href='{$href}'><pre><small>{$file}:{$line}</small></pre></a>";
    }

    public function setEditor(string $editor): void
    {
        $this->editor = $editor;
    }

    /**
     * The path to the project on the local/host machine where the editor is running.
     */
    public function setLocalBasePath(string $basePath): void
    {
        $this->localBasePath = $basePath;
    }

    /**
     * The path top the project on the remote server (eg. in Docker, the path inside the container).
     */
    public function setRemoteBasePath(string $basePath): void
    {
        $this->remoteBasePath = $basePath;
    }

    /**
     * Resolve the source href, if possible.
     *
     * @param  string  $file
     * @param  int|null  $line
     * @return string|null
     */
    protected function resolveSourceHref($file, $line): string|null
    {
        if ($this->editor === null) {
            return null;
        }

        $remoteBasePath = $this->remoteBasePath ?? (realpath(__DIR__ . '../../..') ?: null);
        $localBasePath = $this->localBasePath ?? (realpath(__DIR__ . '../../..') ?: null);

        $href = $this->editorHrefs[$this->editor] ?? sprintf('%s://open?file={file}&line={line}', $this->editor);

        if ($localBasePath && $remoteBasePath) {
            $file = str_replace($remoteBasePath, $localBasePath, $file);
        }

        return str_replace(
            ['{file}', '{line}'],
            [$file, is_null($line) ? 1 : $line],
            subject: $href,
        );
    }
}
