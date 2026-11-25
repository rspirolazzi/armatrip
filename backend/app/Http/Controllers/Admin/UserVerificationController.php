<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Admin\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserVerificationController extends AppBaseController
{

    /**
     * Display a listing of the UserVerification.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $userVerifications = UserVerification::orderBy('created_at', 'desc')->get();

        return view('admin.user_verifications.index')
            ->with('userVerifications', $userVerifications);
    }


}
