<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class SendMailRemindCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMail:remindTask';

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
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user){
            $cards = Card::where('user_assign_id', $user->id)->get();
            if (count($cards) > 0){
                $text = '';
                foreach ($cards as $card){
                    $text.= '<li>'.$card->title.'</li>';
                }

                $details = [
                    'title' => 'M.Work - Nhắc nhở công việc hết hạn trong ngày',
                    'body' => '<p>Hôm nay (Ngày '. date_format(new \DateTime(),"d-m-Y").') bạn có các task sau đây sẽ hết hạn trong ngày: </p><ul>'.$text.'</ul>'
                ];

                \Mail::to($user->email)->send(new \App\Mail\SendMail($details));
            }
        }

    }
}
