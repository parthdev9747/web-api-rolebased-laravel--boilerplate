<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function __construct(public Customer $model)
    {
        $modulePermissionPrefix = 'customer-';
        // $this->middleware('permission:' . $modulePermissionPrefix . 'view', ['only' => ['index', 'getDatatable', 'show']]);
        // $this->middleware('permission:' . $modulePermissionPrefix . 'add', ['only' => ['create', 'store']]);
        // $this->middleware('permission:' . $modulePermissionPrefix . 'edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:' . $modulePermissionPrefix . 'delete', ['only' => ['destroy']]);
        // $this->middleware('permission:' . $modulePermissionPrefix . 'status_manage', ['only' => ['updateStatus']]);

        $this->moduleName = "Customer";
        $this->moduleView = "customer";
        $this->moduleRoute = url('customers');
        $this->model = $model;

        View::share('modulePermissionPrefix', $modulePermissionPrefix);
        View::share('module_name', $this->moduleName);
        View::share('module_view', $this->moduleView);
        View::share('module_route', $this->moduleRoute);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->moduleView . ".index");
    }

    public function getDatatable()
    {
        $result = $this->model->with('country', 'currency')->orderBy('id', 'desc');
        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return date('d-m-Y H:i:s', strtotime($row['created_at']));
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::get()->all();
        $currencies = Currency::get()->all();
        return view($this->moduleView . "._form", compact('countries', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $input = $request->all();
        try {
            $status = $this->model->create($input);
            if ($status) {
                Toastr::success($this->moduleName . " Created Successfully", 'Success!!');
                return redirect($this->moduleRoute);
            }
            Toastr::success("Sorry, Something went wrong please try again", 'Error!!');
            return redirect($this->moduleRoute);
        } catch (\Exception $e) {
            Toastr::success("Sorry, Something went wrong please try again", 'Error!!');
            return redirect($this->moduleRoute)->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $countries = Country::get()->all();
        $currencies = Currency::get()->all();
        $result = $this->model->where('id', $id)->first();
        return view($this->moduleView . "._form", compact('countries', 'currencies', 'result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, $id)
    {
        $input = $request->all();
        try {
            $record = $this->model->where('id', $id)->first();
            if ($record) {
                $status = $record->update($input);
                if ($status) {
                    Toastr::success($this->moduleName . " updated Successfully", 'Success!!');
                    return redirect($this->moduleRoute);
                }
            }
            Toastr::success("Sorry, Something went wrong please try again", 'Error!!');
            return redirect($this->moduleRoute);
        } catch (\Exception $e) {
            Toastr::success("Sorry, Something went wrong please try again", 'Error!!');
            return redirect($this->moduleRoute);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = [];
        $data = $this->model->where('id', $id)->first();
        if ($data) {
            $data->delete();
            $response['message'] = $this->moduleName . " Deleted.";
            $response['status'] = true;
        } else {
            $response['message'] = $this->moduleName . " not Found!";
            $response['status'] = false;
        }
        return response()->json($response);
    }
}
