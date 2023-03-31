<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\AccessKeys;

use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Motor\Core\Filter\Renderers\WhereRenderer;
use Partymeister\Competitions\Grids\AccessKeyGrid;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Services\AccessKeyService;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ExportController
 */
class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function csv()
    {
        $grid = new AccessKeyGrid(AccessKey::class);
        $service = AccessKeyService::collection($grid);

        $filter = $service->getFilter();

        $filter->add(new WhereRenderer('visitor_id'))
               ->setAllowNull(true)
               ->setValue(null);

        $grid->setFilter($filter);
        $paginator = $service->getPaginator();

        $csv = '';

        foreach ($paginator as $row) {
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
        $grid = new AccessKeyGrid(AccessKey::class);
        $service = AccessKeyService::collection($grid);

        $filter = $service->getFilter();

        $filter->add(new WhereRenderer('visitor_id'))
               ->setAllowNull(true)
               ->setValue(null);

        $grid->setFilter($filter);
        $paginator = $service->getPaginator();

        $pdf = new \Partymeister\Competitions\PDF\AccessKey($paginator);
        $pdf->generate();

        //Send the file content as the response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output('accesskey.pdf', 'S');
        }, 'access-keys.pdf');
    }
}
