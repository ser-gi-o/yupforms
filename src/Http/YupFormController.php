<?php

namespace YupForms\Http;

use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use YupForms\YupForm;
use YupForms\YupFormData;
use YupForms\Events\YupFormSubmissionAccepted;
use YupForms\Events\YupFormSubmissionRejected;

class YupFormController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, DataTables;

    const CONTROLLER = '\YupForms\Http\YupFormController';

    public function __construct(YupForm $yupForm, YupFormData $yupFormData)
    {
        $this->yupForm = $yupForm;
        $this->yupFormData = $yupFormData;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('yupforms::admin.form.index')
            ->with('title', 'YupForms');
    }

    /**
     * Function used by Datatable ajax call on forms page.  All parameters are Datatables parameters
     *
     * @return false|string
     */
    public function formIndexList()
    {
        // these are all the fields that can be searched or sorted using datatables api
        $sortFields = [
            'name',
            'description',
            'submissions',
            'created_at',
            'status',
        ];

        $filterFields = [
            'name',
            'description',
        ];

        $builder = $this->yupForm
            ->select('id', 'name','description', 'submissions', 'status', 'created_at')
            //->owner()
        ;

        $records = $this->filterSearchSort($builder, $sortFields, $filterFields);

        return $records;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Request::validate([
            'name' => 'required',
        ]);

        $form = $this->yupForm->store([
            'name'        => request('name'),
            'description' => request('description'),
        ]);

        session()->flash('success', 'Form created');
        return redirect()->action(self::CONTROLLER . '@edit', ['yupform' => $form->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $yupForm = $this->yupForm->find($id);

        return view('yupforms::admin.form.edit')
            ->with([
                'title' => $yupForm->name,
                'yupForm' => $yupForm,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Request::validate([
            'name' => 'required',
            //'recaptcha' => 'required',
        ]);

        $yupForm = $this->yupForm->find($id);

        $yupForm->update([
            'name'        => request('name'),
            'description' => request('description'),
            'host'        => request('host'),
            'status'      => request()->has('status') ? 1 : 0,
        ]);

        session()->push('alerts', ['type' => 'success', 'message' => 'Settings Saved.']);

        return redirect()
            ->action(self::CONTROLLER . '@edit', ['yupform' => $id]);
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
        $yupForm = $this->yupForm->find($id);
        $yupForm->remove();

        //session()->flash('errors',  )
        return redirect()->action(self::CONTROLLER . '@edit', ['yupform' => $id]);
    }

    /**
     * Show the form for testing.
     *
     * @param  int  $publicId
     * @return \Illuminate\Http\Response
     */
    public function testPage($publicId)
    {
        $yupForm = $this->yupForm
            ->findByPublicId($publicId);

        return view('yupforms::admin.form.test-page')
            ->with([
                'title'    => 'Test Form Page',
                'yupForm'  => $yupForm,
                'publicId' => $publicId,
            ]);
    }

    /**
     * Download the csv submissions
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCsv($id)
    {
        $yupForm = $this->yupForm->find($id);
        $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $yupForm->name) . '.csv';

        return response()->streamDownload(function () use ($yupForm) {
            if ($yupForm) {
                echo $yupForm->yupFormDataCsv();
            } else {
                echo 'Error';
            }
        }, $filename);
    }

    /**************************************************************************
     * Available to Public for form submission
     *
     **************************************************************************/
    /**
     * Form submission function
     *
     * @param $publicId
     * @return string
     */
    public function submit($publicId)
    {
        //default response
        $response = ['error' => 1, 'code' => 400, 'message' => 'Thank You'];
        $note = '';

        $formData = request()->all();
        $serverData = request()->server();
        $ajaxRequest = request()->server('HTTP_ACCEPT') == 'application/json';

        $yupForm = $this->yupForm
            ->findByPublicId($publicId);

        if (is_null($yupForm)) {
            $response['message'] = "Not Found ($publicId)";
            event(new YupFormSubmissionRejected($formData, $serverData, $response['message']));

        } else {
            //check form restrictions
            if (!$yupForm->isActive()) {
                $note = "Inactive ($publicId)";

            } elseif ($yupForm->hasHost()) {
                //$requestHost = request()->headers->get('origin')
                $requestHost = request()->server('HTTP_ORIGIN');

                if (!$yupForm->isValidHost($requestHost)) {
                    $note = 'Invalid Host: ' . $requestHost;
                }
            } else {
                $response['error'] = 0;
            }

            $response['code'] = 200;

            $yupFormData = $yupForm->storeSubmission(
                $formData,
                $serverData,
                $response['error'],
                $note
            );
            event(new YupFormSubmissionAccepted($yupFormData));
        }

        if ($ajaxRequest) {
            return response()->json(
                [
                $response['error'] ? 'error' : 'success' => 1,
                'code'    => $response['code'],
                'message' => $response['message']
                ],
                $response['code']
            );

        } else {
            //did(get_class_methods(request()), $serverData['HTTP_REFERER']);
            //no error and _next specified send
            if (request()->has('_next') && $response['code'] != 400)
                $to = request('_next');
            else
                $to = action(self::CONTROLLER . '@submitNext', $response + ['back' => $serverData['HTTP_REFERER']]);

            return redirect()->to($to);
        }
    }

    /**
     * Form submitted page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submitNext()
    {
        return view('yupforms::pub.next')
            ->with(request()->all());
    }
}
