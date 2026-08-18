<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Action;
use Carbon\Carbon;

use App\Models\PaymentRenewal;
use App\Models\VendorCompany;

use App\Http\Constants\Actions;

class PaymentRenewalService extends MainService
{
    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm, $status)
    {
        $data = [];
        if($status == 'paid') {
            
            $paymentRenewal = PaymentRenewal::whereRaw("DATE(paid_date) BETWEEN '".$filterForm['start_date']."' AND '".$filterForm['end_date']."' ");

            if (!empty($search)) {
                $paymentRenewal->where(function($query) use ($columns, $search){
                    $query = $this->generateWhereLikeQuery($query, $columns, $search);
                });
            }
            if (!empty($orderBy)) {
            $paymentRenewal->orderBy($orderBy, $orderDirection);
            } else {
            $paymentRenewal->orderBy('created_at', 'desc');
            }

            if ($filterForm['vendor_company_id'] != 'Any') {
                $paymentRenewal->where('vendor_company_id', $filterForm['vendor_company_id']);
            }

            /// get the filtered row count before limiting the results
            $rows = $paymentRenewal->get();
            $count = count($rows);

            // limit the results for pagination
            $paymentRenewal->offset($offset)->limit($limit);
            $rows = $paymentRenewal->get();

            foreach ($rows as $paymentRenewal) {
                $temp = [
                    $paymentRenewal->company->company_name,
                    $paymentRenewal->company->address . ' ' . $paymentRenewal->company->address_line1 . ' ' . $paymentRenewal->company->address_line2,
                    $paymentRenewal->company->br_no,
                    $paymentRenewal->company->contact_no,
                    $paymentRenewal->company->email,
                    $paymentRenewal->company->region->region,
                    $paymentRenewal->company->country->country,
                    $paymentRenewal->renewal_date,
                    $paymentRenewal->renewal_charge,
                    $paymentRenewal->paid_date,
                    $this->generateActionButtons('admin.payment-renewals', $paymentRenewal->id, ['view' => false, 'edit' => false, 'delete' => Actions::VIEW_PAYMENT_RENEWALS])
                ];
                array_push($data, $temp);
            }
        } elseif($status == 'pending') {

            $vendorCompany = VendorCompany::where('status', 1);

                /// get the filtered row count before limiting the results
                $rows = $vendorCompany->get();
                $count = count($rows);

                // limit the results for pagination
                $vendorCompany->offset($offset)->limit($limit);
                $rows = $vendorCompany->get();

            foreach ($rows as $vendorCompany) {
                
                $nextRenewal = $this->nextRenewal($vendorCompany);

                //Pending Days
                $nowDate = Carbon::now();
                if($nowDate->gt($nextRenewal)) {
                    $days = Carbon::parse($nextRenewal)->diffInDays() . ' Days';

                    $temp = [
                        $vendorCompany->company_name,
                        $vendorCompany->address . ' ' . $vendorCompany->address_line1 . ' ' . $vendorCompany->address_line2,
                        $vendorCompany->br_no,
                        $vendorCompany->contact_no,
                        $vendorCompany->email,
                        $vendorCompany->region->region,
                        $vendorCompany->country->country,
                        $nextRenewal,
                        $vendorCompany->renewal_charge,
                        $days,
                        $this->generateActionButtons('admin.payment-renewals', $vendorCompany->id, ['view' => false, 'edit' => Actions::VIEW_PAYMENT_RENEWALS, 'delete' => false])
                    ];
                    array_push($data, $temp);
                }
            }
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = PaymentRenewal::count(); // count of all the records in the database table

        return $out;
    }

    private function nextRenewal($vendorCompany)
    {
        if($vendorCompany->payments()->exists()) {
            $last_renewal_date = Carbon::parse($vendorCompany->payments->last()->renewal_date);
            $next_renewal_date = $last_renewal_date->addYear()->format('Y-m-d');
        } else {
            $next_renewal_date = $vendorCompany->renewal_start_date;
        }

        return $next_renewal_date;
    }

    public function markAsPaid($request, $id)
    {
        try {
            $vendorCompany = VendorCompany::find($id);
            $nextRenewal = $this->nextRenewal($vendorCompany);

            $input['renewal_date'] = $nextRenewal;
            $input['renewal_charge'] = $vendorCompany->renewal_charge;
            $input['paid_date'] = $request->paid_date;

            $vendorCompany->payments()->create($input);

            return ['status' => 'success', 'vendorCompany' => $vendorCompany];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function markAsUnpaid($id)
    {
        try {
            PaymentRenewal::destroy($id);

            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}