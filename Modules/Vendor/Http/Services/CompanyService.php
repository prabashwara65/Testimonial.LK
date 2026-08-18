<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;

use Modules\Vendor\Http\Repositories\CompanyRepository;

use App\Http\Constants\Actions;

class CompanyService extends MainService
{
    protected $companyRepository;

    public function __construct(
        CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function updateCompany($request, $id)
    {
        try {
            $company = $this->companyRepository->show($id); //Get company specified by id

            $input = $request->only(['company_name', 'address', 'address_line1', 'address_line2', 'br_no', 'contact_no', 'email', 'region_id', 'country_id']);
            $company->fill($input)->save();

            $logo = $request->file('logo');
            if ($logo) {
                $path = $request->file('logo')->store('logos', 'public');
                $company->update(['logo' => $path]);
            }

            return ['status' => 'success', 'company' => $company];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCompany($editId)
    {
        $company = $this->companyRepository->show($editId);

        $temp['id'] = $company->id;
        $temp['company_name'] = $company->company_name;
        $temp['br_no'] = $company->br_no;
        $temp['contact_no'] = $company->contact_no;
        $temp['email'] = $company->email;
        $temp['address'] = $company->address;
        $temp['address_line1'] = $company->address_line1;
        $temp['address_line2'] = $company->address_line2;
        $temp['region_id'] = $company->region_id;
        $temp['country_id'] = $company->country_id;

        return $temp;
    }

}