<?php

namespace SolumDeSignum\WebArtisan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function cache;
use function cookie;
use function dump;
use function explode;
use function implode;
use function session;

class WebArtisanController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        return view('webartisan::index');
    }

    /**
     * @param Request $request
     *
     * RPC handler
     *
     * @return array
     */
    public function actionRpc(Request $request)
    {
        $options = json_decode($request->getContent());

        $paramsDirty = Str::after(
            $request->get('params')[0],
            ' '
        );

        /**
         * Iterating Arguments
         * Injecting them into request
         */
        foreach (explode(' ', $paramsDirty) as $argument) {
            $request->merge(
                [
                    Str::before($argument, '=') => Str::after($argument, '='),
                ]
            );
        }

        $i = $options->method;
        if ($i === 'artisan') {
            list($status, $output) = $this->runCommand(
                implode(' ', $options->params)
            );

            return ['result' => $output];
        }
    }

    /**
     * Runs console command.
     *
     * @param string $command
     *
     * @return array [status, output]
     */
    private function runCommand($command)
    {
        $cmd = base_path("artisan $command 2>&1");

        $handler = popen($cmd, 'r');
        $output = '';
        while (! feof($handler)) {
            $output .= fgets($handler);
        }
        $output = trim($output);
        $status = pclose($handler);

        return [$status, $output];
    }
}
