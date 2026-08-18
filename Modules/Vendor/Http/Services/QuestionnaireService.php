<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionAnswer;

use App\Http\Constants\Actions;


class QuestionnaireService extends MainService
{

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $questionnaire = Questionnaire::where('vendor_company_id', auth()->user()->vendor_company_id);
        // * customize end

        if (!empty($search)) {
            $questionnaire = $this->search($questionnaire, $columns, $search);
        }
        if (!empty($orderBy)) {
            $questionnaire->orderBy($orderBy, $orderDirection);
        }else {
            $questionnaire->orderBy('questionnaires.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $questionnaire->get();
        $count = count($rows);

        // limit the results for pagination
        $questionnaire->offset($offset)->limit($limit);
        $rows = $questionnaire->get();

        $data = [];
        foreach ($rows as $questionnaire) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
                $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$questionnaire->id.'" onchange="changeUserStatus(event.target, '. $questionnaire->id .', \''. route('vendor.questionnaires.status') .'\')" ';
                $toggle .= ($questionnaire->status == 1) ? "checked" : "";
                $toggle .= '> <label class="custom-control-label" for="status'.$questionnaire->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $questionnaire->name,
                $questionnaire->campaign->campaign_name,
                $toggle,
                $questionnaire->created_at->format('Y-m-d h:ia'),
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.questionnaires', $questionnaire->id, ['view' => false, 'edit' => Actions::EDIT_QUESTIONNAIRES, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Questionnaire::count(); // count of all the records in the database table

        return $out;
    }

    public function getAll()
    {
        $rows = $this->repository->all();
        return $rows;
    }

    public function create(Request $request)
    {
        try {
            $questionnaire = new Questionnaire();
            $questionnaire->vendor_company_id = auth()->user()->vendor_company_id;
            $questionnaire->campaign_id = $request['campaign_id'];
            $questionnaire->name = $request['name'];
            $questionnaire->created_by = Auth::user()->id;
            $questionnaire->status = $request['status'];
            $questionnaire->save();

            if (!empty($request['questions'])) {
                foreach ($request['questions'] as $q) {
                    $question = new Question();
                    $question->type_id = $q['question_type'];
                    $question->question = $q['question'];
                    if (isset($q['required_needed'])) {
                        $question->required_needed = '1';
                    }

                    $questionnaire->questions()->save($question);

                    if (in_array($q['question_type'], ['2', '3'])) {
                        if (count($q['answers'])) {
                            foreach ($q['answers'] as $ans) {
                                if (!empty($ans)) {
                                    $answer = new QuestionAnswer();
                                    $answer->value = $ans;
                                    $question->answers()->save($answer);
                                }
                            }
                        }
                    } elseif ($q['question_type'] == '4') { // star rating
                        if (!empty($q['star_count'])) {
                            $answer = new QuestionAnswer();
                            $answer->value = $q['star_count'];
                            $question->answers()->save($answer);
                        }
                    }
                }
            }

            return ['status' => 'success', 'questionnaire' => $questionnaire];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();

            $questionnaire = Questionnaire::findOrFail($id);
            $questionnaire->campaign_id = $request['campaign_id'];
            $questionnaire->name = $request['name'];
            $questionnaire->status = $request['status'];
            $questionnaire->save();

            // all questionnaire question sub question null
            Question::where('questionnaire_id', $id)->update(['sub_question' => null]);

            if (!empty($request['questions'])) {
                foreach ($request['questions'] as $index => $q) {
                    // check if the current question is an already exisiting one and it belongs to
                    // the questionnaire we are trying to edit
                    $questionModel = Question::where('questionnaire_id', $questionnaire->id)->find($index);
                    if ($questionModel) {
                        $required_needed = null;
                        if (isset($q['required_needed'])) {
                            $required_needed = '1';
                        }

                        // if the old answer type was 'Star Rating' and the new Type is 'single/multi selection' then
                        // delete star rating value
                        if ($questionModel->type_id == 4 && in_array($q['question_type'], ['2', '3'])) {
                            QuestionAnswer::where('question_id', $questionModel->id)->delete();
                        }

                        $questionModel->update([
                            'type_id' => $q['question_type'],
                            'question' => $q['question'],
                            'required_needed' => $required_needed,

                        ]);

                        $question = Question::find($index);
                    } else { // this is a new question. create a new record
                        $question = new Question();
                        $question->type_id = $q['question_type'];
                        $question->question = $q['question'];

                        $questionnaire->questions()->save($question);
                    }

                    if (in_array($q['question_type'], ['2', '3'])) {
                        if (count($q['answers'])) {

                            $ansModel = QuestionAnswer::where('question_id', $question->id)->delete();

                            foreach ($q['answers'] as $ansIndex => $ans) {
                                if (!empty($ans)) {
                                    $answer = new QuestionAnswer();
                                    $answer->value = $ans;
                                    $answer->sub_questionnaire_question_id = $q['subQuestion'][$ansIndex];
                                    $question->answers()->save($answer);

                                    if ($q['subQuestion'][$ansIndex]) {
                                        $subQuestionNoList = explode(",", $q['subQuestion'][$ansIndex]);
                                        foreach ($subQuestionNoList as $subQuestionNo) {
                                            $questionnaire2 = Question::find($subQuestionNo);
                                            $questionnaire2->sub_question = 'Y';
                                            $questionnaire2->save();
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($q['question_type'] == '4') { // star rating
                        if (!empty($q['star_count'])) {
                            $ansModel = QuestionAnswer::where('question_id', $question->id)->find($index);

                            // remove exisiting records if the old type was MCQ
                            QuestionAnswer::where('question_id', $question->id)->delete();

                            if ($ansModel) {
                                $ansModel->update([
                                    'value' => $q['star_count']
                                ]);
                            } else {
                                $answer = new QuestionAnswer();
                                $answer->value = $q['star_count'];
                                $question->answers()->save($answer);
                            }
                        }
                    } else { // for text answers
                        // remove exisiting records if the old type was MCQ
                        QuestionAnswer::where('question_id', $question->id)->delete();
                    }
                }
            }

            DB::commit();
            return ['status' => 'success', 'questionnaire' => $questionnaire];
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $row = Questionnaire::findOrFail($id);
            $row->delete();
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function get($editId)
    {
        try {
            $model = Questionnaire::findOrFail($editId);
            return $model;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $questionnaire = $this->get($request->id); //Get questionnaire specified by id

            $input = $request->only(['status']);
            $questionnaire->fill($input)->save();

            return ['status' => 'success', 'questionnaire' => $questionnaire];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Questionnaires';

            $columnHeaders = array('Questionnaire Name', 'Campaign Name', 'Created On', 'Status');
            $tableColumns = array('name', 'campaign_name', 'created_at', 'status');

            $model = Questionnaire::where('questionnaires.vendor_company_id', auth()->user()->vendor_company_id)
                            ->join('campaigns', 'questionnaires.campaign_id', 'campaigns.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = array('name', 'campaign_name', 'questionnaires.created_at');
            array_push( $select,
                        DB::raw("(CASE WHEN questionnaires.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
                    );
            $model = $model->get($select);

            return $this->exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model);
    
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);

            $query->orWhereHas('campaign', function ($q) use ($search){
                $q->where('campaigns.campaign_name', 'LIKE', '%'.$search.'%');
            });
        });

        return $model;
    }
}