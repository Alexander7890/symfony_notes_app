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
        $perPage = (int) $request->query('perPage', 10);
        $perPage = max(1, min(50, $perPage));

        $query = Contact::query()->with('group');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($q).'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.mb_strtolower($q).'%'])
                    ->orWhereRaw('LOWER(phone) LIKE ?', ['%'.mb_strtolower($q).'%'])
                    ->orWhereRaw('LOWER(note) LIKE ?', ['%'.mb_strtolower($q).'%']);
            });
        }

        if (!empty($groupId)) {
            $query->where('group_id', (int) $groupId);
        }

        $contacts = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
        $groups = Group::orderBy('name')->get();

        return view('contacts.index', [
            'contacts' => $contacts,
            'groups' => $groups,
            'q' => $q,
            'groupId' => $groupId,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $data = $this->validateForm($request);
            $contact = new Contact();
            $this->hydrate($contact, $data);
            $contact->save();

            return redirect()->route('contacts.index')->with('status', 'Contact created');
        }

        return $this->formView(new Contact(), false);
    }

    public function edit(Contact $contact, Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $data = $this->validateForm($request);
            $this->hydrate($contact, $data);
            $contact->save();

            return redirect()->route('contacts.index')->with('status', 'Contact updated');
        }

        return $this->formView($contact, true);
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('status', 'Contact removed');
    }

    protected function formView(Contact $contact, bool $isEdit): View
    {
        return view('contacts.form', [
            'item' => $contact,
            'groups' => Group::orderBy('name')->get(),
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
        $contact->group_id = !empty($data['groupId']) ? (int) $data['groupId'] : null;
    }
}
