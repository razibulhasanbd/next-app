<div class="accordion" id="accordionExample">
    @foreach($historyData as $key => $item)
        <div class="card">
            <div class="card-header" id="heading{{$key}}">
                <h2 class="mb-0">
                    <button style="text-decoration: none" class="btn btn-link btn-block text-left collapsed"
                            type="button" data-toggle="collapse"
                            data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                        Updated By: {{$item->user->name ?? "System " }} - At: {{$item->updated_at }} <i
                            class="fa fa-chevron-down float-right"></i>
                    </button>
                </h2>
            </div>
            <div id="collapse{{$key}}" class="collapse aria-labelledby="heading{{$key}}"
            data-parent="#accordionExample">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Tittle</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $data = json_decode($item->properties, true);
                    @endphp
                    @foreach($data as $key=> $value)
                        @php
                            $str = ucfirst(str_replace('_', ' ', $key));
                        @endphp
                        <tr>
                            <td >{{$str}}</td>
                            <td>{{$value}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>
@endforeach
</div>
