<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCode extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var 验证码 */
    private $code;

    /** @var 验证码有效期 */
    private $minutes;

    /**
     * EmailCode constructor.
     *
     * @param mixed $code
     * @param mixed $minutes
     */
    public function __construct($code, $minutes)
    {
        $this->code = $code;
        $this->minutes = $minutes;
    }

    public function build()
    {
        return $this->view('lay-admin::email_code')->with([
            'verify_code' => $this->code,
            'minutes' => $this->minutes,
        ]);
    }
}
