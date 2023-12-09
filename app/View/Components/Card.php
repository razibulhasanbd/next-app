<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $minimumTradingDays;
     // public $isActiveTradingDay;
    //  public $maxDailyLoss;
    //  public $maxDailyLossLimit;
    //  public $maxMonthlyLoss;
    //  public $maxMonthlyLossLimit;
    public function __construct($minimumTradingDays)
    {
    
        $this->minimumTradingDays = $minimumTradingDays ?? 0;
         //$this->isActiveTradingDay = $isActiveTradingDay ?? 0;
        // $this->maxDailyLoss = $maxDailyLoss ?? 0;
        // $this->maxDailyLossLimit = $maxDailyLossLimit ?? 0;
        // $this->maxMonthlyLoss = $maxMonthlyLoss ?? 0;
        // $this->maxMonthlyLossLimit = $maxMonthlyLossLimit ?? 0;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
       
        return view('components.card');
    }
}
