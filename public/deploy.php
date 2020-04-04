<?php

declare(strict_types=1);
class Deploy
{
    private const ACCESS_KEY = '8c20ffeec06fcd5744a8fa38ef8c03a1';

    private const HTML_EOL = '<br/>';

    private string $output = '';

    private array $commands = [
        'whoami',
        'sudo git pull',
        '/usr/local/bin/yarn encore dev',
    ];

    public function run(): string
    {
        if (!$this->checkAccess()) {
            $this->output .= 'Access denied' . self::HTML_EOL;
            return $this->output;
        }

        $this->runCommands();
        return $this->output;
    }

    public function checkAccess(): bool
    {
        $key = $_GET['key'] ?? null;

        return $key === self::ACCESS_KEY;
    }

    public function runCommands(): void
    {
        foreach ($this->commands as $command) {
            $tmp = shell_exec("$command 2>&1");
            $this->output .= "{$command} output:" . self::HTML_EOL;
            $this->output .= htmlentities(trim($tmp)) . self::HTML_EOL . self::HTML_EOL;
        }
    }
}

echo (new Deploy())->run();