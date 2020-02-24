<?php

namespace App\Exceptions;

use App\Traits\ApiTrait;
use Exception;
use Illuminate\Http\Request;

class InternalException extends Exception
{
    use ApiTrait;

    protected $msgForUser;

    /**
     * InternalException constructor.
     * @param string $message
     * @param string $msgForUser
     * @param int $code
     */
    public function __construct(string $message, $msgForUser = '系统内部错误', $code = 500)
    {
        parent::__construct($message, $code);
        $this->msgForUser = $msgForUser;
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
        return view('pages.error', ['msg' => $this->msgForUser]);
    }
}
