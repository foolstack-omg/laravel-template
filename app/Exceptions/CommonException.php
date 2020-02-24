<?php

namespace App\Exceptions;

use App\Traits\ApiTrait;
use Exception;
use Illuminate\Http\Request;

class CommonException extends Exception
{
    use ApiTrait;

    public function __construct(string $message, $code = 0)
    {
        parent::__construct($message, $code);

    }

    public function render(Request $request)
    {
        if ($this->expectsJson($request)) {
            $errors['info'] = $this->getMessage();
            if (config('app.debug')) {
                $errors['file'] = $this->getFile();
                $errors['line'] = $this->getLine();
                $errors['trace'] = $this->getTrace();
            }

            return $this->failed($this->getMessage(), $this->getCode(), $errors);
        }
        return view('pages.error', ['msg' => $this->getMessage()]);
    }
}
