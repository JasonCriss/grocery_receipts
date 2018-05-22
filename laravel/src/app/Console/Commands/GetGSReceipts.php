<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetGSReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:get_gs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $oClient = \Client::account('default');
        $aMailboxes = $oClient->getFolder('INBOX.receipts');
        $messages=$aMailboxes->getMessages();
        foreach ($messages as $message){
            $receipt = new \App\Receipt();
            $receipt->raw = $message->getHTMLBody();
            $receipt->store = 'GreenStar';
            $receipt->save();
            $message->moveToFolder('INBOX.receipts.Downloaded');
        }
    }
}
