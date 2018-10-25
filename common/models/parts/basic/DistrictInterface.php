<?php
namespace common\models\parts\basic;

interface DistrictInterface{

    public function getName();

    public function getCityCode();

    public function getAdCode();

    public function getList();
}
