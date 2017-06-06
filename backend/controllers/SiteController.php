<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Game;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
            
        ];
    }

    public function actionIndex()
    {

        $session = Yii::$app->session;

            $game = isset($session['game']) ? $session['game']: null;
            if(!$game) {
                $session->open();
                $game = new Game();
            }

// Обрабатываем запрос пользователя, выполняя нужное действие.
            $params = Yii::$app->request->get();
            if(isset($params['action'])) {
                $action = $params['action'];

                if($action == 'move') {
                    // Обрабатываем ход пользователя.
                    $game->makeMove((int)$params['x'], (int)$params['y']);

                } else if($action == 'newGame') {
                    // Пользователь решил начать новую игру.
                    $session->close();
                    $session->destroy();
                    $session->open();
                    $game = new Game();
                }
            }
// Добавляем вновь созданную игру в сессию.
            $session['game'] = $game;

// Формируем результируюущий массив
            $data_game['width'] = $game->getFieldWidth();
            $data_game['height'] = $game->getFieldHeight();
            $data_game['field'] = $game->getField();
            $data_game['winnerCells'] = $game->getWinnerCells();
            $data_game['CurrentPlayer'] = $game->getCurrentPlayer();
            $data_game['Winner'] = $game->getWinner();

// Передаем в представление
        return $this->render('index', [
            'data_game' => $data_game,
        ]);

	}
        


}
