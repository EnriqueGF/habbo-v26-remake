<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationForm;
use App\Models\ApplicationQuestion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Solicitudes de staff (reemplaza legacy/applications.php). Página solo para
 * usuarios logueados ($allow_guests = false en el legacy): el listado de
 * formularios abiertos/cerrados, el detalle de un formulario con sus campos
 * condicionales y preguntas dinámicas, y el envío de la solicitud.
 *
 * Toda consulta es Eloquent parametrizado (el legacy concatenaba $_GET/$_POST en
 * los INSERT/SELECT — SQLi). La tabla cms_application_questions no existe en esta
 * instalación; se consulta de forma defensiva (igual que el legacy, cuyo
 * $questionscheck no llevaba "or die" y por tanto saltaba el bloque al fallar).
 */
class ApplicationsController extends Controller
{
    /**
     * Listado: solicitudes abiertas (enabled=1) y cerradas (enabled=0), siempre
     * con deleted=0. Reproduce el contenido de la versión sin ?id del legacy.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user() === null) {
            return redirect()->to('/');
        }

        $open = ApplicationForm::query()
            ->where('enabled', '1')
            ->where('deleted', '0')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $closed = ApplicationForm::query()
            ->where('enabled', '0')
            ->where('deleted', '0')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return view('applications.index', [
            'open' => $open,
            'closed' => $closed,
        ]);
    }

    /**
     * Detalle de un formulario abierto. Si no existe o está cerrado, muestra la
     * vista de "solicitud cerrada" (idéntica al else del legacy). Carga el usuario
     * actual (name/birth) y las preguntas dinámicas con sus opciones.
     */
    public function show(Request $request, int $form): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        $row = ApplicationForm::query()
            ->where('id', $form)
            ->where('enabled', '1')
            ->where('deleted', '0')
            ->first();

        if ($row === null) {
            return view('applications.show', [
                'form' => null,
                'questions' => collect(),
                'submitted' => false,
            ]);
        }

        return view('applications.show', [
            'form' => $row,
            'user' => $user,
            'questions' => $this->questionsFor($form),
            'submitted' => false,
        ]);
    }

    /**
     * Envío de la solicitud (el botón legacy se llamaba "sumbit", con el typo).
     * Inserta en cms_applications con consulta parametrizada y vuelve a mostrar el
     * formulario con el mensaje de confirmación arriba, igual que el legacy.
     */
    public function submit(Request $request, int $form): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->to('/');
        }

        $row = ApplicationForm::query()
            ->where('id', $form)
            ->where('enabled', '1')
            ->where('deleted', '0')
            ->first();

        if ($row === null) {
            return view('applications.show', [
                'form' => null,
                'questions' => collect(),
                'submitted' => false,
            ]);
        }

        // El legacy inserta tal cual lo enviado (sin validación: "No hay
        // verificación automática"). Reproducimos ese comportamiento pero con
        // Eloquent parametrizado y solo las columnas reales de cms_applications.
        Application::create([
            'rankname' => (string) $row->name,
            'username' => (string) $user->name,
            'realname' => (string) $request->input('realname', ''),
            'birth' => (string) ($user->birth ?? ''),
            'sex' => (string) $request->input('sex', ''),
            'country' => (string) $request->input('country', ''),
            'general_information' => (string) $request->input('general_information', ''),
            'experience' => (string) $request->input('experience', ''),
            'education' => (string) $request->input('education', ''),
            'additional_information' => (string) $request->input('additional_information', ''),
            // El campo legacy es "agreement"; guardamos 1 si lo aceptó, 0 si no.
            'accepted_disclaimer' => $request->boolean('agreement') ? 1 : 0,
        ]);

        return view('applications.show', [
            'form' => $row,
            'user' => $user,
            'questions' => $this->questionsFor($form),
            'submitted' => true,
        ]);
    }

    /**
     * Preguntas dinámicas del formulario (aoq=1) con sus opciones (qid = id de la
     * pregunta). Defensivo: la tabla puede no existir en esta instalación, en cuyo
     * caso devolvemos una colección vacía (como el legacy al fallar el SELECT).
     *
     * @return Collection<int, ApplicationQuestion>
     */
    private function questionsFor(int $form): Collection
    {
        try {
            if (! Schema::hasTable('cms_application_questions')) {
                return collect();
            }
        } catch (Throwable) {
            return collect();
        }

        $questions = ApplicationQuestion::query()
            ->where('aid', $form)
            ->where('aoq', '1')
            ->get();

        // Adjunta a cada pregunta sus opciones (filas con qid = id de la pregunta).
        $questions->each(function (ApplicationQuestion $question): void {
            $question->setRelation(
                'options',
                ApplicationQuestion::query()->where('qid', $question->id)->get()
            );
        });

        return $questions;
    }
}
