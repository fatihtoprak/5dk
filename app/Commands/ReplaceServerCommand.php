<?php
namespace App\Commands;

use DB;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

ini_set('memory_limit', '-1');

class ReplaceServerCommand extends Command
{

    protected $signature = 'replace:server';
    protected $description = 'Replacing server column by http header status';

    public function handle()
    {
        $videos = DB::table('vibe_videos')->where('server','d1')->get();
        $files  = file_get_contents('https://e1.5dk.org/storage/media/');

        preg_match_all('/<a href="(.*?)">(.*?)<\/a>/si', $files, $m);

        foreach ($videos as $key => $value) {

            $server = data_get($value, 'server');
            $file = data_get($value, 'f720p',null);
            if($file && data_get($m, 1))
            {
                $remoteFile = self::request($file,$m[1]);
            }
        }
    }

    private function request($file,$files)
    {
        $name  = str_replace('/storage/media/', '',$file);
        if(in_array($name, $files))
        {
            self::updateRow($name);
        }
    }

    private function updateRow($file)
    {
        return DB::table('vibe_videos')->where('f720p', '/storage/media/'.$file)->update(['server' => 'e1', 'tags' => 'd1-e1']);
    }

    public function schedule(Schedule $schedule): void
    {
    }
}
