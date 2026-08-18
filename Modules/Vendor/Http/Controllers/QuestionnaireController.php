<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\QuestionnaireRequest;

use Modules\Vendor\Http\Services\QuestionnaireService;

use App\Models\Campaign;
use App\Models\QuestionType;

use App\Http\Constants\Actions;

class QuestionnaireController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Questionnaires";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Questionnaire Name' => 'questionnaires.name',
        'Campaign Name' => '',
        'Status@no-sort@' => 'questionnaires.status',
        'Created On' => 'questionnaires.created_at',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(QuestionnaireService $questionnaireService)
    {
        $this->questionnaireService = $questionnaireService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_QUESTIONNAIRES, 'vendor');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_QUESTIONNAIRES, 'vendor');
        $data['editPermission'] =$this->checkHasPermission(Actions::EDIT_QUESTIONNAIRES, 'vendor');
        $data['deletePermission'] = false;

        $data['export_route'] = "questionnaires/export";

        $data['scripts'] = ['questionnaires.js'];
        $data['addRoute'] = 'vendor.questionnaires.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.questionnaires.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_QUESTIONNAIRES, 'vendor');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $columns = $this->columns;

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->questionnaireService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function create()
    {
        $this->checkPermissionRedirect(Actions::CREATE_QUESTIONNAIRES, 'vendor');

        $data['campaigns'] = Campaign::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['question_types'] = QuestionType::all();

        $view = View::make('vendor::questionnaires.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    public function getQuestionTemplate($count) {
        $data['count'] = $count;
        $data['question_types'] = QuestionType::all();

        $view = View::make('vendor::questionnaires.question_template', $data)->render();
        $outPutArray = array('status' => 'success', 'data' => $view);
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionnaireRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_QUESTIONNAIRES, 'vendor');

            if (!isset($request['questions'])) {
                $outPutArray = array('status' => 'warning', 'message' => 'At least one question is needed to create a questionnaire', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }

            foreach ($request['questions'] as $question) {
                foreach ($question['answers'] as $answer) {
                    if(empty($answer) && in_array($question['question_type'], [2, 3])) {
                        $outPutArray = array('status' => 'warning', 'message' => 'Answers cannot be empty for Single-selection Or multi-selection question types', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                        return $outPutArray;
                    }
                }
            }

            $result = $this->questionnaireService->create($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_QUESTIONNAIRES, $result['questionnaire']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Questionnaire created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function edit($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_QUESTIONNAIRES, 'vendor');

            $data['questionnaire'] = $this->questionnaireService->get($id);

            $data['campaigns'] = Campaign::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
            $data['question_types'] = QuestionType::all();

            $view = View::make('vendor::questionnaires.edit', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionnaireRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_QUESTIONNAIRES, 'vendor');

            foreach ($request['questions'] as $index => $question) {
                foreach ($question['answers'] as $answer) {
                    if(empty($answer) && in_array($question['question_type'], [2, 3])) {
                        $outPutArray = array('status' => 'warning', 'message' => 'Answers cannot be empty for Single-selection Or multi-selection question types', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                        return $outPutArray;
                    }

                    // CHECK VALIDATION QUESTION HAS IT SELF NUMBER
                    foreach ($question['subQuestion'] as $questionNo){
                        if($questionNo){
                            $questionNo = explode(",",$questionNo);

                            //if(!empty($answer) && in_array($index, $question['subQuestion'])) {
                            if(!empty($answer) && in_array($index, $questionNo)) {
                                $outPutArray = array('status' => 'warning', 'message' => 'Sub-question number, cannot be added itself. (Question No ' . $index . ')', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                                return $outPutArray;
                            }
                        }
                    }
                }
            }

            $result = $this->questionnaireService->update($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_QUESTIONNAIRES, $result['questionnaire']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Questionnaire updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::DELETE_QUESTIONNAIRES);

            $result = $this->questionnaireService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_QUESTIONNAIRES, $id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Questionnaire deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }*/

    public function changeStatus(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_QUESTIONNAIRES, 'vendor');

            $result = $this->questionnaireService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_QUESTIONNAIRES, $result['questionnaire']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Questionnaire status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function export($type = null, $searchValue = null)
    {
        try {
            $columns = $this->columns;

            return $this->questionnaireService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
