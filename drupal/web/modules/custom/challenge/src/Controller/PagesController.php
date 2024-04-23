<?php

namespace Drupal\challenge\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoryLeak
{
    public function __construct()
    {
        $this->things[] = $this;
    }

    public function __destruct()
    {
        $this->things[] = null;
    }

    private $things = [];
}

class PagesController extends ControllerBase
{

    /**
     * {@inheritdoc}
     */
    protected function getModuleName()
    {
        return 'challenge';
    }

    public function broken()
    {
        \Drupal::service("my_service")->load();
        return [
            '#markup' => '<p>' . $this->t('Broken') . '</p>',
        ];
    }

    public function heavy()
    {
        ini_set('memory_limit', '10M');

        for ($i = 0; $i < 100000; ++$i) {
            $obj = new MemoryLeak();
        }

        return [
            '#markup' => '<p>' . $this->t('Heavy') . '</p>',
        ];
    }

    public function slow()
    {
        $database = \Drupal::database();
        $query = $database->query("SELECT SLEEP(10)");
        $result = $query->fetchAll();

        return [
            '#markup' => '<p>' . $this->t('Slow') . '</p>',
        ];
    }

    public function crash()
    {
        $database = \Drupal::database();
        $query = $database->query("SELECT * FROM `search_index`");
        $result = $query->fetchAll();

        return [
            '#markup' => '<p>' . $this->t('Crash') . '</p>',
        ];
    }

    public function fetch(Request $request)
    {
        $credentials = $request->headers->get('Authorization');
        if ($credentials !== 'Basic ' . base64_encode('username:password')) {
            return new Response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Restricted area"']);
        }

        $data = [
            'message' => 'Bonjour, voici votre JSON !',
            'time' => time(),
        ];

        return new JsonResponse($data);
    }


    public function users(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            return new Response('Method Not Allowed', 405);
        }

        $utilisateurs = array(
            array(
                'id' => 1,
                'nom' => 'Jean Dupont',
                'email' => 'jean.dupont@example.com',
                'role' => 'administrateur'
            ),
            array(
                'id' => 2,
                'nom' => 'Marie Durand',
                'email' => 'marie.durand@example.com',
                'role' => 'utilisateur'
            ),
            array(
                'id' => 3,
                'nom' => 'Pierre Martin',
                'email' => 'pierre.martin@example.com',
                'role' => 'utilisateur'
            )
        );

        return new JsonResponse($utilisateurs);
    }
}
