<div>
  <div class="card">
    <div class="card-body">
        <div class="card-header">
            Minimun Trading Days
        </div>
        <div class="form-group">

            <ul class="list-group list-group-horizontal">
                <li class="list-group-item" style="width:160px">Minimun</li>
                <li class="list-group-item" style="width:160px">{{ $cardData['minimumTradingDays'] }}</li>
            </ul>

            <ul class="list-group list-group-horizontal">
                <li class="list-group-item" style="width:160px">Current Result</li>
                <li class="list-group-item" style="width:160px">{{ $cardData['isActiveTradingDay'] }}</li>
            </ul>
        </div>
    </div>
</div>
</div>