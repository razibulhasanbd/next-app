<?php


namespace App\Utilities;


use Illuminate\Validation\ValidationException;

class ToasterAlert
{
    public static $instance = null;
    protected $key;
    protected $message;

    public function success($message)
    {
        $this->setKeyMessage('success', $message);

        return $this;
    }

    public function warning($message)
    {
        $this->setKeyMessage('warning', $message);

        return $this;
    }

    public function error($message)
    {
        $this->setKeyMessage('error', $message);

        return $this;
    }

    public function info($message)
    {
        $this->setKeyMessage('info', $message);

        return $this;
    }

    public function flash()
    {
        session()->flash( config('admin.toaster_alert.key') . '_'. $this->key, $this->message);
    }

    public function get()
    {
        $prefix = config('admin.toaster_alert.key');
        $list = [];
        foreach(config('admin.toaster_alert.levels') as $level) {
            if (session()->has($prefix .'_'. $level)) {
                $list[$level] = session()->get($prefix .'_'. $level);
            }
        }

        return $list;
    }

    private function setKeyMessage($key, $message)
    {
        $this->key = $key;
        $this->message = $message;
    }
}
