<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Vendor;
use Spatie\Permission\Models\Role;

use Modules\Admin\Http\Repositories\VendorRepository;

use App\Http\Constants\Actions;

class VendorService extends MainService
{
    protected $vendorRepository;

    public function __construct(
        VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $vendor = Vendor::role('Vendor');

        if (!empty($search)) {
            $vendor->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhere(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $search . '%');
                $query->orWhere(DB::raw("CONCAT(address, ' ', address_line1, ' ', address_line2)"), 'like', '%' . $search . '%');

                $query->orWhereHas('vendorCompany', function ($q) use ($search){
                    $q->where('company_name', 'LIKE', '%'.$search.'%');
                });

                $query->orWhereHas('region', function ($q) use ($search){
                    $q->where('region', 'LIKE', '%'.$search.'%');
                });

                $query->orWhereHas('country', function ($q) use ($search){
                    $q->where('country', 'LIKE', '%'.$search.'%');
                });
            });
        }
        if (!empty($orderBy)) {
            $vendor->orderBy($orderBy, $orderDirection);
        } else {
            $vendor->orderBy('created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $vendor->get();
        $count = count($rows);

        // limit the results for pagination
        $vendor->offset($offset)->limit($limit);
        $rows = $vendor->get();

        $data = [];

        foreach ($rows as $vendor) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$vendor->id.'" onchange="changeUserStatus(event.target, '. $vendor->id .', \''. route('admin.vendors.status') .'\')" ';
            $toggle .= ($vendor->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$vendor->id.'"> </label> </div>';

            $vendorCompany = (isset($vendor->vendorCompany->logo)) ? "<img class='rounded-circle' src='".asset('storage/'.$vendor->vendorCompany->logo)."' \"this.src = '".asset('assets/images/profile_pictures/default.jpg')."';\" height='25px'/> ".$vendor->vendorCompany->company_name : $vendor->vendorCompany->company_name;

            $temp = [
                // * customize start
                $vendor->emp_id,
                $vendor->name . " " . $vendor->last_name,
                $vendor->nic,
                $vendor->email,
                $vendor->mobile,
                $vendor->address . " " . $vendor->address_line1 . " " . $vendor->address_line2,
                $vendor->country->country,
                $vendor->region->region,
                $vendor->designation,
                $vendor->department,
                $vendorCompany,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.vendors', $vendor->id, ['view' => false, 'edit' => Actions::EDIT_VENDORS, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Vendor::count(); // count of all the records in the database table

        return $out;
    }

    public function createVendor($request)
    {
        try {
            $input = $request->only(['emp_id', 'name', 'last_name', 'nic', 'mobile', 'email', 'address', 'address_line1', 'address_line2', 'username', 'region_id', 'country_id', 'department', 'designation', 'vendor_company_id']);
            $input['password'] = Hash::make($request->input('password'));
            $vendor = $this->vendorRepository->create($input);

            $vendor->assignRole('Vendor');

            return ['status' => 'success', 'vendor' => $vendor];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateVendor($request, $id)
    {
        try {
            $vendor = $this->vendorRepository->show($id); //Get vendor specified by id

            $input = $request->only(['emp_id', 'name', 'last_name', 'nic', 'mobile', 'email', 'address', 'address_line1', 'address_line2', 'username', 'region_id', 'country_id', 'department', 'designation', 'vendor_company_id']);
            if (!empty($request->input('update_password'))) {
                $input['password'] = Hash::make($request->input('password'));
            }
            $vendor->fill($input)->save();

            $vendor->assignRole('Vendor');

            return ['status' => 'success', 'vendor' => $vendor];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVendor($editId)
    {
        $vendor = $this->vendorRepository->show($editId);

        $temp['id'] = $vendor->id;
        $temp['vendor_company_id'] = $vendor->vendor_company_id;
        $temp['emp_id'] = $vendor->emp_id;
        $temp['name'] = $vendor->name;
        $temp['last_name'] = $vendor->last_name;
        $temp['nic'] = $vendor->nic;
        $temp['mobile'] = $vendor->mobile;
        $temp['email'] = $vendor->email;
        $temp['address'] = $vendor->address;
        $temp['address_line1'] = $vendor->address_line1;
        $temp['address_line2'] = $vendor->address_line2;
        $temp['username'] = $vendor->username;
        $temp['region_id'] = $vendor->region_id;
        $temp['country_id'] = $vendor->country_id;
        $temp['designation'] = $vendor->designation;
        $temp['department'] = $vendor->department;
        
        return $temp;
    }

    public function updateStatus($request)
    {
        try {
            $vendor = $this->vendorRepository->show($request->id); //Get vendor specified by id

            $input = $request->only(['status']);
            $vendor->fill($input)->save();

            return ['status' => 'success', 'vendor' => $vendor];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}