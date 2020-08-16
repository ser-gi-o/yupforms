<?php

namespace YupForms\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use YupForms\YupFormData;

class YupFormDataController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, DataTables;

    const CONTROLLER = '\YupForms\Http\YupFormDataController';

    public function __construct(YupFormData $yupformData)
    {
        $this->yupformData = $yupformData;
    }

    /**
     * Function used by Datatable ajax call.  All parameters are Datatables parameters
     *
     * @return false|string
     */
    public function formDataList()
    {
        // these are all the fields that can sorted using datatables api. Must match table column order
        $sortFields = [
            'created_at',
            null,
            'flagged',
        ];

        // these are all the fields that can be searched using the filter api
        $filterFields = [
            'data',
        ];

        $builder = $this->yupformData
            ->select('id', 'form_id', 'flagged', 'data', 'server', 'note', 'created_at')
            ->formId(request('id'))
        ;

        $records = $this->filterSearchSort($builder, $sortFields, $filterFields);

        return $records;
    }

    /**
     * @param $id
     */
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $yupFormData = $this->yupformData
            ->find($id);

        $title = 'Submission: ' . $yupFormData->created_at->format('M d, Y h:m') . ''
            ?? 'Error:';

        return view('yupforms::admin.formdata.show')
            ->with([
                'title' => $title,
                'yupFormData' => $yupFormData
            ]);
    }

    /**
     * Remove the specified resource from storage. Removes form and formdata
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete form and submissions
        $yupFormData = $this->yupformData
            ->find($id);

        //handles deleting
        $yupFormData->remove();

        session()->push('alerts', ['type' => 'success', 'message' => 'Submission deleted']);
        return redirect()->action('yupforms::admin.formdata.show', ['yupform' => $id]);
    }

    /**
     * Toggles the yupform data flag
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFlag($id)
    {
        $yupformData = $this->yupformData
            ->find($id);

        if ($yupformData)
            $yupformData->toggleFlag();

        return redirect()->back();
    }

}
