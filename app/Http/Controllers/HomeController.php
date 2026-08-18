<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.21
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewCustomerTestimonials()
    {
        return view('customer-testimonials');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.21
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewCreateTestimonials()
    {
        return view('create-testimonials');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.22
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewCreateCustomerTestimonials()
    {
        return view('sales-rep-create-testimonials');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.22
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewSalesRepDashboard()
    {
        return view('sales-rep-dashboard');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.22
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewTestimonialFeedbackCollection()
    {
        return view('testimonials-feedback-collection');
    }

    /**
     *
     * Created by :Aseniya
     * Created at : 2021.12.22
     *
     * Display a listing of the resource.
     * @return
     */
    public function viewMyProfile()
    {
        return view('my-profile');
    }
}

