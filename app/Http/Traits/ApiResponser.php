<?php

namespace App\Http\Traits;


trait ApiResponser
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json(
            [
                "result" => true,
                "message" => $message ?? "Success",
                "data" => $data
            ],
            $code
        );
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json([
            "result" => false,
            "message" => $message ?? "Error",
            "data" => $data,
        ], $code);
    }

    protected function jsonResponse($data = null, $message = null,  $code, $redirectTo = null,)
    {

        $messageType = ($code >= 200 && $code < 300);
        if (request()->wantsJson()) {
            if ($messageType) {
                return $this->success($data, $message, $code);
            }
            return $this->error($data, $message, $code);
        }
        if ($redirectTo) {
            return redirect($redirectTo)->with('message', $message);
        }
        return back()->with($messageType ? 'message' : 'error', $message);
    }
}
