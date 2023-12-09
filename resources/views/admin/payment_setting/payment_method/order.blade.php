@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header text-primary">
        Payment Method Ordering
        <a href="{{ route('admin.payment-method.index')}}" class="btn btn-dark pull-right">Back</a>

    </div>

    <form action="{{ route('admin.payment_method.order')}}" method="GET">
        <div class="row card-body">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="payment_method_sc">{{ trans('cruds.payment_method.fields.country_category') }}</label>
                    <select class="form-control" name="country_category" id="payment_method_cc">
                        @foreach($paymentCountryCategory as $key => $module)
                            <option {{ request('country_category','0') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>
    <section class="panel panel-default">
        <form action="{{ route('admin.payment-method-order.update') }}" method="post">
            {{ csrf_field() }}


            <ul class="payment-list">
                @foreach ($payment_methods as $method)
                    <li draggable="true" ondragend="dragEnd()" ondragover="dragOver(event)" ondragstart="dragStart(event)">{{ $method->name }}
                        -
                        @if($method->status == 1)
                            <span class="badge badge-success">Active</span>
                        @elseif($method->status == 0)
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                        <input type="hidden" name="ids[]" value="{{ $method->id }}"> </li>
                @endforeach
            </ul>
            @can('payment_method_order_update')
            <button type="submit" class="btn btn-info btn-sm btn-responsive m-b-2 m-t-sm" data-placement="bottom" style="float: left; margin: 5px" > Reorder Submit</button>
            @endcan
            <br>
        </form>
    </section>
</div>

<style>

</style>

<style>
    .payment-list li{
        cursor: pointer;
        margin: 5px;
        list-style: decimal-leading-zero;
        background: #e9e9e9;
        width: 90%;
        padding: 5px;
        border-radius: 5px;
    }
</style>
@endsection
@section('scripts')


        <script>
            let selected = null;

            function dragOver(e) {
                if (isBefore(selected, e.target)) {
                    e.target.parentNode.insertBefore(selected, e.target)
                } else {
                    e.target.parentNode.insertBefore(selected, e.target.nextSibling)
                }
            }

            function dragEnd() {
                selected = null;
            }

            function dragStart(e) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', null);
                selected = e.target;
            }

            function isBefore(el1, el2) {
                let cur;
                if (el2.parentNode === el1.parentNode) {
                    for (cur = el1.previousSibling; cur; cur = cur.previousSibling) {
                        if (cur === el2) return true;
                    }
                }
                return false;
            }

            $("#payment_method_cc").on("change", function(){
                let countryCategory = $(this).val();
                console.log(countryCategory);
                window.location = '{{ route('admin.payment_method.order')}}' + '?country_category='+ countryCategory ;
            });
        </script>
@parent

@endsection
