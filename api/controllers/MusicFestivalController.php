<?php
namespace api\controllers;
use api\models\MusicFestivalModel;

class MusicFestivalController extends Controller{

    protected $access = [
        'peripheral-store' => [null, 'get'],
        'appointment' => [null, 'post'],
        'cancel-appointment' => [null, 'post'],
        'appointment-info' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'peripheral-store' => MusicFestivalModel::SCE_GET_PERIPHERAL_STORE,
        'appointment' => MusicFestivalModel::SCE_APPOINTMENT,
        'cancel-appointment' => MusicFestivalModel::SCE_CANCEL_APPOINTMENT,
        'appointment-info' => MusicFestivalModel::SCE_GET_APPOINTMENT_INFO,
        '_model' => MusicFestivalModel::class,
    ];
}
