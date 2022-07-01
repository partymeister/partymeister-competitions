<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\AccessKeys;

use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Models\AccessKey;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ExportController
 */
class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function csv()
    {
        $csv = '';

        foreach (AccessKey::all() as $row) {
            $csv .= "\"$row->access_key\"\n";
        }

        // Send the file content as the response
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'access-keys.csv');
    }

    /**
     * @return StreamedResponse
     */
    public function pdf()
    {
        $pdf = new \Partymeister\Competitions\PDF\AccessKey();
        $pdf->generate();

        //Send the file content as the response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output('accesskey.pdf', 'S');
        }, 'access-keys.pdf');
    }
}
