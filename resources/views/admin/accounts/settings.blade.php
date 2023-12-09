@extends('layouts.admin')
@section('content')
    <div class="row">
        @can('account-settings-deposite')
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Deposit
                    <span id="accountBalance" class="badge badge-info">{{ trans('cruds.account.fields.balance') }} {{ $account->balance }}</span>
                    <span id="accountEquity" class="badge badge-warning">{{ trans('cruds.account.fields.equity') }}:
                        {{ $account->equity }}</span>
                </div>
                <div id="deposite"></div>

                <div class="card-body">
                   
            <form >
                    <div class="form-group">
                        <label class="required" for="title">Login Number</label>
                        <input  disabled class="form-control" value="{{ $account->login }}"
                            name="login">
                    </div>

                    <div class="form-group">
                        <label class="required" for="title">Deposit Amount</label>
                        <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="number" step="any"
                            name="depositeAmount" required>
                        @if ($errors->has('depositeAmount'))
                            <div class="invalid-feedback">
                                {{ $errors->first('depositeAmount') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-deposit">
                            Deposit
                        </button>
                    </div>
            </form>
                    
                </div>
            </div>
        </div>
        @endcan

        @can('account-settings-withdraw')
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Withdraw
                </div>
                <div id="withdraw"></div>

                <div class="card-body">
                    <form >
                    <div class="form-group">
                        <label class="required" for="title">Login Number</label>
                        <input  disabled class="form-control" value="{{ $account->login }}"
                            name="login">
                    </div>

                    <div class="form-group">
                        <label class="required" for="title">Withdraw Amount</label>
                        <input class="form-control" type="text"
                            name="amount" required>
                        @if ($errors->has('amount'))
                            <div class="invalid-feedback">
                                {{ $errors->first('amount') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-submit-withdraw">
                            Withdraw
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </div>

<div class="row">
    {{-- @can('account-settings-group')
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Account Group
                    <span class="badge badge-info">Group Name: {{ $userGroupName }}</span>
                </div>
                <div id="chGroup"></div>

                <div class="card-body">
                    <form >
                    <div class="form-group">
                        <label class="required" for="title">Login Number</label>
                        <input  disabled class="form-control" value="{{ $account->login }}"
                            name="login">
                    </div>

                    <div class="form-group">
                        <label class="required" for="rule_name_id">All Group List</label>
                        <select class="form-control select2 {{ $errors->has('getAllGroups') ? 'is-invalid' : '' }}" name="getGroups" id="getGroups" required>
                            @foreach($getAllGroups as $groupName)
                                <option value="{{ $groupName }}">{{ $groupName  }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('getAllGroups'))
                            <div class="invalid-feedback">
                                {{ $errors->first('getAllGroups') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button class="btn btn-warning btn-submit-group">
                            Change Group
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan --}}

            {{-- Resel Loss Metric --}}
            @can('account-settings-reset-metric')
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Reset Loss Metric
                    <span class="badge badge-info">Account : {{ $account->breached == '0' ? 'On' : 'Off' }}</span>
                </div>
                <div id="resetMetric"></div>

                <div class="card-body">
                    <form >
                    <div class="form-group">
                        <label class="required" for="title">Login Number</label>
                        <input  disabled class="form-control" value="{{ $account->login }}"
                            name="login">
                    </div>

                    <div class="form-group">
                        <label for="title"><b>Account Metric Info</b></label><br>
                        <span class="badge badge-pill badge-info">LatBalance : {{ $account->latestMetric->lastBalance }}</span>
                        <span class="badge badge-pill badge-info">LastEquity : {{ $account->latestMetric->lastEquity }}</span> 
                        <span class="badge badge-pill badge-info">CreatedAt : {{ $account->latestMetric->created_at }}</span> 
                    </div>

                    <div class="form-group">
                        <button class="btn btn-dark btn-submit-reset-metric">
                            Reset Metric
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </div>
@endsection

@section('scripts')

<script type="text/javascript">
   
   $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".btn-deposit").click(function(e){
  
        e.preventDefault();
   
        var login = $("input[name=login]").val();
        var depositeAmount = $("input[name=depositeAmount]").val();
    
        $.ajax({
           type:'post',
           url:"{{ route('admin.account-settings-deposit.view') }}",
           data:{login:login, depositeAmount:depositeAmount},
           beforeSend: function() { 
            $(".btn-deposit").prop('disabled', true); // disable button
            },
           success:function(data){
            $('#accountBalance').html('Balance: '+data.margin.currentBalance);
            $('#accountEquity').html('Withdraw: '+data.margin.currentEquity);
            $('#deposite').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+ data.message +'</strong></div>');
            $(".btn-deposit").prop('disabled', false);
           }
        });
  
    });
</script>

<script type="text/javascript">
   
    $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     $(".btn-submit-withdraw").click(function(e){
   
         e.preventDefault();
    
         var login = $("input[name=login]").val();
         var amount = $("input[name=amount]").val();
     
         $.ajax({
            type:'post',
            url:"{{ route('admin.account-settings-withdraw.view') }}",
            data:{login:login, amount:amount},
            beforeSend: function() { 
            $(".btn-submit-withdraw").prop('disabled', true); // disable button
            },
            success:function(data){
                $('#accountBalance').html('Balance: '+data.margin.currentBalance);
                $('#accountEquity').html('Withdraw: '+data.margin.currentEquity);
                $('#withdraw').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+ data.message +'</strong></div>');
                $(".btn-submit-withdraw").prop('disabled', false);
            }
         });
   
     });
 </script>

{{-- Change Account Group --}}
<script type="text/javascript">

   
    $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     $(".btn-submit-group").click(function(e){
   
         e.preventDefault();
    
         var login = $("input[name=login]").val();
         var getGroups = $("select[name=getGroups]").val();

         $.ajax({
            type:'post',
            url:"{{ route('admin.account-settings-groupChange.view') }}",
            data:{login:login, getGroups:getGroups},
            success:function(data){
                $('#chGroup').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+ data.message +'</strong></div>');
               
            }
         });
   
     });
 </script>


{{-- Rest Account Metric --}}


<script type="text/javascript">
   
    $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     $(".btn-submit-reset-metric").click(function(e){
   
         e.preventDefault();
    
         var login = $("input[name=login]").val(); 
         $.ajax({
            type:'post',
            url:"{{ route('admin.account-settings-reset-metric.view') }}",
            data:{login:login},
            beforeSend: function() { 
            $(".btn-submit-reset-metric").prop('disabled', true); // disable button
            },
            success:function(data){
                $('#resetMetric').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+ data.message +'</strong></div>');
                $(".btn-submit-reset-metric").prop('disabled', false);
            }
         });
   
     });
 </script>




@endsection
