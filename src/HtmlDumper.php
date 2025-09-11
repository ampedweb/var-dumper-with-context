<?php

namespace AmpedWeb\VarDumperWithContext;

use http\Encoding\Stream;
use Symfony\Component\VarDumper\Dumper\HtmlDumper as BaseDumper;

class HtmlDumper extends BaseDumper implements DumpContextInterface
{
    use ContextDumper;

    /**
     * All the href formats for common editors.
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
    protected string|null $linkColor = null;

    public function getContext(string $file, int|null $line): string
    {
        $href = $this->resolveSourceHref($file, $line);

        if ($href === null) {
            return "<pre><small>{$file}:{$line}</small></pre>";
        }

        $style = '';
        if($this->linkColor) {
            $style = 'style="color: ' . $this->linkColor . '; text-decoration: underline;"';
        }

        return "<a href='{$href}' {$style}><pre><small>{$file}:{$line}</small></pre></a>";
    }

    public function setEditor(?string $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    public function setLinkColor(?string $color): static
    {
        $this->linkColor = $color;

        return $this;
    }

    /**
     * The path to the project on the local/host machine where the editor is running.
     */
    public function setLocalBasePath(?string $basePath): static
    {
        $this->localBasePath = $basePath;

        return $this;
    }

    /**
     * The path top the project on the remote server (eg. in Docker, the path inside the container).
     */
    public function setRemoteBasePath(?string $basePath): static
    {
        $this->remoteBasePath = $basePath;

        return $this;
    }

    /**
     * Resolve the source href, if possible.
     *
     * @param  string  $file
     * @param  int|null  $line
     * @return string|null
     */
    protected function resolveSourceHref(string $file, int|null $line): string|null
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
