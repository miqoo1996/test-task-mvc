<?php

namespace App\Controllers;

use App\Core\Request\Request;
use App\Models\Text\TextManager;

class HomeController extends Controller
{
    private TextManager $textManager;

    public function __construct(TextManager $textManager)
    {
        $this->getView()->setLayout('layouts/main-layout');

        $this->textManager = $textManager;
    }

    public function index(Request $request)
    {
        return $this->getView()->setView('home/index')->renderView([
            'success' => $request->get('success') == 1,
            'lastTextData' => $this->textManager->lastTextData(),
        ]);
    }

    public function saveText(Request $request) : void
    {
        $success = false;

        $value = $request->post('value');

        if (is_string($value)) {
            $uniqueKey = time() . ':' . uniqid();

            // if ID is not passed, then it adds new rows in DB.
            $success = $this->textManager->saveTextAndNotify(null, $uniqueKey, $value);
        }

        $this->redirect('/?success=' . $success);
    }
}