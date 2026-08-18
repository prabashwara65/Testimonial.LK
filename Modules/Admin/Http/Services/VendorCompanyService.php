<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\VendorCompany;
use App\Models\Vendor;

use Modules\Admin\Http\Repositories\VendorCompanyRepository;

use App\Http\Constants\Actions;

class VendorCompanyService extends MainService
{
    protected $vendorCompanyRepository;

    public function __construct(
        VendorCompanyRepository $vendorCompanyRepository)
    {
        $this->vendorCompanyRepository = $vendorCompanyRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $vendorCompany = VendorCompany::select();
        // * customize end

        if (!empty($search)) {
            $vendorCompany->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhere(DB::raw("CONCAT(address, ' ', address_line1, ' ', address_line2)"), 'like', '%' . $search . '%');

                $query->orWhereHas('region', function ($q) use ($search){
                    $q->where('region', 'LIKE', '%'.$search.'%');
                });

                $query->orWhereHas('country', function ($q) use ($search){
                    $q->where('country', 'LIKE', '%'.$search.'%');
                });
            });
        }
        if (!empty($orderBy)) {
            $vendorCompany->orderBy($orderBy, $orderDirection);
        } else {
            $vendorCompany->orderBy('vendor_companies.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $vendorCompany->get();
        $count = count($rows);

        // limit the results for pagination
        $vendorCompany->offset($offset)->limit($limit);
        $rows = $vendorCompany->get();

        $data = [];

        foreach ($rows as $vendorCompany) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$vendorCompany->id.'" onchange="changeUserStatus(event.target, '. $vendorCompany->id .', \''. route('admin.vendor-companies.status') .'\')" ';
            $toggle .= ($vendorCompany->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$vendorCompany->id.'"> </label> </div>';

            $companyName = (isset($vendorCompany->logo)) ? "<img class='rounded-circle' src='".asset('storage/'.$vendorCompany->logo)."' \"this.src = '".asset('assets/images/profile_pictures/default.jpg')."';\" height='25px'/> ".$vendorCompany->company_name : $vendorCompany->company_name;

            $temp = [
                // * customize start
                $vendorCompany->br_no,
                $companyName,
                $vendorCompany->email,
                $vendorCompany->contact_no,
                $vendorCompany->address . " " . $vendorCompany->address_line1 . " " . $vendorCompany->address_line2,
                $vendorCompany->country->country,
                $vendorCompany->region->region,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.vendor-companies', $vendorCompany->id, ['view' => false, 'edit' => Actions::EDIT_VENDOR_COMPANIES, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = VendorCompany::count(); // count of all the records in the database table

        return $out;
    }

    public function createVendorCompany($request)
    {
        try {
            $input = $request->only(['company_name', 'address', 'address_line1', 'address_line2', 'br_no', 'contact_no', 'email', 'region_id', 'country_id', 'renewal_start_date',  'renewal_charge', 'file_size']);
            $input['limit_regions'] = json_encode($request->limit_regions);
            $input['limit_countries'] = json_encode($request->limit_countries);
            $input['limit_provinces'] = json_encode($request->limit_provinces);
            $input['limit_districts'] = json_encode($request->limit_districts);
            $vendorCompany = $this->vendorCompanyRepository->create($input);

            $logo = $request->file('logo');
            if ($logo) {
                $path = $request->file('logo')->store('logos', 'public');
                $vendorCompany->update(['logo' => $path]);
            }

            return ['status' => 'success', 'vendorCompany' => $vendorCompany];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateVendorCompany($request, $id)
    {
        try {
            $vendorCompany = $this->vendorCompanyRepository->show($id); //Get vendor company specified by id

            $input = $request->only(['company_name', 'address', 'address_line1', 'address_line2', 'br_no', 'contact_no', 'email', 'region_id', 'country_id', 'renewal_start_date',  'renewal_charge', 'file_size']);
            $input['limit_regions'] = json_encode($request->limit_regions);
            $input['limit_countries'] = json_encode($request->limit_countries);
            $input['limit_provinces'] = json_encode($request->limit_provinces);
            $input['limit_districts'] = json_encode($request->limit_districts);
            $vendorCompany->fill($input)->save();

            $logo = $request->file('logo');
            if ($logo) {
                $path = $request->file('logo')->store('logos', 'public');
                $vendorCompany->update(['logo' => $path]);
            }

            return ['status' => 'success', 'vendorCompany' => $vendorCompany];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVendorCompany($editId)
    {
        $vendorCompany = $this->vendorCompanyRepository->show($editId);

        $temp['id'] = $vendorCompany->id;
        $temp['company_name'] = $vendorCompany->company_name;
        $temp['address'] = $vendorCompany->address;
        $temp['address_line1'] = $vendorCompany->address_line1;
        $temp['address_line2'] = $vendorCompany->address_line2;
        $temp['br_no'] = $vendorCompany->br_no;
        $temp['contact_no'] = $vendorCompany->contact_no;
        $temp['email'] = $vendorCompany->email;
        $temp['region_id'] = $vendorCompany->region_id;
        $temp['country_id'] = $vendorCompany->country_id;
        $temp['renewal_start_date'] = $vendorCompany->renewal_start_date;
        $temp['renewal_charge'] = $vendorCompany->renewal_charge;
        $temp['file_size'] = $vendorCompany->file_size;
        $temp['limit_regions'] = json_decode($vendorCompany->limit_regions);
        $temp['limit_countries'] = json_decode($vendorCompany->limit_countries);
        $temp['limit_provinces'] = json_decode($vendorCompany->limit_provinces);
        $temp['limit_districts'] = json_decode($vendorCompany->limit_districts);

        return $temp;
    }

    public function updateStatus($request)
    {
        try {
            $vendorCompany = $this->vendorCompanyRepository->show($request->id); //Get vendor company specified by id

            $input = $request->only(['status']);
            $vendorCompany->fill($input)->save();

            foreach ($vendorCompany->vendors as $vendor) {
                $vendor->company_status = $request->status;
                $vendor->save();
            }

            return ['status' => 'success', 'vendorCompany' => $vendorCompany];
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
