<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Filters\V1\CustomersFilter;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CustomerCollection
     */
    public function index(Request $request): CustomerCollection
    {
        $filter = new CustomersFilter();
        $filterItems = $filter->transform($request);

        $includeInvoices = $request->query("includeInvoices");
        $customers =  Customer::where($filterItems)
        ->when($includeInvoices, function (\Illuminate\Database\Eloquent\Builder $builder)
        {
            return $builder->with("invoices");
        });
        return new CustomerCollection($customers->paginate()->appends($request->query()));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomerRequest $request
     * @return CustomerResource
     */
    public function store(StoreCustomerRequest $request): CustomerResource
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return CustomerResource
     */
    public function show(Customer $customer, Request $request): CustomerResource
    {
        $includeInvoices = $request->query("includeInvoices");
        if ($includeInvoices)
        {
            $customer->loadMissing("invoices");
        }
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomerRequest $request
     * @param Customer $customer
     * @return Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
