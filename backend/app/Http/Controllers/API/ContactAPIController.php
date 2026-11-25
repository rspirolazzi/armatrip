<?php

namespace App\Http\Controllers\API;

use App\Events\ContactEvent;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateContactAPIRequest;
use App\Repositories\Admin\ContactRepository;
use Response;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Store;

/**
 * Class ContactController
 * @package App\Http\Controllers\API\Admin
 */

class ContactAPIController extends AppBaseController
{
    /** @var  ContactRepository */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepository = $contactRepo;
    }


    /**
     * Store a newly created Contact in storage.
     * POST /contacts
     *
     * @param CreateContactAPIRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateContactAPIRequest $request)
    {
        $input = $request->all();
        $contacts = $this->contactRepository->create($input);
        event(new ContactEvent($contacts));
        return $this->sendResponse($contacts->toArray(), 'Contact se guardó con éxito');
    }

    function storeContact(Request $request){
        $input = $request->all();
        $input = $input['data'];
        Contact::create(['name' => $input['name'],'phone' => $input['phone_code'].' '.$input['phone_number'],'comment' => $input['comment'],'store_code' => $input['store_code']]);
        return $this->sendResponse(null, 'Contact se guardó con éxito');
    }
}
