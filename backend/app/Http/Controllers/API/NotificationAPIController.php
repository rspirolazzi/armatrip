<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class NotificationController
 * @package App\Http\Controllers\API
 */
class NotificationAPIController extends AppBaseController
{
    /** @var  NotificationRepository */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the Notification.
     * GET|HEAD /notifications
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->notificationRepository->pushCriteria(new RequestCriteria($request));
        $this->notificationRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->notificationRepository->scopeQuery(function ($query) {
            return $query->where('active', 1);
        });
        $notifications = $this->notificationRepository->all();
        return $this->sendResponse($notifications->toArray());
    }

    public function byUri(Request $request)
    {
        try {
            $uri = $request->get('uri');
            $notification = $this->notificationRepository->findByField('uri', $uri)->first();
            return $this->sendResponse($notification);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            exit;
        }
        return $this->sendResponse([]);
    }
}
