<?php

namespace app\shop\service\statistics;

use app\shop\service\statistics\data\SurveyService;
use app\shop\service\statistics\data\ProductRankingService;
use app\shop\service\statistics\data\UserExpendRankingService;

/**
 * 数据概况服务类
 */
class DataService
{
    /**
     * 获取数据概况
     */
    public function getSurveyData($startDate = null, $endDate = null)
    {
        return (new SurveyService)->getSurveyData($startDate, $endDate);
    }


    /**
     * 商品销售榜
     */
    public function getProductRanking()
    {
        return (new ProductRankingService)->getProductRanking();
    }

    /**
     * 用户消费榜
     */
    public function geUserExpendRanking()
    {
        return (new UserExpendRankingService)->getUserExpendRanking();
    }

}