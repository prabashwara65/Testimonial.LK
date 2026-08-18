<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Target;

use Modules\Vendor\Http\Repositories\TargetRepository;

use App\Http\Constants\Actions;

class TargetService extends MainService
{
    protected $targetRepository;

    public function __construct(
        TargetRepository $targetRepository)
    {
        $this->targetRepository = $targetRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $target = Target::where('vendor_company_id', auth()->user()->vendor_company_id);
        // * customize end

        if (!empty($search)) {
            $target = $this->search($target, $columns, $search);
        }
        if (!empty($orderBy)) {
            $target->orderBy($orderBy, $orderDirection);
        } else {
            $target->orderBy('targets.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $target->get();
        $count = count($rows);

        // limit the results for pagination
        $target->offset($offset)->limit($limit);
        $rows = $target->get();

        $data = [];
        foreach ($rows as $target) {

            $target_type = ($target->target_type == '1' ? 'Common Target' : 'Special Target');

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$target->id.'" onchange="changeUserStatus(event.target, '. $target->id .', \''. route('vendor.targets.status') .'\')" ';
            $toggle .= ($target->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$target->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $target->created_at->format('Y-m-d h:ia'),
                $target->target_name,
                $target_type,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.targets', $target->id, ['view' => Actions::VIEW_TARGETS, 'edit' => Actions::EDIT_TARGETS, 'delete' => Actions::DELETE_TARGETS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Target::count(); // count of all the records in the database table

        return $out;
    }

    public function createTarget($request)
    {
        try {
            if($request['target_type'] == 1){
                $input = $request->only(['target_name', 'target_type', 'target']);
            }
            else
            {
                $input = $request->only(['target_name', 'target_type', 'video', 'video_type', 'audio', 'audio_type', 'image', 'image_type', 'text', 'text_type']);
            }
            
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            $target = $this->targetRepository->create($input);

            return ['status' => 'success', 'target' => $target];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateTarget($request, $id)
    {
        try {
            $target = $this->targetRepository->show($id); //Get target specified by id

            if($request['target_type'] == 1){
                $input = $request->only(['target_name', 'target_type', 'target']);

                $input['video'] = $input['audio'] = $input['image'] = $input['text'] = Null;
                $input['video_type'] = $input['audio_type'] =  $input['image_type'] = $input['text_type'] = 3;
            }
            else
            {
                $input = $request->only(['target_name', 'target_type', 'video', 'video_type', 'audio', 'audio_type', 'image', 'image_type', 'text', 'text_type']);

                $input['target'] = Null;
            }

            $target->fill($input)->save();

            return ['status' => 'success', 'target' => $target];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getTarget($editId)
    {
        $target = $this->targetRepository->show($editId);

        $temp['id'] = $target->id;
        $temp['target_name'] = $target->target_name;
        $temp['target_type'] = $target->target_type;
        $temp['target'] = $target->target;
        $temp['video'] = $target->video;
        $temp['video_type'] = $target->video_type;
        $temp['audio'] = $target->audio;
        $temp['audio_type'] = $target->audio_type;
        $temp['image'] = $target->image;
        $temp['image_type'] = $target->image_type;
        $temp['text'] = $target->text;
        $temp['text_type'] = $target->text_type;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->targetRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $target = $this->targetRepository->show($request->id); //Get target specified by id

            $input = $request->only(['status']);
            $target->fill($input)->save();

            return ['status' => 'success', 'target' => $target];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Targets';

            $columnHeaders = array('Date/Time', 'Target Name', 'Target Type', 'Status');
            $tableColumns = array('created_at', 'target_name', 'target_type', 'status');

            $model = Target::where('vendor_company_id', auth()->user()->vendor_company_id);

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = $tableColumns;
            array_push( $select,
                        DB::raw("(CASE WHEN targets.target_type = '1' THEN 'Common Target' ELSE 'Special Target' END) AS target_type"),
                        DB::raw("(CASE WHEN targets.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
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
        });

        return $model;
    }

}