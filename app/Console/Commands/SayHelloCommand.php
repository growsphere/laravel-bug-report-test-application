<?php

namespace App\Console\Commands;

use App\BugReport\ClassToBeInjected;
use Illuminate\Console\Command;

class SayHelloCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:say-hello';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected ClassToBeInjected $injected)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info($this->injected->sayHello());
    }
}
