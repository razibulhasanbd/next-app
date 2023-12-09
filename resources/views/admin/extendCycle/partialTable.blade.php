<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
    <thead>
        <tr>

            <th>
                {{ trans('cruds.customer.fields.id') }}
            </th>

            <th>
                Login Id
            </th>
            <th>
                Week
            </th>

            <th>
                Customer Name
            </th>
            <th>
               Subscription Start Date
            </th>
            <th>
                Subscription End Date
            </th>


        </tr>

    </thead>

    <tbody>

        <tr>
            <th scope="row">{{ $account->latestSubscription->id}}</th>
            <td><a class="btn btn-xs btn-primary" href="{{ url('admin/accounts/'.$account->id) }}">{{ $account->login}}</a></td>
            <td>{{ $weekRequest }}</td>
            <td>{{ $account->customer->name }}</td>
            <td>{{ frontEndTimeConverterView($account->latestSubscription->created_at) }}</td>
            <td>{{ frontEndTimeConverterView($ending_at)}}</td>
            <td><a class="btn btn-xs btn-success" href="{{ url('admin/update/extend/cycle/'.$account->latestSubscription->id.'/'.$ending_at.'/'.$weekRequest) }}">Confirm</a></td>
          </tr>
      </tbody>
</table>
