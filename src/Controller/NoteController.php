<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoteController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->redirectToRoute('note_index');
    }

    #[Route('/notes', name: 'note_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        NoteRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $lang = $request->query->get('lang', 'uk');
        $search = $request->query->get('q', '');
        $sortBy = $request->query->get('sort', 'createdAt');
        $dir = $request->query->get('dir', 'desc');
        $page = max(1, (int) $request->query->get('page', 1));
        $perPage = max(1, (int) $request->query->get('perPage', 6));

        $translations = $this->getTranslations($lang);

        $formTitle = '';
        $formText = '';
        $errors = [];

        if ($request->isMethod('POST') && $request->request->has('create')) {
            $formTitle = trim((string) $request->request->get('title', ''));
            $formText = trim((string) $request->request->get('text', ''));

            if ($formTitle === '') {
                $errors['title'] = $translations['titleRequired'];
            }

            if (!$errors) {
                $note = new Note();
                $note->setTitle($formTitle);
                $note->setText($formText !== '' ? $formText : null);
                $em->persist($note);
                $em->flush();

                return $this->redirectToRoute('note_index', [
                    'lang' => $lang,
                    'q' => $search,
                    'sort' => $sortBy,
                    'dir' => $dir,
                    'page' => $page,
                    'perPage' => $perPage,
                ]);
            }
        }

        $result = $repository->searchPaginated($search, $sortBy, $dir, $page, $perPage);

        $total = $result['total'];
        $items = $result['items'];
        $maxPage = max(1, (int) ceil($total / $perPage));
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        return $this->render('note/index.html.twig', [
            'notes' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'search' => $search,
            'sort' => $sortBy,
            'dir' => $dir,
            'lang' => $lang,
            't' => $translations,
            'errors' => $errors,
            'form_title' => $formTitle,
            'form_text' => $formText,
        ]);
    }

    #[Route('/notes/{id}/edit', name: 'note_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        NoteRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $lang = $request->query->get('lang', 'uk');
        $translations = $this->getTranslations($lang);

        $note = $repository->find($id);
        if (!$note) {
            throw $this->createNotFoundException('Note not found');
        }

        $errors = [];

        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title', ''));
            $text = trim((string) $request->request->get('text', ''));

            if ($title === '') {
                $errors['title'] = $translations['titleRequired'];
            }

            if (!$errors) {
                $note->setTitle($title);
                $note->setText($text !== '' ? $text : null);
                $em->flush();

                return $this->redirectToRoute('note_index', [
                    'lang' => $lang,
                ]);
            }
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'lang' => $lang,
            't' => $translations,
            'errors' => $errors,
        ]);
    }

    #[Route('/notes/{id}/delete', name: 'note_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        NoteRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $lang = $request->query->get('lang', 'uk');

        $note = $repository->find($id);
        if ($note) {
            $em->remove($note);
            $em->flush();
        }

        return $this->redirectToRoute('note_index', ['lang' => $lang]);
    }

    #[Route('/notes/mass-delete', name: 'note_mass_delete', methods: ['POST'])]
    public function massDelete(
        Request $request,
        NoteRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $lang = $request->query->get('lang', 'uk');
        $ids = $request->request->all('ids');

        if (is_array($ids) && $ids) {
            foreach ($ids as $id) {
                $note = $repository->find((int) $id);
                if ($note) {
                    $em->remove($note);
                }
            }
            $em->flush();
        }

        return $this->redirectToRoute('note_index', ['lang' => $lang]);
    }

    private function getTranslations(string $lang): array
    {
        $common = [
            'deleteIcon' => '🗑',
            'editIcon' => '✏️',
        ];

        $uk = [
            'title' => 'Менеджер нотаток — CRUD (Symfony)',
            'searchPlaceholder' => 'Пошук по заголовку або тексту...',
            'find' => 'Знайти',
            'clear' => 'Очистити',
            'sortBy' => 'Сортувати за:',
            'date' => 'Дата',
            'addNote' => 'Додати',
            'titleRequired' => 'Заголовок обовʼязковий',
            'noteTitle' => 'Заголовок',
            'noteText' => 'Текст нотатки',
            'noteNotFound' => 'Нотаток не знайдено.',
            'edit' => '✏️ Редагувати',
            'delete' => '🗑',
            'editModal' => 'Редагувати нотатку',
            'deleteModal' => 'Видалити нотатку?',
            'massDelete' => 'Масове видалення',
            'confirmDelete' => 'Ви збираєтесь видалити нотатку',
            'confirmMassDelete' => 'Ви впевнені, що хочете видалити',
            'loading' => 'Завантаження…',
        ];

        $en = [
            'title' => 'Notes Manager — CRUD (Symfony)',
            'searchPlaceholder' => 'Search by title or text...',
            'find' => 'Find',
            'clear' => 'Clear',
            'sortBy' => 'Sort by:',
            'date' => 'Date',
            'addNote' => 'Add',
            'titleRequired' => 'Title is required',
            'noteTitle' => 'Title',
            'noteText' => 'Note text',
            'noteNotFound' => 'No notes found.',
            'edit' => '✏️ Edit',
            'delete' => '🗑',
            'editModal' => 'Edit note',
            'deleteModal' => 'Delete note?',
            'massDelete' => 'Mass delete',
            'confirmDelete' => 'You are about to delete note',
            'confirmMassDelete' => 'Are you sure you want to delete',
            'loading' => 'Loading…',
        ];

        return $lang === 'uk' ? array_merge($common, $uk) : array_merge($common, $en);
    }
}
