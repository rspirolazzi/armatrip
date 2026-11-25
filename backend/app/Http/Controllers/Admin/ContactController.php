<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Requests\Admin\CreateContactRequest;
use App\Http\Requests\Admin\UpdateContactRequest;
use App\Models\Contact;
use App\Repositories\Admin\ContactRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ContactController extends AppBaseController
{
    /** @var  ContactRepository */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepository = $contactRepo;
    }

    /**
     * Display a listing of the Contact.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {

        $items_for_page = 25;
        $this->contactRepository->pushCriteria(new RequestCriteria($request));
        $this->contactRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->contactRepository->orderBy('created_at', 'desc');
        $contacts = $this->contactRepository->paginate($items_for_page);

        return view('admin.contacts.index')
            ->with('contacts', $contacts);
    }


    /**
     * Display the specified Contact.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contact = $this->contactRepository->findWithoutFail($id);

        if (empty($contact)) {
            Flash::error('Contact no se encontró');

            return redirect(route('admin.contacts.index'));
        }

        return view('admin.contacts.show')->with('contact', $contact);
    }

    /**
     * Remove the specified Contact from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contact = $this->contactRepository->findWithoutFail($id);

        if (empty($contact)) {
            Flash::error('Contact no se encontró');

            return redirect(route('admin.contacts.index'));
        }

        $this->contactRepository->delete($id);

        Flash::success('Contact se eliminó con éxito.');

        return redirect(route('admin.contacts.index'));
    }
}
