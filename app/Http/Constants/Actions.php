<?php
namespace App\Http\Constants;

/**
 * Actions
 *
 * Stores the permission names to reduce the duplications
 * to make it easy to change permission names later without modifying everywhere
 * Add the permissions as constants inside the Actions class
 * This class has been registered as an Alias and can be easily used as
 *      Actions::CONST_NAME
 *
 * @package App\Constants
 * @author  Prasith Fernando
 */
class Actions {

    //Admin and Vendor Permission

    const VIEW_REGIONS = 'View Regions';
    const CREATE_REGIONS = 'Create Regions';
    const EDIT_REGIONS = 'Edit Regions';
    const DELETE_REGIONS = 'Delete Regions';

    const VIEW_COUNTRIES = 'View Countries';
    const CREATE_COUNTRIES = 'Create Countries';
    const EDIT_COUNTRIES = 'Edit Countries';
    const DELETE_COUNTRIES = 'Delete Countries';

    const VIEW_PROVINCES = 'View Provinces';
    const CREATE_PROVINCES = 'Create Provinces';
    const EDIT_PROVINCES = 'Edit Provinces';
    const DELETE_PROVINCES = 'Delete Provinces';

    const VIEW_DISTRICTS = 'View Districts';
    const CREATE_DISTRICTS = 'Create Districts';
    const EDIT_DISTRICTS = 'Edit Districts';
    const DELETE_DISTRICTS = 'Delete Districts';

    const VIEW_USERS = 'View Users';
    const CREATE_USERS = 'Create Users';
    const EDIT_USERS = 'Edit Users';

    const VIEW_ROLES = 'View Roles';
    const CREATE_ROLES = 'Create Roles';
    const EDIT_ROLES = 'Edit Roles';

    const VIEW_PERMISSIONS = 'View Permissions';
    const CREATE_PERMISSIONS = 'Create Permissions';
    const EDIT_PERMISSIONS = 'Edit Permissions';

    const VIEW_ACTION_LOG = "View Action Log";

    const VIEW_VENDORS = 'View Vendors';
    const CREATE_VENDORS = 'Create Vendors';
    const EDIT_VENDORS = 'Edit Vendors';

    const VIEW_VENDOR_COMPANIES = 'View Vendor Companies';
    const CREATE_VENDOR_COMPANIES = 'Create Vendor Companies';
    const EDIT_VENDOR_COMPANIES = 'Edit Vendor Companies';
    const DELETE_VENDOR_COMPANIES = 'Delete Vendor Companies';

    const VIEW_PAYMENT_RENEWALS = 'View Payment Renewals';

    const VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK = 'View Vendor Wise Testimonial & Feedback';
    const EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK = 'Edit Vendor Wise Testimonial & Feedback';

    const VIEW_TOTAL_SUMMARY_REPORT = 'View Total Summary Report';
    const VIEW_PRODUCT_REPORT = 'View Product Report';

    const VIEW_SETTINGS = 'View Settings';
    const EDIT_SETTINGS = 'Edit Settings';


    //Vendor Permission

    const DASHBOARD = 'Dashboard';

    const VIEW_COMPANY = 'View Company';
    const EDIT_COMPANY = 'Edit Company';

    const VIEW_BRANCHES = 'View Branches';
    const CREATE_BRANCHES = 'Create Branches';
    const EDIT_BRANCHES = 'Edit Branches';
    const DELETE_BRANCHES = 'Delete Branches';

    const VIEW_PRODUCTS = 'View Products';
    const CREATE_PRODUCTS = 'Create Products';
    const EDIT_PRODUCTS = 'Edit Products';
    const DELETE_PRODUCTS = 'Delete Products';

    const VIEW_SUBPRODUCTS = 'View Subproducts';
    const CREATE_SUBPRODUCTS = 'Create Subproducts';
    const EDIT_SUBPRODUCTS = 'Edit Subproducts';
    const DELETE_SUBPRODUCTS = 'Delete Subproducts';

    const VIEW_CAMPAIGNS = 'View Campaigns';
    const CREATE_CAMPAIGNS = 'Create Campaigns';
    const EDIT_CAMPAIGNS = 'Edit Campaigns';

    const VIEW_TARGETS = 'View Targets';
    const CREATE_TARGETS = 'Create Targets';
    const EDIT_TARGETS = 'Edit Targets';
    const DELETE_TARGETS = 'Delete Targets';

    const VIEW_QUESTIONNAIRES = 'View Questionnaires';
    const CREATE_QUESTIONNAIRES = 'Create Questionnaires';
    const EDIT_QUESTIONNAIRES = 'Edit Questionnaires';

    const VIEW_TESTIMONIALS = 'View Testimonials';
    const EDIT_TESTIMONIALS = 'Edit Testimonials';

    const VIEW_FEEDBACKS = 'View Feedbacks';
    const EDIT_FEEDBACKS = 'Edit Feedbacks';

    const VIEW_REWARDS = 'View Rewards';
    const CREATE_REWARDS = 'Create Rewards';
    const EDIT_REWARDS = 'Edit Rewards';
    const DELETE_REWARDS = 'Delete Rewards';

    const VIEW_CUSTOMERS = 'View Customers';
    const EDIT_CUSTOMERS = 'Edit Customers';

    const VIEW_INCENTIVEPAYMENTS = 'View Incentive Payments';
}
