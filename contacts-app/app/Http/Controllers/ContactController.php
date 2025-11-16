<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $groupId = $request->query('group');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = max(1, min(50, (int) $request->query('perPage', 10)));

        $query = Contact::query()->with('group');

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $query->where(function ($sub) use ($needle) {
                $sub->whereRaw('LOWER(name) LIKE ?', ['%'.$needle.'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.$needle.'%'])
                    ->orWhereRaw('LOWER(phone) LIKE ?', ['%'.$needle.'%'])
                    ->orWhereRaw('LOWER(note) LIKE ?', ['%'.$needle.'%']);
            });
        }

        if (! empty($groupId)) {
            $query->where('group_id', (int) $groupId);
        }

        $total = $query->count();
        $contacts = $query->orderByDesc('id')->forPage($page, $perPage)->get();
        $groups = Group::orderBy('name')->get();
        $totalPages = (int) ceil($total / $perPage);

        return view('contacts.index', compact(
            'contacts', 'groups', 'q', 'groupId', 'page', 'perPage', 'total', 'totalPages'
        ));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $this->validateForm($request);
            $contact = new Contact();
            $this->hydrate($contact, $data);
            $contact->save();

            return redirect()->route('contacts_index');
        }

        return $this->formResponse(new Contact(), false);
    }

    public function edit(int $id, Request $request)
    {
        $contact = Contact::findOrFail($id);

        if ($request->isMethod('post')) {
            $data = $this->validateForm($request);
            $this->hydrate($contact, $data);
            $contact->save();

            return redirect()->route('contacts_index');
        }

        return $this->formResponse($contact, true);
    }

    public function delete(int $id, Request $request): RedirectResponse
    {
        if ($request->isMethod('post')) {
            $contact = Contact::find($id);
            if ($contact) {
                $contact->delete();
            }
        }

        return redirect()->route('contacts_index');
    }

    protected function formResponse(Contact $contact, bool $isEdit): View
    {
        $groups = Group::orderBy('name')->get();

        return view('contacts.form', [
            'item' => $contact,
            'groups' => $groups,
            'isEdit' => $isEdit,
        ]);
    }

    protected function validateForm(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'groupId' => ['nullable', 'integer', 'exists:contact_groups,id'],
        ]);
    }

    protected function hydrate(Contact $contact, array $data): void
    {
        $contact->name = (string) ($data['name'] ?? '');
        $contact->email = (string) ($data['email'] ?? '');
        $phone = $data['phone'] ?? null;
        $note = $data['note'] ?? null;
        $contact->phone = $phone !== '' ? $phone : null;
        $contact->note = $note !== '' ? $note : null;
        $contact->group_id = ! empty($data['groupId']) ? (int) $data['groupId'] : null;
    }
}
